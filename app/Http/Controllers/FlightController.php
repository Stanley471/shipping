<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket;
use App\Services\FlightApiService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FlightController extends Controller
{
    protected FlightApiService $flightApi;
    
    public function __construct(FlightApiService $flightApi)
    {
        $this->flightApi = $flightApi;
        $this->middleware('auth');
    }
    
    /**
     * Show flight search form
     */
    public function searchForm()
    {
        return view('flights.search');
    }
    
    /**
     * Search for flights
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3|different:origin',
            'date' => 'required|date|after_or_equal:today',
            'passengers' => 'required|integer|min:1|max:9',
        ]);
        
        $flights = $this->flightApi->searchFlights(
            strtoupper($validated['origin']),
            strtoupper($validated['destination']),
            $validated['date']
        );
        
        $originAirport = $this->flightApi->getAirport($validated['origin']);
        $destinationAirport = $this->flightApi->getAirport($validated['destination']);
        
        return view('flights.results', [
            'flights' => $flights,
            'origin' => $originAirport,
            'destination' => $destinationAirport,
            'search' => $validated,
        ]);
    }
    
    /**
     * Show ticket generation form
     */
    public function bookForm(Request $request)
    {
        $flight = $request->input('flight');
        
        if (!$flight) {
            return redirect()->route('flights.search');
        }
        
        return view('flights.book', [
            'flight' => $flight,
        ]);
    }
    
    /**
     * Generate ticket PDF and save to database
     */
    public function generateTicket(Request $request)
    {
        $validated = $request->validate([
            'passenger_name' => 'required|string|max:100',
            'flight_number' => 'required|string',
            'airline' => 'required|string',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'departure_time' => 'required|string',
            'arrival_time' => 'required|string',
            'date' => 'required|date',
            'seat_class' => 'required|in:economy,business,first',
            'price' => 'required|numeric|min:1|max:999999',
        ]);
        
        // Generate random ticket details
        $ticketNumber = 'TKT' . strtoupper(Str::random(8));
        $bookingRef = strtoupper(Str::random(6));
        $seatNumber = rand(1, 50) . chr(65 + rand(0, 5));
        $gate = 'T' . rand(1, 5) . '-' . rand(1, 50);
        $boardingTime = date('H:i', strtotime($validated['departure_time']) - 3600);
        
        // Save to database
        $flightTicket = FlightTicket::create([
            'user_id' => auth()->id(),
            'ticket_number' => $ticketNumber,
            'booking_reference' => $bookingRef,
            'passenger_name' => strtoupper($validated['passenger_name']),
            'flight_number' => $validated['flight_number'],
            'airline' => $validated['airline'],
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'flight_date' => $validated['date'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'],
            'seat' => $seatNumber,
            'gate' => $gate,
            'class' => strtoupper($validated['seat_class']),
            'price' => $validated['price'],
        ]);
        
        $ticket = [
            'ticket_number' => $ticketNumber,
            'booking_reference' => $bookingRef,
            'passenger_name' => strtoupper($validated['passenger_name']),
            'flight_number' => $validated['flight_number'],
            'airline' => $validated['airline'],
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'date' => $validated['date'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'],
            'boarding_time' => $boardingTime,
            'seat' => $seatNumber,
            'gate' => $gate,
            'class' => strtoupper($validated['seat_class']),
            'price' => $validated['price'],
            'barcode' => $this->generateBarcode($ticketNumber),
            'qr_code' => $this->generateQRCode($bookingRef),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('flights.ticket-pdf', compact('ticket'));
        $pdf->setPaper([0, 0, 595.28, 283.47]);
        
        // Save PDF to storage
        $filename = "tickets/{$bookingRef}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());
        
        // Update ticket with PDF path
        $flightTicket->update(['pdf_path' => $filename]);
        
        return redirect()->route('flights.show', $flightTicket)
            ->with('success', 'Flight ticket generated successfully!');
    }
    
    /**
     * Show user's ticket history
     */
    public function index()
    {
        $tickets = auth()->user()
            ->flightTickets()
            ->latest()
            ->paginate(10);
            
        return view('flights.index', compact('tickets'));
    }
    
    /**
     * Show single ticket
     */
    public function show(FlightTicket $flightTicket)
    {
        // Ensure user owns this ticket
        if ($flightTicket->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('flights.show', compact('flightTicket'));
    }
    
    /**
     * Download ticket PDF
     */
    public function download(FlightTicket $flightTicket)
    {
        // Ensure user owns this ticket
        if ($flightTicket->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Update download stats
        $flightTicket->update([
            'download_count' => $flightTicket->download_count + 1,
            'last_downloaded_at' => now(),
        ]);
        
        // If PDF exists in storage, serve it
        if ($flightTicket->pdf_path && Storage::disk('public')->exists($flightTicket->pdf_path)) {
            return Storage::disk('public')->download(
                $flightTicket->pdf_path,
                "ticket-{$flightTicket->booking_reference}.pdf"
            );
        }
        
        // Otherwise regenerate
        $ticket = [
            'ticket_number' => $flightTicket->ticket_number,
            'booking_reference' => $flightTicket->booking_reference,
            'passenger_name' => $flightTicket->passenger_name,
            'flight_number' => $flightTicket->flight_number,
            'airline' => $flightTicket->airline,
            'origin' => $flightTicket->origin,
            'destination' => $flightTicket->destination,
            'date' => $flightTicket->flight_date->format('Y-m-d'),
            'departure_time' => $flightTicket->departure_time,
            'arrival_time' => $flightTicket->arrival_time,
            'boarding_time' => date('H:i', strtotime($flightTicket->departure_time) - 3600),
            'seat' => $flightTicket->seat,
            'gate' => $flightTicket->gate,
            'class' => $flightTicket->class,
            'price' => $flightTicket->price,
            'barcode' => $this->generateBarcode($flightTicket->ticket_number),
            'qr_code' => $this->generateQRCode($flightTicket->booking_reference),
        ];
        
        $pdf = Pdf::loadView('flights.ticket-pdf', compact('ticket'));
        $pdf->setPaper([0, 0, 595.28, 283.47]);
        
        return $pdf->download("ticket-{$flightTicket->booking_reference}.pdf");
    }
    
    /**
     * Delete ticket
     */
    public function destroy(FlightTicket $flightTicket)
    {
        if ($flightTicket->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Delete PDF file
        if ($flightTicket->pdf_path && Storage::disk('public')->exists($flightTicket->pdf_path)) {
            Storage::disk('public')->delete($flightTicket->pdf_path);
        }
        
        $flightTicket->delete();
        
        return redirect()->route('flights.index')
            ->with('success', 'Ticket deleted successfully.');
    }
    
    /**
     * API endpoint for airport autocomplete
     */
    public function autocompleteAirports(Request $request)
    {
        $query = $request->input('q', '');
        $airports = $this->flightApi->searchAirports($query);
        
        return response()->json(array_values($airports));
    }
    
    /**
     * Generate barcode number
     */
    protected function generateBarcode(string $ticketNumber): string
    {
        return '1' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT) . '2';
    }
    
    /**
     * Generate QR code data
     */
    protected function generateQRCode(string $bookingRef): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($bookingRef);
    }
}