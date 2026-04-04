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


    Route::get('/preview/united-ticket', function () {
        // Sample data matching your PDF template
        $logoPath = public_path('images/airlines/united-logo.png');
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $ticket = [
            'passenger_name' => 'JOHN DOE',
            'ticket_number' => '016-1234567890',
            'booking_reference' => 'ABC123',
            'flight_number' => 'UA123',
            'airline' => 'United Airlines',
            'origin' => 'JFK',
            'destination' => 'LAX',
            'departure_time' => '10:00 AM',
            'arrival_time' => '01:30 PM',
            'departure_date' => '2024-04-15',
            'seat_class' => 'business',
            'seat' => '4A',
            'price' => 1250.00,
            'taxes' => 125.00,
            'total' => 1375.00,
            'ff_number' => 'MP 12345678',
            'date' => 'Apr 15, 2024',
            'day' => 'Monday',
            'origin_city' => 'New York',
            'destination_city' => 'Los Angeles',
            'class_code' => 'J',
            'class' => 'Business',
            'logo' => $logoBase64,
        ];
        
        // Render as HTML (not PDF) for instant preview
        return view('flights.templates.united-eticket', compact('ticket'));
    })->middleware('auth');

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
