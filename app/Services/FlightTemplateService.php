<?php

namespace App\Services;

class FlightTemplateService
{
    /**
     * Airline to template mapping
     */
    protected array $airlineTemplates = [
        // United Airlines
        'UA' => 'united',
        'UAL' => 'united',
        
        // Delta
        'DL' => 'delta',
        'DAL' => 'delta',
        
        // American Airlines
        'AA' => 'american',
        'AAL' => 'american',
        
        // Emirates
        'EK' => 'emirates',
        'UAE' => 'emirates',
        
        // Lufthansa
        'LH' => 'lufthansa',
        'DLH' => 'lufthansa',
        
        // Singapore Airlines
        'SQ' => 'singapore',
        'SIA' => 'singapore',
        
        // Qatar Airways
        'QR' => 'qatar',
        'QTR' => 'qatar',
        
        // British Airways
        'BA' => 'british',
        'BAW' => 'british',
        
        // Air France
        'AF' => 'airfrance',
        'AFR' => 'airfrance',
        
        // KLM
        'KL' => 'klm',
        'KLM' => 'klm',
        
        // Cathay Pacific
        'CX' => 'cathay',
        'CPA' => 'cathay',
        
        // Qantas
        'QF' => 'qantas',
        'QFA' => 'qantas',
        
        // JetBlue
        'B6' => 'jetblue',
        'JBU' => 'jetblue',
        
        // Southwest
        'WN' => 'southwest',
        'SWA' => 'southwest',
        
        // Air Canada
        'AC' => 'aircanada',
        'ACA' => 'aircanada',
        
        // Turkish Airlines
        'TK' => 'turkish',
        'THY' => 'turkish',
        
        // Etihad
        'EY' => 'etihad',
        'ETD' => 'etihad',
    ];

    /**
     * Template configurations
     */
    protected array $templates = [
        'united' => [
            'name' => 'United Airlines',
            'primary_color' => '#002244',
            'accent_color' => '#FFB81C',
            'text_color' => '#FFFFFF',
            'logo_style' => 'bold uppercase',
            'barcode_position' => 'bottom',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'type' => 'eticket', // e-ticket receipt style
        ],
        'delta' => [
            'name' => 'Delta Air Lines',
            'primary_color' => '#E31837',
            'accent_color' => '#003087',
            'text_color' => '#000000',
            'logo_style' => 'red triangle',
            'barcode_position' => 'none',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'type' => 'eticket',
        ],
        'american' => [
            'name' => 'American Airlines',
            'primary_color' => '#0078D2',
            'accent_color' => '#E31837',
            'text_color' => '#000000',
            'logo_style' => 'eagle logo',
            'barcode_position' => 'top-right',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'type' => 'eticket',
        ],
        'emirates' => [
            'name' => 'Emirates',
            'primary_color' => '#D71A21',
            'accent_color' => '#FFFFFF',
            'text_color' => '#FFFFFF',
            'logo_style' => 'arabic + english',
            'barcode_position' => 'bottom',
        ],
        'lufthansa' => [
            'name' => 'Lufthansa',
            'primary_color' => '#05164D',
            'accent_color' => '#FFFFFF',
            'text_color' => '#FFFFFF',
            'logo_style' => 'crane logo',
            'barcode_position' => 'bottom',
        ],
        'singapore' => [
            'name' => 'Singapore Airlines',
            'primary_color' => '#003366',
            'accent_color' => '#F5A623',
            'text_color' => '#FFFFFF',
            'logo_style' => 'bird logo',
            'barcode_position' => 'bottom',
        ],
        'qatar' => [
            'name' => 'Qatar Airways',
            'primary_color' => '#5C0D12',
            'accent_color' => '#FFFFFF',
            'text_color' => '#FFFFFF',
            'logo_style' => 'oryx logo',
            'barcode_position' => 'bottom',
        ],
        'british' => [
            'name' => 'British Airways',
            'primary_color' => '#075AAA',
            'accent_color' => '#FFFFFF',
            'text_color' => '#FFFFFF',
            'logo_style' => 'speedmarque',
            'barcode_position' => 'bottom',
        ],
        'generic' => [
            'name' => 'Airline',
            'primary_color' => '#1e3a5f',
            'accent_color' => '#10b981',
            'text_color' => '#FFFFFF',
            'logo_style' => 'simple',
            'barcode_position' => 'bottom',
        ],
    ];

    /**
     * Get template key from airline code
     */
    public function getTemplateForAirline(?string $iataCode, ?string $icaoCode = null): string
    {
        $iataCode = strtoupper($iataCode ?? '');
        $icaoCode = strtoupper($icaoCode ?? '');
        
        // Try IATA code first
        if (isset($this->airlineTemplates[$iataCode])) {
            return $this->airlineTemplates[$iataCode];
        }
        
        // Try ICAO code
        if (isset($this->airlineTemplates[$icaoCode])) {
            return $this->airlineTemplates[$icaoCode];
        }
        
        // Try to match by airline name (for mock data)
        return $this->matchByAirlineName($iataCode);
    }

    /**
     * Try to match template by airline name
     */
    protected function matchByAirlineName(string $name): string
    {
        $name = strtolower($name);
        
        $mappings = [
            'united' => 'united',
            'delta' => 'delta',
            'american' => 'american',
            'emirates' => 'emirates',
            'lufthansa' => 'lufthansa',
            'singapore' => 'singapore',
            'qatar' => 'qatar',
            'british' => 'british',
            'air france' => 'airfrance',
            'klm' => 'klm',
            'cathay' => 'cathay',
            'qantas' => 'qantas',
            'jetblue' => 'jetblue',
            'southwest' => 'southwest',
            'air canada' => 'aircanada',
            'turkish' => 'turkish',
            'etihad' => 'etihad',
        ];
        
        foreach ($mappings as $keyword => $template) {
            if (str_contains($name, $keyword)) {
                return $template;
            }
        }
        
        return 'generic';
    }

    /**
     * Get template configuration
     */
    public function getTemplateConfig(string $template): array
    {
        return $this->templates[$template] ?? $this->templates['generic'];
    }

    /**
     * Get all available templates
     */
    public function getAvailableTemplates(): array
    {
        return $this->templates;
    }

    /**
     * Get template view name
     */
    public function getTemplateView(string $template): string
    {
        // E-ticket style templates
        if ($template === 'united') {
            return 'flights.templates.united-eticket';
        }
        
        if ($template === 'delta') {
            return 'flights.templates.delta-eticket';
        }
        
        if ($template === 'american') {
            return 'flights.templates.american-eticket';
        }
        
        $view = "flights.templates.{$template}";
        
        // Check if specific template exists, otherwise use generic
        if (view()->exists($view)) {
            return $view;
        }
        
        return 'flights.templates.generic';
    }
}