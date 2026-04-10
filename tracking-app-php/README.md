# Cargo Tracking - Pure PHP Version

A lightweight, standalone tracking website built with pure PHP. No database required - fetches data from the main app's API.

## Requirements

- PHP 8.0 or higher
- cURL extension enabled
- Write permissions for `cache/` and `logs/` directories

## File Structure

```
tracking-app-php/
├── index.php          → Homepage with tracking form
├── result.php         → Tracking result page
├── 404.php           → Error page
├── config.php         → Configuration (API URL, app name)
├── functions.php      → Helper functions (API, cache, rate limit)
├── .htaccess          → URL rewriting (optional)
├── robots.txt         → SEO
├── sitemap.xml        → SEO
├── README.md          → This file
├── cache/             → File cache (auto-created)
└── logs/              → Error logs (auto-created)
```

## Quick Setup

1. **Edit `config.php`**:
```php
define('API_BASE_URL', 'https://yourmainapp.com/api/public');
define('APP_NAME', 'Your Tracking Site Name');
```

2. **Upload to your hosting** (any shared hosting with PHP works)

3. **Make directories writable**:
```bash
chmod 755 cache/ logs/
```

4. **Update sitemap.xml** with your domain:
```xml
<loc>https://yourtrackingdomain.com/</loc>
```

5. **Done!** 🎉

## Features

### Core Features
- ✅ **API Integration** - Calls main app's `/api/public/track/{id}` endpoint
- ✅ **File Caching** - 2-minute cache reduces API calls
- ✅ **Rate Limiting** - 30 requests per minute per IP
- ✅ **No Database** - Just needs file write permissions

### UI/UX Features
- ✅ **Dark Mode** - User-toggleable, persists in localStorage
- ✅ **Mobile Responsive** - Tailwind CSS
- ✅ **Loading States** - Button shows spinner during API call
- ✅ **Auto-formatting** - Tracking IDs auto-uppercase and strip invalid chars
- ✅ **Recent Searches** - Shows last 5 searches (stored in cookie)
- ✅ **Copy Link** - One-click copy tracking URL
- ✅ **Print Styles** - Optimized print layout
- ✅ **Clean URLs** - `/ABC123` redirects to result page (with .htaccess)

### SEO & Meta
- ✅ **Meta Tags** - Dynamic OG tags for social sharing
- ✅ **Favicon** - Emoji-based (📦)
- ✅ **Robots.txt** - Search engine friendly
- ✅ **Sitemap.xml** - SEO
- ✅ **Canonical URLs** - Prevent duplicate content

### Security
- ✅ **Input Sanitization** - All user inputs cleaned
- ✅ **Rate Limiting** - File-based IP tracking
- ✅ **Secure Cookies** - HttpOnly, Secure, SameSite
- ✅ **Error Logging** - Failed requests logged (no sensitive data)
- ✅ **Cache Protection** - .htaccess blocks direct access

## URLs

| URL | Description |
|-----|-------------|
| `/` | Search form |
| `/result.php` | POST handler for form |
| `/result.php?id=ABC123` | Direct tracking link |
| `/ABC123` | Clean URL (requires .htaccess) |

## API Integration

The app expects your main Laravel app to provide:

```
GET /api/public/track/{tracking_id}
```

Response format:
```json
{
  "success": true,
  "data": {
    "tracking_id": "ABC123",
    "status": "in_transit",
    "progress": 50,
    "shipment_type": "air_freight",
    "sender": "John Doe",
    "receiver": "Jane Smith",
    "origin": "New York",
    "destination": "Los Angeles",
    "shipped_at": "2024-01-15T10:00:00+00:00",
    "estimated_delivery": "2024-01-20T10:00:00+00:00",
    "courier": "DHL",
    "timeline": [
      {
        "status": "in_transit",
        "location": "Chicago",
        "message": "Package in transit",
        "progress": 50,
        "timestamp": "2024-01-16T14:30:00+00:00"
      }
    ]
  }
}
```

## Configuration Options

Edit `config.php`:

```php
// Required: Your main app's API URL
define('API_BASE_URL', 'https://yourmainapp.com/api/public');

// App branding
define('APP_NAME', 'Cargo Tracking');

// Cache settings
define('CACHE_DURATION', 120);        // 2 minutes
define('CACHE_DIR', __DIR__ . '/cache');

// Rate limiting
define('RATE_LIMIT', 30);             // requests per minute

// Recent searches cookie duration
define('RECENT_SEARCHES_DAYS', 30);
```

## Troubleshooting

### "Permission denied" errors
```bash
chmod 755 cache/ logs/
chown www-data:www-data cache/ logs/  # On Ubuntu/Debian
```

### API not responding
- Check `API_BASE_URL` in `config.php`
- Ensure main app API is accessible
- Check PHP cURL extension: `php -m | grep curl`
- Check error logs in `logs/error.log`

### Rate limit exceeded
- Wait 1 minute
- Rate limit: 30 requests per minute per IP
- Cached responses don't count toward rate limit

### Clean URLs not working
- Ensure Apache mod_rewrite is enabled
- Check `.htaccess` is uploaded
- For Nginx, add rewrite rule manually:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Nginx Configuration

If using Nginx instead of Apache:

```nginx
server {
    listen 80;
    server_name yourtrackingdomain.com;
    root /var/www/tracking-app-php;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Protect sensitive directories
    location ~ ^/(cache|logs)/ {
        deny all;
    }
}
```

## Performance

- **Cached responses**: Served instantly from file
- **API calls**: Only when cache misses
- **Rate limiting**: File-based (no Redis needed)
- **Memory usage**: ~2MB per request
- **Load time**: <100ms for cached results

## Security Considerations

1. **Keep config.php secret** - Contains API URL
2. **Protect cache/logs directories** - Already blocked by .htaccess
3. **Use HTTPS** - Both for tracking site and API
4. **Monitor logs** - Check `logs/error.log` for abuse attempts
5. **Rate limiting** - Already built-in, adjust if needed

## Customization

### Change Colors
Edit Tailwind config in each file or switch to custom CSS.

### Add Analytics
Add tracking code before `</head>`:
```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_ID"></script>
```

### Custom Favicon
Replace the emoji favicon with a real one:
```html
<link rel="icon" href="/favicon.ico">
```

### Multi-language Support
Add `lang.php` with translations and replace hardcoded strings.

## Comparison with Laravel Version

| Feature | Pure PHP | Laravel |
|---------|----------|---------|
| Files | 10 | 50+ |
| Setup | 2 minutes | 10+ minutes |
| Memory | ~2 MB | ~15 MB |
| Hosting | Any shared hosting | Needs Composer |
| Database | None | Optional |
| Cache | File | File/Redis/DB |
| Dependencies | None | Many |

## License

Same as main application.

---

**Need help?** Check `logs/error.log` for debugging info.
