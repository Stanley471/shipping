# Ctools - Developer Handoff Documentation

> **Version:** 1.0  
> **Last Updated:** April 13, 2026  
> **Framework:** Laravel 12.x  
> **PHP:** 8.2+

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Architecture Overview](#architecture-overview)
3. [Directory Structure](#directory-structure)
4. [Core Features](#core-features)
5. [Database Schema](#database-schema)
6. [Configuration Guide](#configuration-guide)
7. [Code Patterns](#code-patterns)
8. [API Documentation](#api-documentation)
9. [Deployment](#deployment)
10. [Troubleshooting](#troubleshooting)

---

## Project Overview

**Ctools** is a comprehensive cargo/logistics management platform with integrated tools including:

- **Shipment Management** - Create, track, and manage cargo shipments
- **Coin System** - Virtual currency for paying for services (P2P purchases)
- **Referral Program** - Two-tier referral system with withdrawable earnings
- **Flight Tickets** - United Airlines flight booking with e-ticket generation
- **Vendor Portal** - P2P coin sellers (admin-approved vendors)
- **Public Tracking API** - External tracking for third-party websites

### Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | Laravel 12.x |
| Frontend | Blade + Tailwind CSS |
| Database | MySQL |
| Queue | Database (configurable) |
| Mail | SMTP (configurable) |
| Auth | Laravel Breeze (customized) |

---

## Architecture Overview

### Request Flow

```
Request → Route → Middleware → Controller → Service → Model → DB
                    ↓
              View (Blade) ← Helper/Component
```

### Key Architectural Decisions

1. **Service Layer Pattern** - Business logic in Services (`app/Services/`)
2. **Repository Pattern** - Complex queries in model scopes
3. **Form Request Validation** - Validation in dedicated Request classes
4. **Mail Helper** - Unified email sending (sync/queue configurable)
5. **Policy-based Authorization** - Access control via Policies

---

## Directory Structure

```
c:\xampp\htdocs\shipping/
├── app/
│   ├── Helpers/              # Helper classes (MailHelper)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Admin-only controllers
│   │   │   ├── Api/          # API controllers
│   │   │   └── Auth/         # Authentication (Breeze)
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Mail/                 # Mailable classes
│   ├── Models/               # Eloquent models
│   ├── Policies/             # Authorization policies
│   ├── Rules/                # Custom validation rules
│   ├── Services/             # Business logic services
│   └── View/                 # View components
├── bootstrap/
├── config/
├── database/
│   ├── migrations/           # All database migrations
│   └── seeders/              # Database seeders
├── public/                   # Web root
│   └── build/                # Vite compiled assets
├── resources/
│   ├── css/                  # Tailwind + custom CSS
│   ├── js/                   # Alpine.js + app.js
│   └── views/                # Blade templates
│       ├── admin/            # Admin panel views
│       ├── auth/             # Authentication views
│       ├── coins/            # Coin system views
│       ├── components/       # Reusable components
│       ├── emails/           # Email templates
│       ├── flights/          # Flight booking views
│       ├── layouts/          # App layouts
│       ├── referrals/        # Referral system views
│       ├── shipments/        # Shipment views
│       ├── tools/            # Coming soon tools
│       └── vendor/           # Vendor portal views
├── routes/
│   ├── web.php               # Web routes
│   ├── api.php               # API routes
│   └── auth.php              # Auth routes
├── storage/
├── tests/
├── tracking-app-php/         # External tracking app (pure PHP)
│   ├── index.php
│   ├── result.php
│   ├── functions.php
│   └── config.php
└── .env                      # Environment config
```

---

## Core Features

### 1. Shipment Management

**Location:** `app/Http/Controllers/ShipmentController.php`

**Key Models:**
- `Shipment` - Main shipment data
- `TrackingUpdate` - Status updates

**Flow:**
1. User creates shipment (costs coins)
2. Tracking ID auto-generated
3. Vendor/admin adds tracking updates
4. Receiver can track via public page

**Chat Widget per Shipment:**
- Vendors can add WhatsApp/SmartSupp widget code
- Displayed on tracking result page
- Validated for security

**Code Example:**
```php
// Creating shipment
$shipment = Shipment::create([
    'tracking_id' => TrackingIdGenerator::generate(),
    'chat_provider' => 'whatsapp', // or 'smartsupp'
    'chat_widget_code' => $validated['chat_widget_code'],
    // ... other fields
]);
```

### 2. Coin System (P2P)

**Location:** `app/Services/CoinService.php`

**Concept:** Virtual currency for paying platform services

**How it works:**
1. User buys coins via P2P (pays vendor → uploads proof → admin/vendor approves → coins credited)
2. User spends coins on shipments, flight tickets
3. Vendor earns real money, user gets coins

**Key Tables:**
- `coins` - User coin balances
- `coin_transactions` - Coin transaction log
- `coin_purchases` - Pending/completed purchases
- `admin_bank_accounts` - Vendor bank details for P2P

**Service Costs:**
```php
// Configured in services table
'create_shipment' => 50 coins
'flight_ticket' => 100 coins
'edit_shipment' => 0 coins
'update_shipment' => 0 coins
```

### 3. Referral System (Two-Tier)

**Location:** `app/Services/ReferralService.php`

**Architecture:**
- **Tier 1:** Normal coins (spendable on platform)
- **Tier 2:** Referral coins (earned from referrals, convertible or withdrawable)

**Earning Methods:**
1. **Signup Bonus** - When referred user registers
2. **Purchase Commission** - % of referred user's coin purchases

**Withdrawal Flow:**
1. User requests withdrawal (bank details required)
2. Admin reviews in Admin → Referral Program → Withdrawals
3. Admin approves → Status: completed
4. Admin rejects → Status: rejected + reason → Coins refunded

**Key Tables:**
- `referral_coins` - Referral coin balances
- `referral_transactions` - All referral transactions
- `referral_settings` - Global settings

### 4. Flight Tickets (United Airlines Only)

**Location:** `app/Http/Controllers/FlightController.php`

**Override System:**
All flights display as United Airlines regardless of source API.

**Templates:**
- `resources/views/flights/templates/united-eticket.blade.php`

**PDF Generation:**
```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('flights.templates.united-eticket', compact('ticket'));
return $pdf->download('ticket.pdf');
```

### 5. Public Tracking API

**Location:** `app/Http/Controllers/Api/PublicTrackingController.php`

**Endpoint:** `GET /api/public/track/{tracking_id}`

**Security:**
- Rate limited (30 req/min)
- Only alphanumeric tracking IDs (5-20 chars)
- Sanitized output (no internal IDs)

**Response Format:**
```json
{
  "success": true,
  "data": {
    "tracking_id": "ABC123",
    "status": "in_transit",
    "progress": 75,
    "sender": "John Doe",
    "receiver": "Jane Doe",
    "timeline": [...],
    "chat": {
      "provider": "whatsapp",
      "widget_code": "..."
    }
  }
}
```

**External Tracking App:**
Pure PHP app in `tracking-app-php/` folder:
- Consumes the API
- Self-contained (no Laravel dependencies)
- Can be deployed separately

---

## Database Schema

### Core Tables

#### users
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| name | string | |
| email | string | Unique |
| password | string | Hashed |
| role | string | 'user', 'admin' |
| is_vendor | boolean | P2P seller access |
| is_active | boolean | Account status |
| referral_code | string | Unique, auto-generated |
| referred_by | bigint | FK to users |
| total_referrals | int | Cached count |
| total_referral_earnings | decimal | Cached total |

#### shipments
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK |
| tracking_id | string | Unique, indexed |
| sender_name | string | |
| receiver_name | string | |
| receiver_email | string | Nullable |
| pickup_location | text | |
| delivery_address | text | |
| shipment_type | enum | air/sea/road/express |
| status | string | From latest update |
| chat_provider | enum | 'whatsapp', 'smartsupp' |
| chat_widget_code | text | Nullable |
| shipped_at | datetime | |
| eta | datetime | Estimated delivery |
| softDeletes | | |

#### coins
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK |
| balance | int | Current balance |
| total_earned | int | Lifetime earned |
| total_spent | int | Lifetime spent |

#### coin_transactions
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK |
| type | string | 'deposit', 'purchase', 'refund' |
| amount | int | + or - |
| balance_after | int | |
| description | string | |
| reference_id | string | For tracking |

#### coin_purchases
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK |
| amount_naira | decimal | NGN amount |
| amount_coins | int | Coins to receive |
| status | string | 'pending', 'approved', 'rejected' |
| proof_image | string | Path to receipt |
| bank_name | string | Vendor's bank |
| account_number | string | Vendor's account |
| processed_at | datetime | When approved |

#### referral_coins
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK |
| balance | decimal | Current balance |
| total_earned | decimal | Lifetime |
| total_converted | decimal | To normal coins |
| total_withdrawn | decimal | Cash withdrawn |

#### referral_transactions
| Field | Type | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK |
| referred_user_id | bigint | Nullable |
| type | string | 'signup_bonus', 'purchase_commission', 'withdrawal', 'converted_to_coins' |
| amount | decimal | + or - |
| balance_after | decimal | |
| status | string | 'pending', 'completed', 'rejected' |
| description | string | |
| metadata | json | Bank details, etc. |

---

## Configuration Guide

### Essential .env Variables

```env
# APP
APP_NAME=Ctools
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:... # Generate with php artisan key:generate

# DATABASE
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ctools_prod
DB_USERNAME=ctools_user
DB_PASSWORD=secure_password

# MAIL
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=mailgun_api_key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
MAIL_SEND_MODE=sync  # 'sync' or 'queue'

# SESSION
SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=yourdomain.com

# QUEUE (if using queue mode)
QUEUE_CONNECTION=database

# TELEGRAM
TELEGRAM_CHANNEL_URL=https://t.me/your_channel

# EXTERNAL TRACKING API (for tracking-app-php)
API_BASE_URL=https://yourdomain.com/api
```

### Config Files

**config/app.php:**
```php
'telegram_channel_url' => env('TELEGRAM_CHANNEL_URL'),
```

**config/mail.php:**
```php
'send_mode' => env('MAIL_SEND_MODE', 'sync'),
```

---

## Code Patterns

### 1. Service Pattern

```php
// app/Services/ExampleService.php
namespace App\Services;

class ExampleService
{
    public function doSomething($data): Result
    {
        // Business logic here
        return $result;
    }
}

// Usage in controller
$service = app(ExampleService::class);
$result = $service->doSomething($data);
```

### 2. Mail Helper Pattern

```php
use App\Helpers\MailHelper;
use App\Mail\SomeNotification;

// Sends via queue or sync based on config
MailHelper::send($user->email, new SomeNotification($data));

// Multiple recipients
MailHelper::sendToMany($admins, new SomeNotification($data));
```

### 3. Form Request Validation

```php
// app/Http/Requests/StoreShipmentRequest.php
class StoreShipmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sender_name' => 'required|string|max:255',
            'receiver_email' => 'nullable|email',
        ];
    }
}

// Controller
public function store(StoreShipmentRequest $request)
{
    $validated = $request->validated();
    // Data is already validated
}
```

### 4. Policy Authorization

```php
// app/Policies/ShipmentPolicy.php
public function view(User $user, Shipment $shipment): bool
{
    return $user->id === $shipment->user_id || $user->isAdmin();
}

// Controller
public function show(Shipment $shipment)
{
    $this->authorize('view', $shipment);
    // Authorized
}
```

### 5. Custom Validation Rules

```php
// app/Rules/ValidChatWidget.php
class ValidChatWidget implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validation logic
        if (! $this->isValid($value)) {
            $fail('The :attribute is not valid.');
        }
    }
}

// Usage
$request->validate([
    'widget_code' => ['nullable', 'string', new ValidChatWidget($provider)],
]);
```

---

## API Documentation

### Authentication
All API endpoints use the `auth:sanctum` middleware (except public tracking).

### Public Tracking API

**Endpoint:** `GET /api/public/track/{tracking_id}`

**Rate Limit:** 30 requests per minute

**Request:**
```http
GET /api/public/track/ABC123456
Accept: application/json
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "tracking_id": "ABC123456",
    "status": "in_transit",
    "progress": 75,
    "shipment_type": "air_freight",
    "sender": "John Doe",
    "receiver": "Jane Doe",
    "origin": "Lagos, Nigeria",
    "destination": "New York, USA",
    "shipped_at": "2026-04-10T10:00:00Z",
    "estimated_delivery": "2026-04-15T14:00:00Z",
    "courier": "DHL",
    "timeline": [
      {
        "status": "in_transit",
        "location": "London Hub",
        "message": "Package in transit",
        "progress": 75,
        "timestamp": "2026-04-12T08:30:00Z"
      }
    ],
    "chat": {
      "provider": "whatsapp",
      "widget_code": "<!-- widget code -->"
    }
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "error": "Tracking number not found"
}
```

---

## Deployment

### Step-by-Step Deployment

```bash
# 1. Clone and install
git clone [repo-url]
cd ctools
composer install --optimize-autoloader --no-dev

# 2. Environment
cp .env.example .env
# Edit .env with production values
php artisan key:generate

# 3. Database
php artisan migrate --force

# 4. Storage
php artisan storage:link
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 5. Build assets
npm install
npm run build

# 6. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Queue worker (if using queue mode)
php artisan queue:work
```

### Post-Deployment Setup

1. **Create Admin User:**
```bash
php artisan tinker
>>> User::create(['name'=>'Admin','email'=>'admin@domain.com','password'=>bcrypt('password'),'role'=>'admin','is_active'=>true]);
```

2. **Configure Service Pricing:**
   - Login as admin
   - Go to Admin → Coin System → Service Costs
   - Set prices for shipment_create, flight_ticket, etc.

3. **Add Bank Account:**
   - Admin → Coin System → Bank Accounts
   - Add at least one for P2P purchases

4. **Configure Referral Settings:**
   - Admin → Referral Program → Settings
   - Enable/disable, set bonus amounts

---

## Troubleshooting

### Common Issues

#### 419 Page Expired (CSRF)
**Cause:** Session/cookie misconfiguration
**Fix:**
```env
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true  # If using HTTPS
```

#### Emails Not Sending
**Check:**
1. SMTP credentials in .env
2. `MAIL_SEND_MODE` setting
3. If queue mode: is `php artisan queue:work` running?
4. Check `storage/logs/laravel.log`

#### Queue Worker Not Processing
**Check:**
```bash
# Check if running
ps aux | grep queue:work

# Restart
php artisan queue:restart
php artisan queue:work --timeout=60 --tries=3
```

#### Images Not Uploading
**Fix:**
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

#### 500 Errors
**Debug:**
```env
APP_DEBUG=true  # Temporarily to see stack trace
```
Check `storage/logs/laravel.log`

### Performance Optimization

**Enable caching:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Database indexing:**
- `shipments.tracking_id` - Already indexed
- `users.email` - Already indexed
- `coin_purchases.user_id` - Already indexed

---

## Key Files to Know

| File | Purpose |
|------|---------|
| `routes/web.php` | All web routes |
| `routes/api.php` | API routes |
| `app/Services/CoinService.php` | Core coin logic |
| `app/Services/ReferralService.php` | Referral logic |
| `app/Helpers/MailHelper.php` | Email abstraction |
| `config/mail.php` | Mail configuration |
| `.env` | Environment variables |

---

## Contact & Support

For questions or issues:
1. Check `storage/logs/laravel.log`
2. Review Laravel docs: https://laravel.com/docs
3. Check code comments in service classes

---

**End of Documentation**
