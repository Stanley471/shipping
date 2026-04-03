<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>United Airlines - {{ $ticket['booking_reference'] }}</title>
    <style>
        @page { margin: 0; size: 595.28pt 283.47pt; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #002244;
            width: 595.28pt; height: 283.47pt; position: relative; overflow: hidden;
        }
        .boarding-pass { width: 100%; height: 100%; display: flex; }
        .left-section {
            width: 65%; background: white; padding: 0; display: flex; flex-direction: column;
        }
        .united-header {
            background: #002244; color: white; padding: 12pt 20pt;
            display: flex; justify-content: space-between; align-items: center;
        }
        .united-logo { font-size: 18pt; font-weight: bold; letter-spacing: 2pt; }
        .star-alliance { font-size: 8pt; opacity: 0.8; }
        .main-content { padding: 15pt 20pt; flex: 1; display: flex; flex-direction: column; }
        .route-display {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 15pt;
        }
        .airport-box { text-align: center; }
        .airport-code { font-size: 42pt; font-weight: bold; color: #002244; line-height: 1; }
        .airport-name { font-size: 9pt; color: #666; margin-top: 4pt; }
        .flight-arrow {
            flex: 1; text-align: center; padding: 0 20pt;
        }
        .flight-number-display {
            background: #FFB81C; color: #002244; padding: 4pt 12pt;
            border-radius: 15pt; font-weight: bold; font-size: 11pt;
            display: inline-block; margin-bottom: 8pt;
        }
        .plane-icon-united { font-size: 20pt; color: #002244; }
        .passenger-row {
            display: flex; gap: 30pt; margin-bottom: 12pt;
        }
        .data-box { flex: 1; }
        .data-label { font-size: 7pt; color: #666; text-transform: uppercase; margin-bottom: 3pt; }
        .data-value { font-size: 14pt; font-weight: bold; color: #002244; }
        .passenger-name { font-size: 18pt; text-transform: uppercase; }
        .info-row-united {
            display: flex; gap: 20pt; margin-top: auto;
            border-top: 1pt solid #eee; padding-top: 12pt;
        }
        .info-box { text-align: center; min-width: 60pt; }
        .info-box-label { font-size: 7pt; color: #666; text-transform: uppercase; }
        .info-box-value { font-size: 16pt; font-weight: bold; color: #002244; }
        .right-section {
            width: 35%; background: #f5f5f5; border-left: 2pt dashed #ccc;
            padding: 15pt; display: flex; flex-direction: column;
        }
        .qr-section { text-align: center; margin-bottom: 15pt; }
        .qr-placeholder {
            width: 80pt; height: 80pt; background: white;
            margin: 0 auto 8pt; display: flex; align-items: center; justify-content: center;
            border: 1pt solid #ddd;
        }
        .priority-tag {
            background: {{ $ticket['class'] == 'ECONOMY' ? '#666' : '#FFB81C' }};
            color: {{ $ticket['class'] == 'ECONOMY' ? 'white' : '#002244' }};
            padding: 4pt 12pt; border-radius: 3pt;
            font-size: 9pt; font-weight: bold; text-transform: uppercase;
            display: inline-block; margin-bottom: 10pt;
        }
        .booking-ref-box {
            background: #002244; color: white; padding: 10pt;
            text-align: center; border-radius: 4pt; margin-top: auto;
        }
        .booking-ref-label { font-size: 7pt; text-transform: uppercase; opacity: 0.8; }
        .booking-ref-value { font-size: 16pt; font-weight: bold; letter-spacing: 2pt; }
        .seq-number { font-size: 7pt; color: #666; margin-top: 8pt; text-align: center; }
    </style>
</head>
<body>
    <div class="boarding-pass">
        <div class="left-section">
            <div class="united-header">
                <div class="united-logo">UNITED</div>
                <div class="star-alliance">Star Alliance Member</div>
            </div>
            <div class="main-content">
                <div class="route-display">
                    <div class="airport-box">
                        <div class="airport-code">{{ $ticket['origin'] }}</div>
                        <div class="airport-name">{{ date('g:i A', strtotime($ticket['departure_time'])) }}</div>
                    </div>
                    <div class="flight-arrow">
                        <div class="flight-number-display">{{ $ticket['flight_number'] }}</div>
                        <div class="plane-icon-united">✈</div>
                    </div>
                    <div class="airport-box">
                        <div class="airport-code">{{ $ticket['destination'] }}</div>
                        <div class="airport-name">{{ date('g:i A', strtotime($ticket['arrival_time'])) }}</div>
                    </div>
                </div>
                <div class="passenger-row">
                    <div class="data-box" style="flex: 2;">
                        <div class="data-label">Passenger Name</div>
                        <div class="data-value passenger-name">{{ $ticket['passenger_name'] }}</div>
                    </div>
                    <div class="data-box">
                        <div class="data-label">Date</div>
                        <div class="data-value">{{ date('M d, Y', strtotime($ticket['date'])) }}</div>
                    </div>
                </div>
                <div class="info-row-united">
                    <div class="info-box">
                        <div class="info-box-label">Gate</div>
                        <div class="info-box-value">{{ explode('-', $ticket['gate'])[1] ?? $ticket['gate'] }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-label">Seat</div>
                        <div class="info-box-value">{{ $ticket['seat'] }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-label">Boarding Time</div>
                        <div class="info-box-value">{{ date('g:i', strtotime($ticket['boarding_time'])) }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-label">Class</div>
                        <div class="info-box-value" style="font-size: 12pt;">{{ $ticket['class'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-section">
            <div class="qr-section">
                <div class="qr-placeholder">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <rect x="5" y="5" width="20" height="20" fill="#002244"/>
                        <rect x="35" y="5" width="20" height="20" fill="#002244"/>
                        <rect x="5" y="35" width="20" height="20" fill="#002244"/>
                        <rect x="30" y="30" width="5" height="5" fill="#002244"/>
                        <rect x="40" y="35" width="5" height="5" fill="#002244"/>
                        <rect x="50" y="40" width="5" height="5" fill="#002244"/>
                        <rect x="35" y="50" width="10" height="5" fill="#002244"/>
                    </svg>
                </div>
                <div class="priority-tag">{{ $ticket['class'] == 'FIRST' ? 'Premier Access' : ($ticket['class'] == 'BUSINESS' ? 'Priority' : 'Economy') }}</div>
            </div>
            <div style="flex: 1;"></div>
            <div class="booking-ref-box">
                <div class="booking-ref-label">Confirmation #</div>
                <div class="booking-ref-value">{{ $ticket['booking_reference'] }}</div>
            </div>
            <div class="seq-number">Seq: {{ rand(1, 999) }}</div>
        </div>
    </div>
</body>
</html>