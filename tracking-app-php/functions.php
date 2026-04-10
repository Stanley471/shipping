<?php
/**
 * Helper Functions for Tracking App
 */

require_once 'config.php';

/**
 * Fetch tracking data from API with caching
 */
function fetchTrackingData(string $trackingId): ?array
{
    $cacheFile = CACHE_DIR . '/' . sanitizeFileName($trackingId) . '.json';
    
    // Check cache first
    if (file_exists($cacheFile)) {
        $age = time() - filemtime($cacheFile);
        if ($age < CACHE_DURATION) {
            $cached = file_get_contents($cacheFile);
            return json_decode($cached, true);
        }
    }
    
    // Fetch from API
    $url = API_BASE_URL . '/track/' . urlencode($trackingId);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$response) {
        return null;
    }
    
    $data = json_decode($response, true);
    
    // Cache successful response
    if ($data && $data['success'] ?? false) {
        file_put_contents($cacheFile, $response);
    }
    
    return $data;
}

/**
 * Check rate limit for current IP
 */
function checkRateLimit(): bool
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateFile = CACHE_DIR . '/rate_' . sanitizeFileName($ip) . '.json';
    
    $requests = [];
    if (file_exists($rateFile)) {
        $requests = json_decode(file_get_contents($rateFile), true) ?: [];
    }
    
    // Remove old requests (older than 1 minute)
    $now = time();
    $requests = array_filter($requests, function($timestamp) use ($now) {
        return ($now - $timestamp) < 60;
    });
    
    // Check limit
    if (count($requests) >= RATE_LIMIT) {
        return false;
    }
    
    // Add current request
    $requests[] = $now;
    file_put_contents($rateFile, json_encode(array_values($requests)));
    
    return true;
}

/**
 * Validate tracking ID format
 */
function validateTrackingId(string $trackingId): bool
{
    // Alphanumeric, 5-20 characters
    return preg_match('/^[A-Z0-9]{5,20}$/', strtoupper(trim($trackingId)));
}

/**
 * Sanitize string for safe file name
 */
function sanitizeFileName(string $string): string
{
    return preg_replace('/[^A-Z0-9_-]/i', '', $string);
}

/**
 * Format status for display
 */
function formatStatus(string $status): string
{
    return ucwords(str_replace('_', ' ', $status));
}

/**
 * Format date for display
 */
function formatDate(?string $isoDate): string
{
    if (!$isoDate) return 'N/A';
    $date = new DateTime($isoDate);
    return $date->format('M d, Y');
}

/**
 * Format datetime for display
 */
function formatDateTime(?string $isoDate): string
{
    if (!$isoDate) return 'N/A';
    $date = new DateTime($isoDate);
    return $date->format('M d, Y • H:i');
}

/**
 * Get status color class
 */
function getStatusColor(string $status): string
{
    return match($status) {
        'delivered' => 'green',
        'cancelled' => 'red',
        'out_for_delivery' => 'blue',
        default => 'emerald',
    };
}

/**
 * Get progress bar color
 */
function getProgressColor(int $progress): string
{
    return match(true) {
        $progress >= 100 => 'green',
        $progress >= 50 => 'emerald',
        default => 'yellow',
    };
}

/**
 * Clean old cache files
 */
function cleanOldCache(): void
{
    $files = glob(CACHE_DIR . '/*.json');
    $now = time();
    
    foreach ($files as $file) {
        if (is_file($file) && ($now - filemtime($file)) > CACHE_DURATION * 2) {
            unlink($file);
        }
    }
}

// Run cleanup occasionally (1% chance)
if (rand(1, 100) === 1) {
    cleanOldCache();
}

/**
 * Get recent searches from cookie
 */
function getRecentSearches(): array
{
    if (!isset($_COOKIE['recent_searches'])) {
        return [];
    }
    
    $searches = json_decode($_COOKIE['recent_searches'], true);
    if (!is_array($searches)) {
        return [];
    }
    
    // Filter valid tracking IDs and limit to 5
    return array_slice(array_filter($searches, 'validateTrackingId'), 0, 5);
}

/**
 * Add tracking ID to recent searches
 */
function addRecentSearch(string $trackingId): void
{
    if (!validateTrackingId($trackingId)) {
        return;
    }
    
    $searches = getRecentSearches();
    
    // Remove if already exists (move to top)
    $searches = array_diff($searches, [strtoupper($trackingId)]);
    
    // Add to beginning
    array_unshift($searches, strtoupper($trackingId));
    
    // Limit to 5
    $searches = array_slice($searches, 0, 5);
    
    // Save for 30 days
    setcookie('recent_searches', json_encode($searches), [
        'expires' => time() + 30 * 24 * 60 * 60,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

/**
 * Log error for debugging
 */
function logError(string $message): void
{
    $logFile = __DIR__ . '/logs/error.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $line = "[{$timestamp}] [{$ip}] {$message}" . PHP_EOL;
    
    error_log($line, 3, $logFile);
}
