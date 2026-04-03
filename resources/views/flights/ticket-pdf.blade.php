<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boarding Pass - {{ $ticket['booking_reference'] }}</title>
    <style>
        @page {
            margin: 0;
            size: 595.28pt 283.47pt; /* A5 landscape - standard boarding pass size */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            width: 595.28pt;
            height: 283.47pt;
            position: relative;
            overflow: hidden;
        }
        
        .boarding-pass {
            width: 100%;
            height: 100%;
            display: flex;
            background: white;
        }
        
        /* Left Section - Main Info */
        .main-section {
            flex: 1;
            padding: 20pt 25pt;
            background: white;
            position: relative;
        }
        
        .airline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15pt;
            padding-bottom: 10pt;
            border-bottom: 2pt solid #e5e7eb;
        }
        
        .airline-name {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a5f;
        }
        
        .boarding-pass-title {
            font-size: 10pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2pt;
        }
        
        .flight-route {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20pt;
        }
        
        .airport-code {
            font-size: 36pt;
            font-weight: bold;
            color: #1e3a5f;
        }
        
        .airport-info {
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }
        
        .route-arrow {
            flex: 1;
            text-align: center;
            padding: 0 15pt;
        }
        
        .route-line {
            border-top: 2pt dashed #cbd5e1;
            position: relative;
            margin-top: 5pt;
        }
        
        .plane-icon {
            position: absolute;
            top: -8pt;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12pt;
        }
        
        .passenger-info {
            margin-bottom: 15pt;
        }
        
        .info-row {
            display: flex;
            gap: 30pt;
        }
        
        .info-item {
            flex: 1;
        }
        
        .info-label {
            font-size: 7pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1pt;
            margin-bottom: 3pt;
        }
        
        .info-value {
            font-size: 12pt;
            font-weight: bold;
            color: #1e3a5f;
        }
        
        .passenger-name {
            font-size: 16pt !important;
        }
        
        /* Right Section - Details & Barcode */
        .details-section {
            width: 200pt;
            background: #f8fafc;
            border-left: 2pt dashed #cbd5e1;
            padding: 20pt;
            display: flex;
            flex-direction: column;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12pt;
            margin-bottom: 15pt;
        }
        
        .detail-item {
            text-align: center;
        }
        
        .detail-label {
            font-size: 7pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1pt;
        }
        
        .detail-value {
            font-size: 14pt;
            font-weight: bold;
            color: #1e3a5f;
        }
        
        .class-badge {
            display: inline-block;
            padding: 3pt 10pt;
            background: #10b981;
            color: white;
            border-radius: 3pt;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .barcode-section {
            margin-top: auto;
            text-align: center;
        }
        
        .barcode {
            height: 50pt;
            background: repeating-linear-gradient(
                90deg,
                #000 0pt,
                #000 2pt,
                #fff 2pt,
                #fff 4pt,
                #000 4pt,
                #000 5pt,
                #fff 5pt,
                #fff 7pt,
                #000 7pt,
                #000 9pt,
                #fff 9pt,
                #fff 10pt,
                #000 10pt,
                #000 11pt,
                #fff 11pt,
                #fff 13pt,
                #000 13pt,
                #000 14pt,
                #fff 14pt,
                #fff 16pt,
                #000 16pt,
                #000 17pt,
                #fff 17pt,
                #fff 19pt,
                #000 19pt,
                #000 21pt,
                #fff 21pt,
                #fff 22pt
            );
            margin-bottom: 5pt;
        }
        
        .barcode-text {
            font-size: 10pt;
            font-family: monospace;
            letter-spacing: 3pt;
        }
        
        .qr-code {
            width: 60pt;
            height: 60pt;
            margin: 0 auto 5pt;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #6b7280;
        }
        
        .booking-ref {
            text-align: center;
            padding: 8pt;
            background: #1e3a5f;
            color: white;
            border-radius: 5pt;
            margin-top: 10pt;
        }
        
        .booking-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 1pt;
            opacity: 0.8;
        }
        
        .booking-value {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2pt;
        }
        
        .watermark {
            position: absolute;
            bottom: 10pt;
            right: 210pt;
            font-size: 20pt;
            color: rgba(16, 185, 129, 0.1);
            font-weight: bold;
            transform: rotate(-15deg);
        }
        
        .disclaimer {
            position: absolute;
            bottom: 5pt;
            left: 25pt;
            font-size: 6pt;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="boarding-pass">
        <!-- Main Section -->
        <div class="main-section">
            <div class="airline-header">
                <div class="airline-name">{{ $ticket['airline'] }}</div>
                <div class="boarding-pass-title">Boarding Pass</div>
            </div>
            
            <div class="flight-route">
                <div>
                    <div class="airport-code">{{ $ticket['origin'] }}</div>
                    <div class="airport-info">{{ date('g:i A', strtotime($ticket['departure_time'])) }}</div>
                </div>
                <div class="route-arrow">
                    <div style="font-size: 8pt; color: #6b7280;">{{ $ticket['flight_number'] }}</div>
                    <div class="route-line">
                        <div class="plane-icon">✈</div>
                    </div>
                </div>
                <div>
                    <div class="airport-code">{{ $ticket['destination'] }}</div>
                    <div class="airport-info">{{ date('g:i A', strtotime($ticket['arrival_time'])) }}</div>
                </div>
            </div>
            
            <div class="passenger-info">
                <div class="info-row">
                    <div class="info-item">
                        <div class="info-label">Passenger</div>
                        <div class="info-value passenger-name">{{ $ticket['passenger_name'] }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date</div>
                        <div class="info-value">{{ date('M d, Y', strtotime($ticket['date'])) }}</div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Class</div>
                    <div class="info-value">
                        <span class="class-badge">{{ $ticket['class'] }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Price</div>
                    <div class="info-value">${{ number_format($ticket['price'] ?? 0, 2) }}</div>
                </div>
            </div>
            
            <div class="disclaimer">This is a novelty/prank ticket for entertainment purposes only. Not valid for travel.</div>
        </div>
        
        <!-- Details Section -->
        <div class="details-section">
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Gate</div>
                    <div class="detail-value">{{ $ticket['gate'] }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Seat</div>
                    <div class="detail-value">{{ $ticket['seat'] }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Boarding</div>
                    <div class="detail-value">{{ date('g:i', strtotime($ticket['boarding_time'])) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Terminal</div>
                    <div class="detail-value">{{ $ticket['gate'] }}</div>
                </div>
            </div>
            
            <div class="barcode-section">
                <div class="barcode"></div>
                <div class="barcode-text">{{ substr($ticket['barcode'], 0, 16) }}</div>
                
                <div class="booking-ref">
                    <div class="booking-label">Booking Reference</div>
                    <div class="booking-value">{{ $ticket['booking_reference'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="watermark">NOVELTY</div>
</body>
</html>