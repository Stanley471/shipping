<?php

namespace App\Http\Controllers;

use App\Services\FlightApiService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class FlightController extends Controller
{
    protected FlightApiService $flightApi;
    
    public function __construct(FlightApiService $flightApi)
    {
        $this->flightApi = $flightApi;
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
     * Generate fake ticket PDF
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
        ]);
        
        // Generate random ticket details
        $ticketNumber = 'TKT' . strtoupper(Str::random(8));
        $bookingRef = strtoupper(Str::random(6));
        $seatNumber = rand(1, 50) . chr(65 + rand(0, 5)); // e.g., 12A
        $gate = 'T' . rand(1, 5) . '-' . rand(1, 50);
        $boardingTime = date('H:i', strtotime($validated['departure_time']) - 3600); // 1 hour before
        
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
            'barcode' => $this->generateBarcode($ticketNumber),
            'qr_code' => $this->generateQRCode($bookingRef),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('flights.ticket-pdf', compact('ticket'));
        $pdf->setPaper([0, 0, 595.28, 283.47]); // Boarding pass size (A5 landscape-ish)
        
        return $pdf->download("ticket-{$bookingRef}.pdf");
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
        // In production, use a QR code library
        // For now, return a data URI placeholder
        return 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($bookingRef);
    }
}