# Public Tracking API - Documentation

## Overview

The application has been refactored to separate backend logic from public tracking:

- **Main App**: Handles authentication, shipment management, admin dashboard, flight tickets, coins, vendors
- **Public API**: Provides read-only tracking data for external tracking websites

---

## API Endpoint

### Get Tracking Information

```http
GET /api/public/track/{tracking_id}
```

**Rate Limit**: 30 requests per minute per IP

### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| tracking_id | string | Yes | Alphanumeric, 5-20 characters |

### Response Format

#### Success (200 OK)

```json
{
  "success": true,
  "data": {
    "tracking_id": "TRKKP9BV33PHR",
    "status": "in_transit",
    "progress": 40,
    "shipment_type": "air_freight",
    "sender": "Stanley",
    "receiver": "John",
    "origin": "Tokyo",
    "destination": "Texas",
    "shipped_at": "2026-03-28T06:02:00+00:00",
    "estimated_delivery": "2026-04-04T06:02:00+00:00",
    "courier": "DHL",
    "timeline": [
      {
        "status": "in_transit",
        "location": "Ethiopia",
        "message": "In transit",
        "progress": 40,
        "timestamp": "2026-03-30T06:03:00+00:00"
      }
    ]
  }
}
```

#### Error Responses

**Invalid Tracking ID (400 Bad Request)**
```json
{
  "success": false,
  "error": "Invalid tracking ID format"
}
```

**Not Found (404 Not Found)**
```json
{
  "success": false,
  "error": "Tracking number not found"
}
```

**Rate Limited (429 Too Many Requests)**
```json
{
  "message": "Too many attempts. Please try again later."
}
```

---

## Data Structure

### Timeline Item

| Field | Type | Description |
|-------|------|-------------|
| status | string | Shipment status (pending, in_transit, out_for_delivery, delivered, cancelled) |
| location | string\|null | Current location |
| message | string\|null | Additional notes |
| progress | integer | Progress percentage (0-100) |
| timestamp | string | ISO 8601 datetime |

### Status Values

- `pending` - Shipment created, awaiting pickup
- `in_transit` - Shipment is on the move
- `out_for_delivery` - Out for final delivery
- `delivered` - Successfully delivered
- `cancelled` - Shipment cancelled

---

## Security Features

1. **Rate Limiting**: 30 requests per minute per IP
2. **Tracking ID Validation**: Alphanumeric only, 5-20 characters
3. **Data Sanitization**: 
   - No internal database IDs exposed
   - No user passwords or sensitive data
   - Location/notes truncated to prevent data leakage
   - HTML tags stripped from all text fields
4. **Generic Error Messages**: Prevents tracking ID enumeration attacks

---

## Integration Example

### cURL

```bash
curl https://yourmainapp.com/api/public/track/TRKKP9BV33PHR
```

### JavaScript/Fetch

```javascript
const response = await fetch('https://yourmainapp.com/api/public/track/TRKKP9BV33PHR');
const data = await response.json();

if (data.success) {
  console.log('Status:', data.data.status);
  console.log('Progress:', data.data.progress + '%');
  console.log('Timeline:', data.data.timeline);
}
```

### PHP/Guzzle

```php
use GuzzleHttp\Client;

$client = new Client();
$response = $client->get('https://yourmainapp.com/api/public/track/TRKKP9BV33PHR');
$data = json_decode($response->getBody(), true);

if ($data['success']) {
    echo 'Status: ' . $data['data']['status'];
}
```

---

## Existing Functionality (Unchanged)

All existing features remain intact:

### Web Routes
- `/track` - Public tracking form (web interface)
- `/dashboard` - User dashboard (auth required)
- `/shipments/*` - Shipment management (auth required)
- `/flights/*` - Flight tickets (auth required)
- `/coins/*` - Coin system (auth required)
- `/vendor/*` - Vendor portal (auth required)
- `/admin/*` - Admin dashboard (auth required)

### Features Preserved
- User authentication (Breeze)
- Shipment creation and updates
- Tracking update logging
- Flight ticket generation
- Coin system (purchases, transactions)
- Vendor management
- Admin dashboard
- PDF generation

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        MAIN APP                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Web Auth   │  │   Dashboard  │  │  Admin Panel │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  Shipments   │  │Flight Tickets│  │  Coin System │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │         Public API: /api/public/track/{id}           │  │
│  │  • Rate limited • Sanitized data • No auth required  │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                   EXTERNAL TRACKING SITE                    │
│              (Different domain/hosting)                     │
│                     • No database                          │
│                     • Calls API only                       │
│                     • Read-only access                     │
└─────────────────────────────────────────────────────────────┘
```

---

## Rate Limiting Configuration

Rate limits are defined in `app/Providers/AppServiceProvider.php`:

```php
// Web tracking form
RateLimiter::for('tracking', function (Request $request) {
    return Limit::perMinute(10)->by($request->ip());
});

// Public API
RateLimiter::for('public-tracking', function (Request $request) {
    return Limit::perMinute(30)->by($request->ip());
});
```

---

## Files Changed/Created

### Modified Files
- `bootstrap/app.php` - Added API routes support
- `app/Providers/AppServiceProvider.php` - Added public-tracking rate limiter

### New Files
- `routes/api.php` - API route definitions
- `app/Http/Controllers/Api/PublicTrackingController.php` - API controller

---

## Testing

### Test API Endpoint

```bash
# Valid tracking ID
curl http://localhost/shipping/public/api/public/track/TRKKP9BV33PHR

# Invalid format (too short)
curl http://localhost/shipping/public/api/public/track/AB

# Not found
curl http://localhost/shipping/public/api/public/track/NONEXISTENT
```

### Verify Web Interface Still Works

Visit: `http://localhost/shipping/public/track`

---

## Deployment Notes

1. **Main App**: Deploy as usual. The API endpoint is automatically available.

2. **External Tracking Site**: 
   - No database needed
   - Only needs to call the API endpoint
   - Can be built with any technology (Laravel, React, vanilla HTML/JS, etc.)

3. **CORS** (if needed):
   If the tracking site is on a different domain, you may need to enable CORS:
   ```php
   // In config/cors.php or middleware
   'paths' => ['api/public/*'],
   'allowed_origins' => ['https://yourtrackingsite.com'],
   ```

---

## Support

For questions or issues with the API, check:
1. Rate limit headers in response
2. Tracking ID format (alphanumeric, 5-20 chars)
3. API base URL is correct
