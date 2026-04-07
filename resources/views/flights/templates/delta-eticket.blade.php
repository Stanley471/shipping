<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delta Airlines Electronic Ticket Confirmation - {{ $ticket['booking_reference'] }}</title>
    <style>
        @page {
            margin: 28pt 60pt;
            size: A4;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            color: #000;
            font-size: 10pt;
            line-height: 1.4;
            padding: 28pt 60pt;
            max-width: 800pt;
            margin: 0 auto;
        }

        /* ─── HEADER ─────────────────────────────────── */
        .header-text {
            font-size: 9pt;
            color: #333;
            margin-bottom: 20pt;
        }

        /* ─── LOGO AREA ──────────────────────────────── */
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15pt;
            margin-bottom: 5pt;
        }

        .delta-logo {
            display: flex;
            align-items: center;
        }

        .delta-logo img {
            height: 45pt;
            width: auto;
        }

        .skyteam-logo {
            width: 35pt;
            height: 35pt;
        }

        /* ─── TITLE ──────────────────────────────────── */
        .receipt-title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16pt;
            font-weight: 600;
            color: #003087;
            margin-bottom: 15pt;
        }

        /* ─── BOOKING INFO ───────────────────────────── */
        .booking-info {
            margin-bottom: 20pt;
        }

        .booking-info div {
            font-size: 10pt;
            margin-bottom: 3pt;
        }

        .booking-info .label {
            font-weight: 600;
        }

        .booking-info .passenger-name {
            text-transform: uppercase;
        }

        /* ─── FLIGHT DETAILS HEADER ──────────────────── */
        .section-header {
            font-size: 11pt;
            font-weight: 600;
            text-decoration: underline;
            margin-bottom: 15pt;
        }

        /* ─── FLIGHT CARD ────────────────────────────── */
        .flight-card {
            border: 1pt solid #ccc;
            margin-bottom: 15pt;
            padding: 15pt;
        }

        .flight-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12pt;
            padding-bottom: 8pt;
            border-bottom: 1pt solid #eee;
        }

        .flight-route {
            font-size: 10pt;
            color: #333;
        }

        .flight-route strong {
            font-weight: 600;
        }

        .confirmed-badge {
            color: #2E7D32;
            font-size: 10pt;
            font-weight: 600;
        }

        .confirmed-badge::after {
            content: " \2713";
            color: #2E7D32;
        }

        /* ─── FLIGHT INFO ROW ────────────────────────── */
        .flight-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .airline-info {
            display: flex;
            align-items: flex-start;
            gap: 10pt;
            flex: 0 0 180pt;
        }

        .small-delta-logo {
            width: 0;
            height: 0;
            border-left: 10pt solid transparent;
            border-right: 10pt solid transparent;
            border-bottom: 18pt solid #E31837;
            position: relative;
            flex-shrink: 0;
            margin-top: 2pt;
        }

        .small-delta-logo::after {
            content: '';
            position: absolute;
            left: -5pt;
            top: 7pt;
            width: 0;
            height: 0;
            border-left: 5pt solid transparent;
            border-right: 5pt solid transparent;
            border-bottom: 8pt solid #fff;
        }

        .airline-details {
            font-size: 10pt;
        }

        .airline-name {
            font-weight: 600;
            font-size: 11pt;
        }

        .flight-number {
            color: #333;
        }

        .confirmation-num {
            font-size: 9pt;
            color: #666;
            margin-top: 4pt;
        }

        /* ─── DEPART/ARRIVE SECTION ──────────────────── */
        .flight-times {
            display: flex;
            align-items: center;
            gap: 20pt;
            flex: 1;
            justify-content: center;
        }

        .time-block {
            text-align: center;
        }

        .time-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .time-value {
            font-size: 20pt;
            font-weight: 300;
            color: #000;
            line-height: 1.1;
        }

        .airport-code {
            font-size: 10pt;
            color: #666;
        }

        .airplane-icon {
            font-size: 18pt;
            color: #003087;
        }

        .nonstop {
            text-align: center;
            font-size: 9pt;
            color: #333;
        }

        .nonstop .arrow {
            color: #2E7D32;
            font-size: 16pt;
        }

        /* ─── PAGE FOOTER ────────────────────────────── */
        .page-footer {
            margin-top: 30pt;
            padding-top: 15pt;
            border-top: 1pt solid #ccc;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ───────────────────────────────────── --}}
    <div class="header-text">
        Delta Airlines Electronic Ticket Confirmation - {{ $ticket['booking_reference'] }}-{{ strtoupper(date('FY', strtotime($ticket['date']))) }}
    </div>

    {{-- ── LOGO SECTION ─────────────────────────────── --}}
    <div class="logo-section">
        <div class="delta-logo">
            <img src="{{ public_path('images/airlines/Delta-logo.png') }}" alt="Delta Airlines">
        </div>
        {{-- SkyTeam logo as SVG --}}
        <svg class="skyteam-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48" fill="none" stroke="#003087" stroke-width="2"/>
            <path d="M50 15 L55 40 L80 35 L60 50 L70 75 L50 58 L30 75 L40 50 L20 35 L45 40 Z" fill="none" stroke="#003087" stroke-width="2"/>
            <text x="50" y="88" text-anchor="middle" font-size="8" fill="#003087" font-family="Arial">SKYTEAM</text>
        </svg>
    </div>

    {{-- ── TITLE ────────────────────────────────────── --}}
    <div class="receipt-title">Your Receipt and Itinerary</div>

    {{-- ── BOOKING INFO ─────────────────────────────── --}}
    <div class="booking-info">
        <div><span class="label">Booking Reference :</span> {{ $ticket['booking_reference'] }}</div>
        <div><span class="label">Passengers :</span> <span class="passenger-name">{{ $ticket['passenger_name'] }}</span></div>
    </div>

    {{-- ── FLIGHT DETAILS ───────────────────────────── --}}
    <div class="section-header">Flight Details:</div>

    {{-- Flight Card --}}
    <div class="flight-card">
        <div class="flight-header">
            <div class="flight-route">
                <strong>{{ date('D, M. d, Y', strtotime($ticket['date'])) }} - {{ date('D, M. d, Y', strtotime($ticket['date'])) }}</strong> 
                | {{ $ticket['origin'] }} to {{ $ticket['destination'] }}
            </div>
            <div class="confirmed-badge">Confirmed</div>
        </div>

        <div class="flight-info">
            {{-- Airline Info --}}
            <div class="airline-info">
                <img src="{{ public_path('images/airlines/Delta-logo.png') }}" alt="Delta" style="height: 24pt; width: auto;">
                <div class="airline-details">
                    <div class="airline-name">Delta Airlines(DL)</div>
                    <div class="flight-number">{{ preg_replace('/[^0-9]/', '', $ticket['flight_number']) }}</div>
                    <div class="confirmation-num">Confirmation Number: {{ rand(10000, 99999) }}</div>
                </div>
            </div>

            {{-- Departure --}}
            <div class="time-block">
                <div class="time-label">Depart</div>
                <div class="time-value">{{ date('H:i', strtotime($ticket['departure_time'])) }}</div>
                <div class="airport-code">{{ $ticket['origin'] }}</div>
            </div>

            {{-- Airplane & Nonstop --}}
            <div class="nonstop">
                <div class="airplane-icon">&#9992;</div>
                <div>NON STOP</div>
                <div class="arrow">&#8594;</div>
            </div>

            {{-- Arrival --}}
            <div class="time-block">
                <div class="time-label">Arrive</div>
                <div class="time-value">{{ date('H:i', strtotime($ticket['arrival_time'])) }}</div>
                <div class="airport-code">{{ $ticket['destination'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── PAGE FOOTER ──────────────────────────────── --}}
    <div class="page-footer">
        This is an electronic ticket confirmation. Please present this confirmation along with valid identification at check-in.
    </div>

</body>
</html>
