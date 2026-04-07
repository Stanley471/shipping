<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>American Airlines - e-Ticket Receipt & Itinerary - {{ $ticket['booking_reference'] }}</title>
    <style>
        @page {
            margin: 28pt 50pt;
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
            font-size: 9pt;
            line-height: 1.4;
            padding: 28pt 50pt;
            max-width: 800pt;
            margin: 0 auto;
        }

        /* ─── HEADER ─────────────────────────────────── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15pt;
            border-bottom: 2pt solid #000;
            padding-bottom: 15pt;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15pt;
        }

        .aa-logo {
            height: 50pt;
            width: auto;
        }

        .title-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .main-title {
            font-size: 18pt;
            font-weight: normal;
            color: #000;
            letter-spacing: 1pt;
        }

        .barcode-section {
            text-align: center;
        }

        .barcode-text {
            font-size: 7pt;
            color: #333;
            margin-bottom: 5pt;
            max-width: 150pt;
        }

        .barcode-img {
            height: 40pt;
            width: 150pt;
            background: repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 2px,
                #fff 2px,
                #fff 4px
            );
            margin-bottom: 5pt;
        }

        .barcode-number {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2pt;
        }

        /* ─── DISCLAIMER ─────────────────────────────── */
        .disclaimer {
            font-size: 8pt;
            color: #333;
            margin-bottom: 15pt;
            text-align: justify;
        }

        .disclaimer p {
            margin-bottom: 8pt;
        }

        .disclaimer a {
            color: #0078D2;
            text-decoration: underline;
        }

        /* ─── GRAY SECTION HEADERS ───────────────────── */
        .section-header {
            background: #999;
            color: #fff;
            padding: 4pt 8pt;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8pt;
        }

        /* ─── PASSENGER INFO TABLE ───────────────────── */
        .passenger-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20pt;
        }

        .passenger-table td {
            padding: 3pt 8pt 3pt 0;
            font-size: 9pt;
            vertical-align: top;
        }

        .passenger-table .label {
            font-weight: bold;
            text-transform: uppercase;
            width: 25%;
        }

        .passenger-table .value {
            width: 25%;
        }

        /* ─── DEPARTURE/RETURN SECTIONS ──────────────── */
        .flight-section {
            margin-bottom: 25pt;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5pt;
            text-transform: uppercase;
        }

        /* ─── FLIGHT TABLE ───────────────────────────── */
        .flight-table {
            width: 100%;
            border-collapse: collapse;
        }

        .flight-table th {
            background: #999;
            color: #fff;
            padding: 5pt 6pt;
            font-size: 8pt;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
        }

        .flight-table td {
            padding: 6pt;
            font-size: 9pt;
            border-bottom: 1pt solid #ccc;
            vertical-align: top;
        }

        .flight-table .flight-num {
            font-weight: bold;
        }

        .flight-table .status {
            font-size: 8pt;
        }

        /* ─── FOOTER ─────────────────────────────────── */
        .footer-note {
            font-size: 8pt;
            color: #666;
            margin-top: 30pt;
            padding-top: 10pt;
            border-top: 1pt solid #ccc;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ───────────────────────────────────── --}}
    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('images/airlines/American-Airlines-Logo.png') }}" alt="American Airlines" class="aa-logo">
            <div class="title-section">
                <div class="main-title">e-Ticket Receipt & Itinerary</div>
            </div>
        </div>

        <div class="barcode-section">
            <div class="barcode-text">Scan the barcode above or use the e-ticket number below for self service kiosk check-in</div>
            <div class="barcode-img"></div>
            <div class="barcode-number">1762143480036</div>
        </div>
    </div>

    {{-- ── DISCLAIMER ───────────────────────────────── --}}
    <div class="disclaimer">
        <p>Your electronic ticket is stored in our computer reservation system. This e-Ticket receipt / itinerary is your record of your electronic ticket and forms part of your contract of carriage. You may need to show this receipt to enter the airport and/or to prove return or onward travel to customs and immigration officials.</p>
        
        <p>Your attention is drawn to the Conditions of Contract and Other Important Notices set out in the attached document. Please visit us on <a href="http://www.aa.com">www.aa.com</a> to check-in online and for more information.</p>
        
        <p>Economy Class passengers should report to American Airlines check-in desks 2 hours prior to departure of all flights. First and Business Class passengers should report to American Airlines check-in desks not later than 1 hour prior to departure. Boarding for your flight begins at least 35 minutes before your scheduled departure time. Gates close 15 minutes prior to departure.</p>
        
        <p>Please check with departure airport for restrictions on the carriage of liquids, aerosols and gels in hand baggage.</p>
        
        <p>Below are the details of your electronic ticket. Note: all timings are local.</p>
    </div>

    {{-- ── PASSENGER INFO ───────────────────────────── --}}
    <div class="section-header">PASSENGER AND TICKET INFORMATION</div>
    
    <table class="passenger-table">
        <tr>
            <td class="label">PASSENGER NAME</td>
            <td class="value">{{ strtoupper($ticket['passenger_name']) }}</td>
            <td class="label">FREQUENT FLYER</td>
            <td class="value">EK217206592/BLUE</td>
        </tr>
        <tr>
            <td class="label">E-TICKET NUMBER</td>
            <td class="value">176 2143480036</td>
            <td class="label">BOOKING REFERENCE</td>
            <td class="value">{{ $ticket['booking_reference'] }}</td>
        </tr>
        <tr>
            <td class="label">ISSUED BY/DATE</td>
            <td class="value" colspan="3">
                AGT 86491845 AE<br>
                {{ date('dMY', strtotime($ticket['date'])) }} UNITED STATES /AMERICAN AIRLINES
            </td>
        </tr>
    </table>

    {{-- ── DEPARTURE SECTION ────────────────────────── --}}
    <div class="flight-section">
        <div class="section-title">DEPARTURE</div>
        <div class="section-header">TRAVEL INFORMATION</div>
        
        <table class="flight-table">
            <thead>
                <tr>
                    <th>FLIGHT</th>
                    <th>DEPART/ARRIVE</th>
                    <th>AIRPORT/TERMINAL</th>
                    <th>CHECK-IN OPENS</th>
                    <th>CLASS</th>
                    <th>COUPON VALIDITY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="flight-num">{{ $ticket['flight_number'] }}</div>
                        <div class="status">CONFIRMED</div>
                    </td>
                    <td>
                        {{ date('d M y', strtotime($ticket['date'])) }}<br>
                        {{ date('d M y', strtotime($ticket['date'])) }}
                    </td>
                    <td>
                        {{ $ticket['origin'] }} INTL<br>
                        TERMINAL 2<br><br>
                        {{ $ticket['destination'] }} INTL<br>
                        TERMINAL 2
                    </td>
                    <td>
                        {{ date('d M y', strtotime($ticket['date'])) }}<br>
                        12:05
                    </td>
                    <td>
                        {{ strtoupper($ticket['class']) }}<br>
                        SEAT<br>
                        BAGGAGE<br>
                        ALLOWANCE 2 PIECE
                    </td>
                    <td>
                        NOT BEFORE {{ date('d M y', strtotime($ticket['date'])) }}<br>
                        NOT AFTER {{ date('d M y', strtotime($ticket['date'])) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ── RETURN SECTION (if round trip) ───────────── --}}
    @if(!empty($ticket['return_date']))
    <div class="flight-section">
        <div class="section-title">RETURN</div>
        <div class="section-header">TRAVEL INFORMATION</div>
        
        <table class="flight-table">
            <thead>
                <tr>
                    <th>FLIGHT</th>
                    <th>DEPART/ARRIVE</th>
                    <th>AIRPORT/TERMINAL</th>
                    <th>CHECK-IN OPENS</th>
                    <th>CLASS</th>
                    <th>COUPON VALIDITY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="flight-num">{{ $ticket['flight_number'] }}</div>
                        <div class="status">CONFIRMED</div>
                    </td>
                    <td>
                        {{ date('d M y', strtotime($ticket['return_date'])) }}<br>
                        {{ date('d M y', strtotime($ticket['return_date'])) }}
                    </td>
                    <td>
                        {{ $ticket['destination'] }} INTL<br>
                        TERMINAL 2<br><br>
                        {{ $ticket['origin'] }} INTL<br>
                        TERMINAL 2
                    </td>
                    <td>
                        {{ date('d M y', strtotime($ticket['return_date'])) }}<br>
                        07:10
                    </td>
                    <td>
                        {{ strtoupper($ticket['class']) }}<br>
                        SEAT<br>
                        BAGGAGE<br>
                        ALLOWANCE 2 PIECE
                    </td>
                    <td>
                        NOT BEFORE {{ date('d M y', strtotime($ticket['return_date'])) }}<br>
                        NOT AFTER {{ date('d M y', strtotime($ticket['return_date'])) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

</body>
</html>
