<?php
/**
 * Configuration for Tracking App
 */

// Main app API base URL (where your Laravel app is hosted)
// FOR LOCALHOST XAMPP (your current setup):
define('API_BASE_URL', 'http://localhost/shipping/public/api/public');

// Alternative if using php artisan serve:
// define('API_BASE_URL', 'http://localhost:8000/api/public');

// App name
define('APP_NAME', 'Cargo Tracking');

// Cache directory (must be writable)
define('CACHE_DIR', __DIR__ . '/cache');

// Cache duration in seconds (2 minutes)
define('CACHE_DURATION', 120);

// Rate limit: max requests per minute per IP
define('RATE_LIMIT', 30);

// Create cache directory if not exists
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0755, true);
}
