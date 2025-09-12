<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// Default homepage
Route::get('/', function () {
    return view('welcome');
});

// ====================== AUTH ======================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ====================== EVENTS ======================
Route::resource('events', EventController::class);
Route::get('/events/index', [EventController::class, 'publicIndex'])->name('events.public');

// ====================== PROTECTED (USER) ======================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Apply Manager
    Route::get('/apply-manager', [UserController::class, 'showManagerApplication'])->name('manager.apply');
    Route::post('/apply-manager', [UserController::class, 'submitManagerApplication'])->name('manager.submit');

    // ====================== RESERVATIONS ======================
    Route::get('/events/{event}/reserve', [ReservationController::class, 'checkout'])->name('reservations.checkout');
    Route::post('/events/{event}/reserve', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/events/{event}/reserve', [ReservationController::class, 'checkout'])->name('reservations.checkout');

    // ====================== PAYMENTS ======================
    Route::get('/payments/checkout/{reservation}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/status', [PaymentController::class, 'status'])->name('payments.status');

    // Venues
    Route::resource('venues', VenueController::class);
});

// ====================== ADMIN ======================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/{user}/promote', [AdminController::class, 'promoteToManager'])->name('users.promote');
        Route::post('/users/{user}/demote', [AdminController::class, 'demoteManager'])->name('users.demote');

        // Manager applications
        Route::get('/manager-applications', [AdminController::class, 'managerApplications'])->name('manager.applications');
        Route::post('/manager-applications/{user}/approve', [AdminController::class, 'approveApplication'])->name('manager.approve');
        Route::post('/manager-applications/{user}/reject', [AdminController::class, 'rejectApplication'])->name('manager.reject');

        // Admin profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    });
});

// ====================== EXTRA ======================
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

//Reservation Route
Route::get('/reservations/{event}', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations/{event}', [ReservationController::class, 'store'])->name('reservations.store');
