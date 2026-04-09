<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\CoinAdminController;

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

    Route::get('/preview/delta-ticket', function () {
        $ticket = [
            'passenger_name' => 'KATE ERONDIA CARLISLE',
            'ticket_number' => '016-1234567890',
            'booking_reference' => 'HCP8PG',
            'flight_number' => 'DL547',
            'airline' => 'Delta Air Lines',
            'origin' => 'JAN',
            'destination' => 'GRR',
            'departure_time' => '15:02',
            'arrival_time' => '23:02',
            'date' => '2024-08-30',
            'seat_class' => 'economy',
            'seat' => '12A',
            'price' => 425.00,
            'class' => 'Economy',
        ];
        
        return view('flights.templates.delta-eticket', compact('ticket'));
    })->middleware('auth');

    Route::get('/preview/american-ticket', function () {
        $ticket = [
            'passenger_name' => 'ASHLEY MARTELLE',
            'ticket_number' => '176-2143480036',
            'booking_reference' => 'HG6NWJ',
            'flight_number' => 'AA9279',
            'airline' => 'American Airlines',
            'origin' => 'SFO',
            'destination' => 'SAN',
            'departure_time' => '14:05',
            'arrival_time' => '15:39',
            'date' => '2024-03-02',
            'return_date' => '2024-03-06',
            'seat_class' => 'economy',
            'seat' => '14C',
            'price' => 385.00,
            'class' => 'Economy',
        ];
        
        return view('flights.templates.american-eticket', compact('ticket'));
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

    // Coin Routes
    Route::get('/coins', [CoinController::class, 'index'])->name('coins.index');
    Route::get('/coins/buy', [CoinController::class, 'buyForm'])->name('coins.buy');
    Route::post('/coins/buy', [CoinController::class, 'buy']);
    Route::get('/coins/orders', [CoinController::class, 'orders'])->name('coins.orders');

    // Vendor Routes
    Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/vendor/setup', [VendorController::class, 'setup'])->name('vendor.setup');
    Route::post('/vendor/setup', [VendorController::class, 'store']);
    Route::get('/vendor/profile', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::put('/vendor/profile', [VendorController::class, 'update'])->name('vendor.update');
    Route::get('/vendor/orders', [VendorController::class, 'orders'])->name('vendor.orders');
    Route::post('/vendor/orders/{order}/confirm', [VendorController::class, 'confirmOrder'])->name('vendor.confirm');

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::post('/users/{user}/toggle-vendor', [AdminController::class, 'toggleVendorStatus'])->name('users.toggle-vendor');
        Route::get('/shipments', [AdminController::class, 'shipments'])->name('shipments.index');
        
        // Admin Coin Routes
        Route::get('/coins', [CoinAdminController::class, 'dashboard'])->name('coins.dashboard');
        Route::get('/coins/pending', [CoinAdminController::class, 'pendingPurchases'])->name('coins.pending');
        Route::post('/coins/purchases/{purchase}/approve', [CoinAdminController::class, 'approvePurchase'])->name('coins.purchases.approve');
        Route::post('/coins/purchases/{purchase}/reject', [CoinAdminController::class, 'rejectPurchase'])->name('coins.purchases.reject');
        Route::get('/coins/adjustment', [CoinAdminController::class, 'adjustmentForm'])->name('coins.adjustment');
        Route::post('/coins/adjustment', [CoinAdminController::class, 'adjustment']);
        Route::get('/coins/transactions', [CoinAdminController::class, 'transactions'])->name('coins.transactions');
        Route::get('/coins/services', [CoinAdminController::class, 'services'])->name('coins.services');
        Route::patch('/coins/services/{service}', [CoinAdminController::class, 'updateService'])->name('coins.services.update');
        Route::get('/coins/bank-accounts', [CoinAdminController::class, 'bankAccounts'])->name('coins.bank_accounts');
        Route::post('/coins/bank-accounts', [CoinAdminController::class, 'addBankAccount']);
        Route::patch('/coins/bank-accounts/{account}', [CoinAdminController::class, 'updateBankAccount']);
        Route::delete('/coins/bank-accounts/{account}', [CoinAdminController::class, 'deleteBankAccount']);
    });
});

require __DIR__.'/auth.php';
