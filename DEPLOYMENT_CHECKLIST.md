# Ctools Deployment Checklist

## ✅ Pre-Deployment Configuration

### 1. Environment Variables (.env)

```env
# APP CONFIG
APP_NAME=Ctools
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# DATABASE (Update with production credentials)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# SESSION (Secure settings for production)
SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=yourdomain.com
SESSION_HTTP_ONLY=true
SESSION_SECURE_COOKIE=true  # Set to true if using HTTPS
SESSION_SAME_SITE=strict

# MAIL (Configure for email notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org  # or your SMTP provider
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# QUEUE (For background jobs - emails, etc)
QUEUE_CONNECTION=database

# CACHE
CACHE_STORE=database
CACHE_PREFIX=ctools_cache

# TELEGRAM (Update with your actual channel)
TELEGRAM_CHANNEL_URL=https://t.me/your_actual_channel
```

### 2. Security Settings

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set strong `APP_KEY` (run `php artisan key:generate` if needed)
- [ ] Configure HTTPS and set `SESSION_SECURE_COOKIE=true`
- [ ] Update `SESSION_DOMAIN` to your actual domain
- [ ] Remove Telescope/Debugbar if installed (`composer remove laravel/telescope`)
- [ ] Configure CORS properly for API access
- [ ] Set proper file permissions (755 for directories, 644 for files)

### 3. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (if needed)
php artisan db:seed --force

# Setup admin user
php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin','email'=>'admin@yourdomain.com','password'=>bcrypt('secure_password'),'role'=>'admin','is_active'=>true]);
```

### 4. Storage Setup

```bash
# Create storage link
php artisan storage:link

# Ensure proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage  # For Ubuntu/Apache
```

### 5. Optimization (Run these after deployment)

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev

# Cache config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear any old cached data
php artisan cache:clear
php artisan config:clear
```

### 6. Queue Worker (For emails and background jobs)

If using database queue, setup a supervisor process:

```bash
# Install supervisor
sudo apt-get install supervisor

# Create config file
sudo nano /etc/supervisor/conf.d/ctools-worker.conf
```

Add this content:
```ini
[program:ctools-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/html
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ctools-worker:*
```

### 7. Web Server Configuration

#### Apache (.htaccess)
Ensure `public/.htaccess` exists with:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 8. SSL Certificate (HTTPS)

```bash
# Using Certbot (Let's Encrypt)
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### 9. Admin Setup

1. **Bank Accounts**: Go to Admin → Coin System → Bank Accounts and add at least one active bank account for P2P payments

2. **Service Pricing**: Configure coin costs for services (flight tickets, shipments, etc.)

3. **Referral Settings**: Set signup bonus, commission percentage, and minimum withdrawal

4. **Admin User**: Create an admin user if not exists

### 10. Testing Checklist

- [ ] User registration works
- [ ] Login/logout works
- [ ] Email notifications sent (if configured)
- [ ] Coin purchase flow works (upload proof)
- [ ] Admin can approve coin purchases
- [ ] Shipment creation works
- [ ] Tracking page displays correctly
- [ ] Referral system works (signup bonus, commissions)
- [ ] Withdrawal request → Admin approval → User notification flow works
- [ ] Telegram links work
- [ ] Mobile responsive design works
- [ ] API endpoints work (for tracking app)

### 11. Monitoring & Logs

```bash
# Monitor logs
tail -f storage/logs/laravel.log
tail -f storage/logs/worker.log

# Setup log rotation
sudo nano /etc/logrotate.d/ctools
```

Add:
```
/var/www/html/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

### 12. Backup Strategy

Setup automated database backups:

```bash
# Create backup script
sudo nano /usr/local/bin/ctools-backup.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p'your_password' your_db_name > /backup/ctools_backup_$DATE.sql
find /backup -name "ctools_backup_*.sql" -mtime +30 -delete
```

```bash
chmod +x /usr/local/bin/ctools-backup.sh

# Add to crontab (daily at 2 AM)
crontab -e
0 2 * * * /usr/local/bin/ctools-backup.sh
```

### 13. Post-Deployment Commands

```bash
cd /var/www/html

# Set proper ownership
sudo chown -R www-data:www-data .

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
php artisan queue:restart
```

### 14. Security Headers (Optional but recommended)

Add to `app/Http/Middleware/SecurityHeaders.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
```

Register in `bootstrap/app.php`:
```php
$middleware->append(\App\Http\Middleware\SecurityHeaders::class);
```

---

## 🚀 Quick Deploy Script

```bash
#!/bin/bash
cd /var/www/html
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
php artisan queue:restart
chown -R www-data:www-data .
echo "Deployment complete!"
```

---

## ⚠️ Common Issues

1. **419 CSRF Errors**: Session/cookie misconfiguration - check domain settings
2. **Permission Denied**: Run `chmod -R 755 storage bootstrap/cache`
3. **Queue not processing**: Ensure supervisor is running
4. **Emails not sending**: Check SMTP credentials and queue worker
5. **Images not uploading**: Check storage link and permissions

---

## 📋 After Deployment

- [ ] Test all critical user flows
- [ ] Verify email notifications work
- [ ] Check queue worker is processing jobs
- [ ] Monitor error logs for 24-48 hours
- [ ] Setup uptime monitoring (e.g., UptimeRobot)
- [ ] Configure CDN for assets (optional)
