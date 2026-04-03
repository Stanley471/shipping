<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emirates - {{ $ticket['booking_reference'] }}</title>
    <style>
        @page { margin: 0; size: 595.28pt 283.47pt; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #D71A21;
            width: 595.28pt; height: 283.47pt; position: relative; overflow: hidden;
        }
        .boarding-pass { width: 100%; height: 100%; display: flex; }
        .emirates-header {
            width: 60pt; background: #D71A21; display: flex; flex-direction: column;
            align-items: center; padding: 15pt 5pt; color: white;
        }
        .emirates-logo { 
            writing-mode: vertical-rl; text-orientation: mixed;
            font-size: 14pt; font-weight: bold; letter-spacing: 3pt;
            transform: rotate(180deg);
        }
        .main-section {
            flex: 1; background: white; padding: 20pt;
            display: flex; flex-direction: column;
        }
        .top-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 15pt; padding-bottom: 10pt; border-bottom: 2pt solid #D71A21;
        }
        .flight-title {
            font-size: 24pt; font-weight: bold; color: #D71A21;
        }
        .flight-subtitle { font-size: 9pt; color: #666; }
        .passenger-box {
            background: #f5f5f5; padding: 8pt 15pt; border-radius: 4pt;
        }
        .passenger-label { font-size: 7pt; color: #666; text-transform: uppercase; }
        .passenger-value { font-size: 12pt; font-weight: bold; color: #333; }
        .route-section {
            display: flex; align-items: center; justify-content: center;
            gap: 30pt; margin: 15pt 0;
        }
        .airport-block { text-align: center; }
        .airport-code-ek { font-size: 48pt; font-weight: bold; color: #D71A21; line-height: 1; }
        .airport-city { font-size: 10pt; color: #666; margin-top: 5pt; }
        .airport-time { font-size: 14pt; font-weight: bold; color: #333; }
        .flight-info-center {
            display: flex; flex-direction: column; align-items: center;
        }
        .ek-flight-number {
            background: #D71A21; color: white; padding: 5pt 15pt;
            font-weight: bold; font-size: 11pt; margin-bottom: 8pt;
        }
        .arrow-ek { font-size: 24pt; color: #D71A21; }
        .details-grid-ek {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 15pt; margin-top: auto;
        }
        .detail-box-ek {
            text-align: center; padding: 10pt;
            border: 1pt solid #eee; border-radius: 4pt;
        }
        .detail-box-ek.highlight {
            background: #D71A21; color: white; border-color: #D71A21;
        }
        .detail-box-ek.highlight .detail-label-ek,
        .detail-box-ek.highlight .detail-value-ek {
            color: white;
        }
        .detail-label-ek { font-size: 7pt; color: #666; text-transform: uppercase; margin-bottom: 4pt; }
        .detail-value-ek { font-size: 18pt; font-weight: bold; color: #333; }
        .right-panel {
            width: 160pt; background: #f8f8f8;
            border-left: 2pt dashed #ccc; padding: 15pt;
            display: flex; flex-direction: column;
        }
        .barcode-ek { 
            height: 180pt; width: 40pt; margin: 0 auto 10pt;
            background: repeating-linear-gradient(
                0deg, #000 0pt, #000 2pt, #fff 2pt, #fff 4pt
            );
        }
        .qr-ek { 
            width: 60pt; height: 60pt; background: white;
            margin: 0 auto 10pt; display: flex; align-items: center; justify-content: center;
            border: 1pt solid #ddd;
        }
        .pnr-box {
            text-align: center; margin-top: auto;
            padding: 10pt; background: #D71A21; color: white; border-radius: 4pt;
        }
        .pnr-label { font-size: 7pt; text-transform: uppercase; opacity: 0.9; }
        .pnr-value { font-size: 14pt; font-weight: bold; letter-spacing: 2pt; }
    </style>
</head>
<body>
    <div class="boarding-pass">
        <div class="emirates-header">
            <div class="emirates-logo">EMIRATES</div>
        </div>
        <div class="main-section">
            <div class="top-row">
                <div>
                    <div class="flight-title">Boarding Pass</div>
                    <div class="flight-subtitle">{{ $ticket['class'] }} Class</div>
                </div>
                <div class="passenger-box">
                    <div class="passenger-label">Passenger</div>
                    <div class="passenger-value">{{ $ticket['passenger_name'] }}</div>
                </div>
            </div>
            <div class="route-section">
                <div class="airport-block">
                    <div class="airport-code-ek">{{ $ticket['origin'] }}</div>
                    <div class="airport-city">{{ $ticket['origin'] }}</div>
                    <div class="airport-time">{{ date('H:i', strtotime($ticket['departure_time'])) }}</div>
                </div>
                <div class="flight-info-center">
                    <div class="ek-flight-number">{{ $ticket['flight_number'] }}</div>
                    <div class="arrow-ek">→</div>
                </div>
                <div class="airport-block">
                    <div class="airport-code-ek">{{ $ticket['destination'] }}</div>
                    <div class="airport-city">{{ $ticket['destination'] }}</div>
                    <div class="airport-time">{{ date('H:i', strtotime($ticket['arrival_time'])) }}</div>
                </div>
            </div>
            <div class="details-grid-ek">
                <div class="detail-box-ek highlight">
                    <div class="detail-label-ek">Date</div>
                    <div class="detail-value-ek" style="font-size: 12pt;">{{ date('d M', strtotime($ticket['date'])) }}</div>
                </div>
                <div class="detail-box-ek">
                    <div class="detail-label-ek">Gate</div>
                    <div class="detail-value-ek">{{ explode('-', $ticket['gate'])[1] ?? $ticket['gate'] }}</div>
                </div>
                <div class="detail-box-ek">
                    <div class="detail-label-ek">Seat</div>
                    <div class="detail-value-ek">{{ $ticket['seat'] }}</div>
                </div>
                <div class="detail-box-ek">
                    <div class="detail-label-ek">Seq</div>
                    <div class="detail-value-ek">{{ rand(1, 999) }}</div>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <div class="barcode-ek"></div>
            <div class="qr-ek">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <rect x="5" y="5" width="15" height="15" fill="#D71A21"/>
                    <rect x="30" y="5" width="15" height="15" fill="#D71A21"/>
                    <rect x="5" y="30" width="15" height="15" fill="#D71A21"/>
                    <rect x="25" y="25" width="5" height="5" fill="#D71A21"/>
                    <rect x="35" y="35" width="5" height="5" fill="#D71A21"/>
                </svg>
            </div>
            <div class="pnr-box">
                <div class="pnr-label">PNR</div>
                <div class="pnr-value">{{ $ticket['booking_reference'] }}</div>
            </div>
        </div>
    </div>
</body>
</html>