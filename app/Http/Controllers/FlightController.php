<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket;
use App\Services\FlightApiService;
use App\Services\FlightTemplateService;
use App\Services\CoinService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;


class FlightController extends Controller
{
    protected FlightApiService $flightApi;
    protected FlightTemplateService $templateService;
    protected CoinService $coinService;
    
    public function __construct(FlightApiService $flightApi, FlightTemplateService $templateService, CoinService $coinService)
    {
        $this->flightApi = $flightApi;
        $this->templateService = $templateService;
        $this->coinService = $coinService;
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
        
        // Check if user has enough coins
        $user = auth()->user();
        if (!$this->coinService->canAffordService($user, 'flight_ticket')) {
            $cost = $this->coinService->getServiceCost('flight_ticket');
            $balance = $this->coinService->getBalance($user);
            return back()->with('error', "Insufficient coins. This service costs {$cost} coins. Your balance: {$balance} coins. Please buy more coins.");
        }
        
        // Deduct coins first
        $paymentTx = $this->coinService->payForService(
            $user,
            'flight_ticket',
            'Flight ticket generation - ' . $validated['flight_number'],
            null
        );
        
        if (!$paymentTx) {
            return back()->with('error', 'Payment failed. Please try again.');
        }
        
        try {
            // Generate random ticket details
            $ticketNumber = 'TKT' . strtoupper(Str::random(8));
            $bookingRef = strtoupper(Str::random(6));
            $seatNumber = rand(1, 50) . chr(65 + rand(0, 5));
            $gate = 'T' . rand(1, 5) . '-' . rand(1, 50);
            $boardingTime = date('H:i', strtotime($validated['departure_time']) - 3600);
            $logoPath = public_path('images/airlines/united-logo.png');
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            
            // Detect template from airline
            $airlineCode = substr($validated['flight_number'], 0, 2);
            $template = $this->templateService->getTemplateForAirline($airlineCode, null);
            $templateConfig = $this->templateService->getTemplateConfig($template);
            
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
                'template' => $template,
                'logo' => $logoBase64,
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
                'template' => $template,
                'template_config' => $templateConfig,
                'logo' => $logoBase64,
            ];
            
            return redirect()->route('flights.show', $flightTicket)
                ->with('success', 'Flight ticket generated successfully!');
                
        } catch (\Exception $e) {
            // Refund coins on failure
            $cost = $this->coinService->getServiceCost('flight_ticket');
            $this->coinService->refundCoins(
                $user,
                $cost,
                'Refund for failed ticket generation - ' . $validated['flight_number'],
                $paymentTx?->id
            );
            
            \Log::error('Ticket generation failed, coins refunded', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Ticket generation failed. Your coins have been refunded.');
        }
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
        
        // Regenerate PDF on each download
        $template = $flightTicket->template ?? 'generic';
        $templateConfig = $this->templateService->getTemplateConfig($template);
        $logoPath = public_path('images/airlines/united-logo.png');
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        
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
            'template' => $template,
            'template_config' => $templateConfig,
            'logo' => $logoBase64,
        ];
        
        $templateView = $this->templateService->getTemplateView($template);
        $pdf = Pdf::loadView($templateView, compact('ticket'));
        
        if ($template === 'united') {
            $pdf->setPaper('A4', 'portrait');
        } else {
            $pdf->setPaper([0, 0, 595.28, 283.47]);
        }
        
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