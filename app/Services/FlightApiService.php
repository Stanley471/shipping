<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FlightApiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.aviationstack.com/v1';
    
    public function __construct()
    {
        $this->apiKey = config('services.aviationstack.key', env('AVIATIONSTACK_API_KEY'));
    }
    
    /**
     * Search for flights between two airports on a specific date
     */
    public function searchFlights(string $departure, string $arrival, string $date): array
    {
        $cacheKey = "flights_{$departure}_{$arrival}_{$date}";
        
        // Cache for 1 hour to save API calls
        return Cache::remember($cacheKey, 3600, function () use ($departure, $arrival, $date) {
            // For demo/free tier, we'll use scheduled flights endpoint
            // In production, you'd use the flights endpoint with date params
            
            $response = Http::get("{$this->baseUrl}/flights", [
                'access_key' => $this->apiKey,
                'dep_iata' => $departure,
                'arr_iata' => $arrival,
                'limit' => 10,
            ]);
            
            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            // Return mock data if API fails or no key
            return $this->getMockFlights($departure, $arrival);
        });
    }
    
    /**
     * Get airport information by IATA code
     */
    public function getAirport(string $iataCode): ?array
    {
        $cacheKey = "airport_{$iataCode}";
        
        return Cache::remember($cacheKey, 86400, function () use ($iataCode) {
            $response = Http::get("{$this->baseUrl}/airports", [
                'access_key' => $this->apiKey,
                'iata_code' => $iataCode,
            ]);
            
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                return $data[0] ?? null;
            }
            
            return $this->getMockAirport($iataCode);
        });
    }
    
    /**
     * Search airports by query (for autocomplete)
     */
    public function searchAirports(string $query): array
    {
        // Return common airports for demo
        $commonAirports = [
            ['iata_code' => 'JFK', 'name' => 'John F. Kennedy International', 'city' => 'New York', 'country' => 'USA'],
            ['iata_code' => 'LAX', 'name' => 'Los Angeles International', 'city' => 'Los Angeles', 'country' => 'USA'],
            ['iata_code' => 'LHR', 'name' => 'Heathrow Airport', 'city' => 'London', 'country' => 'UK'],
            ['iata_code' => 'CDG', 'name' => 'Charles de Gaulle Airport', 'city' => 'Paris', 'country' => 'France'],
            ['iata_code' => 'DXB', 'name' => 'Dubai International', 'city' => 'Dubai', 'country' => 'UAE'],
            ['iata_code' => 'HND', 'name' => 'Haneda Airport', 'city' => 'Tokyo', 'country' => 'Japan'],
            ['iata_code' => 'SIN', 'name' => 'Changi Airport', 'city' => 'Singapore', 'country' => 'Singapore'],
            ['iata_code' => 'SYD', 'name' => 'Sydney Kingsford Smith', 'city' => 'Sydney', 'country' => 'Australia'],
            ['iata_code' => 'AMS', 'name' => 'Amsterdam Schiphol', 'city' => 'Amsterdam', 'country' => 'Netherlands'],
            ['iata_code' => 'FRA', 'name' => 'Frankfurt Airport', 'city' => 'Frankfurt', 'country' => 'Germany'],
            ['iata_code' => 'MAD', 'name' => 'Adolfo Suárez Madrid–Barajas', 'city' => 'Madrid', 'country' => 'Spain'],
            ['iata_code' => 'FCO', 'name' => 'Leonardo da Vinci–Fiumicino', 'city' => 'Rome', 'country' => 'Italy'],
            ['iata_code' => 'YYZ', 'name' => 'Toronto Pearson International', 'city' => 'Toronto', 'country' => 'Canada'],
            ['iata_code' => 'HKG', 'name' => 'Hong Kong International', 'city' => 'Hong Kong', 'country' => 'Hong Kong'],
            ['iata_code' => 'BOM', 'name' => 'Chhatrapati Shivaji Maharaj', 'city' => 'Mumbai', 'country' => 'India'],
        ];
        
        if (empty($query)) {
            return $commonAirports;
        }
        
        return array_filter($commonAirports, function ($airport) use ($query) {
            $q = strtolower($query);
            return str_contains(strtolower($airport['name']), $q) ||
                   str_contains(strtolower($airport['city']), $q) ||
                   str_contains(strtolower($airport['iata_code']), $q);
        });
    }
    
    /**
     * Mock flight data for demo/testing without API key
     */
    protected function getMockFlights(string $departure, string $arrival): array
    {
        $airlines = [
            ['name' => 'Emirates', 'iata' => 'EK', 'logo' => 'https://logo.clearbit.com/emirates.com'],
            ['name' => 'Qatar Airways', 'iata' => 'QR', 'logo' => 'https://logo.clearbit.com/qatarairways.com'],
            ['name' => 'Singapore Airlines', 'iata' => 'SQ', 'logo' => 'https://logo.clearbit.com/singaporeair.com'],
            ['name' => 'Lufthansa', 'iata' => 'LH', 'logo' => 'https://logo.clearbit.com/lufthansa.com'],
            ['name' => 'British Airways', 'iata' => 'BA', 'logo' => 'https://logo.clearbit.com/britishairways.com'],
            ['name' => 'Delta Air Lines', 'iata' => 'DL', 'logo' => 'https://logo.clearbit.com/delta.com'],
            ['name' => 'United Airlines', 'iata' => 'UA', 'logo' => 'https://logo.clearbit.com/united.com'],
            ['name' => 'American Airlines', 'iata' => 'AA', 'logo' => 'https://logo.clearbit.com/aa.com'],
        ];
        
        $flights = [];
        $baseTime = strtotime('tomorrow 08:00');
        
        for ($i = 0; $i < 5; $i++) {
            $airline = $airlines[array_rand($airlines)];
            $flightNumber = $airline['iata'] . rand(100, 999);
            $departureTime = $baseTime + ($i * 7200) + rand(0, 3600);
            $duration = rand(120, 600); // 2-10 hours
            $arrivalTime = $departureTime + $duration;
            
            $flights[] = [
                'flight' => [
                    'iata_number' => $flightNumber,
                    'icao_number' => $flightNumber,
                ],
                'airline' => [
                    'name' => $airline['name'],
                    'iata_code' => $airline['iata'],
                ],
                'departure' => [
                    'iata_code' => $departure,
                    'scheduled' => date('Y-m-d\TH:i:s', $departureTime),
                    'terminal' => chr(65 + rand(0, 4)), // A-E
                    'gate' => rand(1, 50),
                ],
                'arrival' => [
                    'iata_code' => $arrival,
                    'scheduled' => date('Y-m-d\TH:i:s', $arrivalTime),
                    'terminal' => chr(65 + rand(0, 4)),
                    'baggage' => rand(1, 10),
                ],
                'aircraft' => [
                    'iata_code' => ['B777', 'A380', 'B787', 'A350', 'B737'][rand(0, 4)],
                ],
                'flight_status' => 'scheduled',
            ];
        }
        
        return $flights;
    }
    
    /**
     * Mock airport data
     */
    protected function getMockAirport(string $iataCode): ?array
    {
        $airports = [
            'JFK' => ['iata_code' => 'JFK', 'name' => 'John F. Kennedy International', 'city' => 'New York', 'country' => 'USA', 'timezone' => 'America/New_York'],
            'LAX' => ['iata_code' => 'LAX', 'name' => 'Los Angeles International', 'city' => 'Los Angeles', 'country' => 'USA', 'timezone' => 'America/Los_Angeles'],
            'LHR' => ['iata_code' => 'LHR', 'name' => 'Heathrow Airport', 'city' => 'London', 'country' => 'UK', 'timezone' => 'Europe/London'],
            'CDG' => ['iata_code' => 'CDG', 'name' => 'Charles de Gaulle Airport', 'city' => 'Paris', 'country' => 'France', 'timezone' => 'Europe/Paris'],
            'DXB' => ['iata_code' => 'DXB', 'name' => 'Dubai International', 'city' => 'Dubai', 'country' => 'UAE', 'timezone' => 'Asia/Dubai'],
        ];
        
        return $airports[strtoupper($iataCode)] ?? null;
    }
}