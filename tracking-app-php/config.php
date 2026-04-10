<?php
/**
 * Configuration for Tracking App
 */

// Main app API base URL (where your Laravel app is hosted)
define('API_BASE_URL', 'https://yourmainapp.com/api/public');

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
