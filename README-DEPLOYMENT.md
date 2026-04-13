# Ctools - Pre-Deployment Summary

## ✅ What's Already Implemented

### Core Features
- ✅ User Authentication (Login/Register)
- ✅ Shipment Management (Create, Track, Update)
- ✅ Coin System (Purchase via P2P, Earn, Spend)
- ✅ Vendor System (P2P coin sellers)
- ✅ Referral System (Signup bonus, commissions, withdrawals)
- ✅ Flight Tickets (Search, Book, Generate e-tickets)
- ✅ Admin Panel (Users, Shipments, Coin Management)
- ✅ Public Tracking API (External app support)
- ✅ Chat Widget (WhatsApp/SmartSupp per shipment)
- ✅ Telegram Integration (Popup + Menu links)

### Security
- ✅ CSRF Protection
- ✅ Rate Limiting on API
- ✅ Input Validation
- ✅ XSS Protection in views
- ✅ Middleware for roles (admin, vendor)

### UI/UX
- ✅ Responsive Design (Mobile + Desktop)
- ✅ Dark Mode Support
- ✅ Real-time Notifications
- ✅ PDF Generation for shipments

---

## ⚠️ Critical Items Before Deployment

### 1. Environment Configuration (`.env`)

**MUST CHANGE:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Configure Email (Required for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=your_mailgun_key
MAIL_FROM_ADDRESS=noreply@yourdomain.com

# Update Telegram Link
TELEGRAM_CHANNEL_URL=https://t.me/your_actual_channel

# Session Security
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=yourdomain.com
```

### 2. Database Setup

```bash
# Run all migrations
php artisan migrate --force

# Create admin user
php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin','email'=>'admin@yourdomain.com','password'=>bcrypt('your_password'),'role'=>'admin','is_active'=>true,'is_vendor'=>false]);
```

### 3. Initial Admin Configuration

After logging in as admin, you MUST:

1. **Setup Bank Account** (Admin → Coin System → Bank Accounts)
   - Add at least one admin bank account for P2P payments

2. **Configure Service Pricing** (Admin → Coin System → Service Costs)
   - flight_ticket: ~100 coins
   - create_shipment: ~50 coins
   - edit_shipment: ~0 coins
   - update_shipment: ~0 coins

3. **Configure Referral Settings** (Admin → Referral Program → Settings)
   - Enable/disable referral system
   - Set signup bonus (e.g., 50 coins)
   - Set purchase commission % (e.g., 10%)
   - Set minimum withdrawal (e.g., 1000 coins)

### 4. Storage Permissions

```bash
# Link storage
php artisan storage:link

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage  # For Ubuntu/Apache
```

### 5. Queue Worker (Required for emails)

```bash
# Install supervisor
sudo apt-get install supervisor

# Create worker config
sudo nano /etc/supervisor/conf.d/ctools-worker.conf
```

Paste:
```ini
[program:ctools-worker]
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3
directory=/var/www/html
autostart=true
autorestart=true
user=www-data
numprocs=2
stdout_logfile=/var/www/html/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ctools-worker:*
```

---

## 🎯 Deployment Steps

### Step 1: Upload Files
```bash
cd /var/www/html
# Upload your project files
composer install --optimize-autoloader --no-dev
```

### Step 2: Environment
```bash
cp .env.example .env
# Edit .env with production values
php artisan key:generate
```

### Step 3: Database
```bash
php artisan migrate --force
```

### Step 4: Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Permissions
```bash
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/storage
```

---

## 📋 Post-Deployment Testing

### User Flow Tests
- [ ] Register new account
- [ ] Login/Logout
- [ ] Create shipment (check coin deduction)
- [ ] Purchase coins via P2P
- [ ] Track shipment
- [ ] Referral signup (test bonus)
- [ ] Withdrawal request → Admin approve

### Admin Tests
- [ ] Access admin panel
- [ ] Approve coin purchase
- [ ] View withdrawal requests
- [ ] Approve/Reject withdrawal

### API Tests
- [ ] Public tracking API works: `https://yourdomain.com/api/public/track/ABC123`

---

## 📋 Optional Improvements (Post-Launch)

1. **Payment Gateway Integration** - Currently P2P only
2. **SMS Notifications** - Add Twilio/AfricasTalking
3. **Live Chat** - Intercom/Tawk.to integration
4. **Analytics** - Google Analytics
5. **Error Monitoring** - Sentry/Honeybadger
6. **CDN** - CloudFlare for assets
7. **Backup Automation** - Daily database backups

---

## 🚨 Common Deployment Issues

| Issue | Solution |
|-------|----------|
| 419 CSRF Error | Check SESSION_DOMAIN matches your URL |
| Emails not sending | Verify SMTP credentials, check queue worker |
| Storage error | Run `php artisan storage:link` |
| Permission denied | Run `chmod -R 755 storage` |
| 500 Error | Check `storage/logs/laravel.log` |

---

## 📞 Support

For deployment assistance, check:
- `DEPLOYMENT_CHECKLIST.md` - Detailed step-by-step guide
- Laravel Docs: https://laravel.com/docs/deployment
- Storage logs: `storage/logs/laravel.log`
