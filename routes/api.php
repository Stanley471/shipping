<?php

use App\Http\Controllers\Api\PublicTrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

/*
|--------------------------------------------------------------------------
| Public Tracking API
|--------------------------------------------------------------------------
| These endpoints are publicly accessible for external tracking websites
| and applications. They return only sanitized, public-safe data.
|
*/

// Public tracking endpoint - no authentication required
// Rate limited to prevent abuse
Route::get('/public/track/{tracking_id}', [PublicTrackingController::class, 'show'])
    ->name('api.public.track')
    ->middleware('throttle:public-tracking');

// Alternative endpoint for batch tracking (optional future feature)
// Route::post('/public/track/batch', [PublicTrackingController::class, 'batch'])
//     ->name('api.public.track.batch')
//     ->middleware('throttle:public-tracking');
