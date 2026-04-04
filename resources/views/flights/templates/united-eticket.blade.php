<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>United Airlines - {{ $ticket['booking_reference'] }}</title>
    <style>
        @page {
            margin: 28pt 100pt;
            size: A4;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #fff;
            color: #000;
            font-size: 9pt;
            line-height: 1.35;
            padding: 28pt 30pt;  /* Browser preview margins */
            max-width: 800pt;
            margin: 0 auto;
        }

        /* ─── HEADER ─────────────────────────────────── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10pt;
            margin-bottom: 0;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .united-wordmark {
            font-family: Arial Black, Arial, sans-serif;
            font-size: 28pt;
            font-weight: 900;
            color: #003087;
            letter-spacing: -0.5pt;
            line-height: 1;
        }

        /* Inline globe SVG sits right next to the wordmark */
        .globe-wrap {
            width: 34pt;
            height: 34pt;
            margin-left: 5pt;
            flex-shrink: 0;
        }

        .pipe {
            color: #aaa;
            font-size: 18pt;
            margin: 0 10pt;
            line-height: 1;
            font-weight: 100;
        }

        .star-alliance {
            font-family: Arial, sans-serif;
            font-size: 7pt;
            color: #555;
            letter-spacing: 1.2pt;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 4pt;
        }
        .star-alliance .star { font-size: 10pt; color: #555; }

        .confirmation-box {
            text-align: right;
            font-family: Arial, sans-serif;
        }
        .confirmation-box .conf-label {
            font-size: 8.5pt;
            color: #000;
        }
        .confirmation-box .conf-code {
            font-size: 22pt;
            font-weight: 700;
            color: #000;
            letter-spacing: 1pt;
            line-height: 1.1;
        }

        /* ─── ISSUE DATE ─────────────────────────────── */
        .issue-date {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            margin-top: 10pt;
            margin-bottom: 14pt;
        }
        .issue-date strong { font-weight: 700; }

        /* ─── PASSENGER TABLE ────────────────────────── */
        .passenger-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2pt solid #000;
            border-bottom: 2pt solid #000;
            margin-bottom: 16pt;
        }
        .passenger-table th {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            font-weight: 700;
            text-align: left;
            padding: 5pt 6pt 3pt 0;
            border-bottom: 1pt solid #555;
        }
        .passenger-table td {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            padding: 4pt 6pt 7pt 0;
            vertical-align: top;
        }

        /* ─── FLIGHT INFO ─────────────────────────────── */
        .section-title {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            font-weight: 700;
            margin-bottom: 3pt;
        }

        .flight-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2pt solid #000;
        }
        .flight-table thead tr {
            border-bottom: 1pt solid #555;
        }
        .flight-table th {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            font-weight: 700;
            text-align: left;
            padding: 4pt 4pt 3pt 0;
            color: #000;
        }
        .flight-table th:first-child,
        .flight-table td:first-child { padding-left: 0; }
        .flight-table th:last-child,
        .flight-table td:last-child  { text-align: right; }

        .flight-table tbody tr {
            border-bottom: 1pt solid #ddd;
        }
        .flight-table tbody tr:nth-child(even) {
            background: #f2f2f2;
        }
        .flight-table td {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            padding: 6pt 4pt 6pt 0;
            vertical-align: top;
        }

        /* ─── OPERATED BY ─────────────────────────────── */
        .operated-by {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            font-style: italic;
            margin-top: 7pt;
            margin-bottom: 8pt;
        }

        /* ─── GRAY BAR (barcode area) ─────────────────── */
        .gray-bar {
            height: 32pt;
            background: #dedede;
            margin: 8pt 0 14pt 0;
        }

        /* ─── FARE SECTION ────────────────────────────── */
        .fare-section {
            border-top: 2pt solid #000;
            padding-top: 7pt;
        }
        .fare-title {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            font-weight: 700;
            margin-bottom: 8pt;
            text-transform: uppercase;
        }
        .fare-columns {
            display: flex;
            gap: 20pt;
        }
        .fare-left  { flex: 0 0 230pt; }
        .fare-right { flex: 1; display: flex; gap: 16pt; }
        .fare-right-col { flex: 1; }

        .fare-table {
            width: 100%;
            border-collapse: collapse;
        }
        .fare-table td {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            padding: 2pt 0;
            vertical-align: top;
        }
        .fare-table .val { text-align: right; }
        .fare-table .total-row td {
            font-weight: 700;
            padding-top: 6pt;
        }
        .fare-table .total-row { border-top: 1pt solid #000; }

        .fare-sub-heading {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            font-weight: 700;
            margin-bottom: 2pt;
        }
        .fare-sub-value {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            margin-bottom: 10pt;
        }

        /* ─── TOTAL LINE ──────────────────────────────── */
        .total-line {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            margin-top: 10pt;
            margin-bottom: 18pt;
        }

        /* ─── AWARD RULES FOOTER ──────────────────────── */
        .award-rules {
            border-top: 1pt solid #ccc;
            padding-top: 10pt;
            display: flex;
            gap: 10pt;
        }
        .award-rules .aw-label {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            font-weight: 700;
            white-space: nowrap;
        }
        .award-rules .aw-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8.5pt;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ───────────────────────────────────── --}}
    <div class="header">
        <div class="logo-area">
            {{-- United wordmark + globe --}}
            <img src="{{ $ticket['logo'] }}" 
     alt="United Airlines" 
     style="height: 30pt; width: auto;">
            <div class="star-alliance">
                A STAR ALLIANCE MEMBER
            </div>
        </div>

        <div class="confirmation-box">
            <div class="conf-label">Confirmation:</div>
            <div class="conf-code">{{ $ticket['booking_reference'] }}</div>
        </div>
    </div>

    {{-- ── ISSUE DATE ───────────────────────────────── --}}
    <div class="issue-date">
        <strong>Issue Date:</strong> {{ date('l, F jS, Y') }}
    </div>

    {{-- ── PASSENGER TABLE ──────────────────────────── --}}
    <table class="passenger-table">
        <thead>
            <tr>
                <th style="width:22%;">Traveler</th>
                <th style="width:28%;">Ticket Number</th>
                <th style="width:28%;">Frequent Flyer</th>
                <th style="width:22%;">Seats</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ strtoupper($ticket['passenger_name']) }}</td>
                <td>{{ $ticket['ticket_number'] }}</td>
                <td>UA-XXXXXX{{ rand(100, 999) }}</td>
                <td>{{ $ticket['seat'] }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ── FLIGHT INFORMATION ───────────────────────── --}}
    <div class="section-title">FLIGHT INFORMATION</div>
    <table class="flight-table">
        <thead>
            <tr>
                <th style="width:13%;">Day, Date</th>
                <th style="width:12%;">Flight Class</th>
                <th style="width:28%;">Departure City and Time</th>
                <th style="width:28%;">Arrival City and Time</th>
                <th style="width:19%;">Aircraft Meal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ date('D, M d', strtotime($ticket['date'])) }}</td>
                <td>{{ ucfirst(strtolower($ticket['class'])) }}</td>
                <td>
                    {{ $ticket['origin'] }}<br>
                    {{ date('g:ia', strtotime($ticket['departure_time'])) }}
                </td>
                <td>
                    {{ $ticket['destination'] }}<br>
                    {{ date('g:ia', strtotime($ticket['arrival_time'])) }}
                </td>
                <td>{{ $ticket['flight_number'] }}</td>
            </tr>
        </tbody>
    </table>

    @if(!empty($ticket['operated_by']))
    <div class="operated-by">Operated by {{ $ticket['operated_by'] }}</div>
    @endif

    {{-- ── GRAY BAR ─────────────────────────────────── --}}
    <div class="gray-bar"></div>

    {{-- ── FARE INFORMATION ────────────────────────── --}}
    <div class="fare-section">
        <div class="fare-title">FARE INFORMATION</div>
        <div class="fare-columns">

            {{-- Left: Fare Breakdown --}}
            <div class="fare-left">
                <table class="fare-table">
                    <thead>
                        <tr><td colspan="2" style="font-weight:700;padding-bottom:5pt;">Fare Breakdown</td></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Airfare:</td>
                            <td class="val">{{ number_format($ticket['price'] * 0.75, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Departure Tax:</td>
                            <td class="val">{{ number_format($ticket['price'] * 0.05, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Pax Terminal Facilities Charge</td>
                            <td class="val">{{ number_format($ticket['price'] * 0.03, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Security Charge:</td>
                            <td class="val">{{ number_format($ticket['price'] * 0.02, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Passenger Service Charge:</td>
                            <td class="val">{{ number_format($ticket['price'] * 0.08, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Per Person Total:</td>
                            <td class="val">{{ number_format($ticket['price'] * 0.98, 2) }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>Ticket Total:</td>
                            <td class="val">{{ number_format($ticket['price'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Right: MileagePlus + Payment --}}
            <div class="fare-right">
                <div class="fare-right-col">
                    <div class="fare-sub-heading">MileagePlus Account Debited:</div>
                    <div class="fare-sub-value">UA{{ rand(100000, 999999) }}</div>

                    <div class="fare-sub-heading">Form of Payment:</div>
                    <div class="fare-sub-value">
                        VISA<br>
                        Last Four Digits {{ rand(1000, 9999) }}
                    </div>
                </div>
                <div class="fare-right-col">
                    <div class="fare-sub-heading">MileagePlus Miles Debited/<br>Award Used:</div>
                    <div class="fare-sub-value">{{ number_format(rand(20000, 60000)) }}/{{ ['WC77K','SAVER','AWARD'][rand(0,2)] }}</div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── TOTAL LINE ───────────────────────────────── --}}
    <div class="total-line">
        The airfare you paid on this itinerary totals: {{ number_format($ticket['price'], 2) }} USD
    </div>

    {{-- ── AWARD RULES ──────────────────────────────── --}}
    <div class="award-rules">
        <div class="aw-label">Award Rules:</div>
        <div class="aw-text">
            Additional charges may apply for changes in addition to any fare rules listed.<br>
            {{ ['RWD WC77K/NONEND/-TRAN:VALID UA/A3/CA','RWD SAVER/NONEND/VALID UA','RWD AWARD/VALID UA'][rand(0,2)] }}<br>
            All changes must be made prior to the departure date, or the ticket has no value.
        </div>
    </div>

</body>
</html>