<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FlightController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/track', [TrackingController::class, 'search'])
    ->middleware('throttle:tracking')
    ->name('tracking.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('shipments', ShipmentController::class);
    Route::get('/shipments/{shipment}/pdf', [ShipmentController::class, 'pdf'])->name('shipments.pdf');
    Route::post('/shipments/{shipment}/updates', [\App\Http\Controllers\TrackingUpdateController::class, 'store'])
    ->name('tracking-updates.store');

    // Flight Ticket Routes (Authenticated Only)
    Route::get('/flights', [FlightController::class, 'searchForm'])->name('flights.search');
    Route::post('/flights/search', [FlightController::class, 'search'])->name('flights.results');
    Route::get('/flights/book', [FlightController::class, 'bookForm'])->name('flights.book');
    Route::post('/flights/ticket', [FlightController::class, 'generateTicket'])->name('flights.ticket');
    Route::get('/flights/my-tickets', [FlightController::class, 'index'])->name('flights.index');
    Route::get('/flights/ticket/{flightTicket}', [FlightController::class, 'show'])->name('flights.show');
    Route::get('/flights/ticket/{flightTicket}/download', [FlightController::class, 'download'])->name('flights.download');
    Route::delete('/flights/ticket/{flightTicket}', [FlightController::class, 'destroy'])->name('flights.destroy');
    Route::get('/api/airports', [FlightController::class, 'autocompleteAirports'])->name('api.airports');

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::get('/shipments', [AdminController::class, 'shipments'])->name('shipments.index');
    });
});

require __DIR__.'/auth.php';
