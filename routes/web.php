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



// ====================== PROTECTED (USER) ======================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Apply Manager
    Route::middleware(['auth', 'permission:apply_for_manager'])->group(function(){
        Route::get('/apply-manager', [UserController::class, 'showManagerApplication'])->name('manager.apply');
        Route::post('/apply-manager', [UserController::class, 'submitManagerApplication'])->name('manager.submit');
    });


    // ====================== PAYMENTS ======================
    Route::get('/payments/checkout/{reservation}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/status', [PaymentController::class, 'status'])->name('payments.status');

    // ====================== EVENTS ======================
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/index', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/create', [EventController::class, 'store'])->name('store');
        Route::get('/show/{event}', [EventController::class, 'show'])->name('show');
        Route::get('/edit/{event}', [EventController::class, 'edit'])->name('edit');
        Route::put('/edit/{event}', [EventController::class, 'update'])->name('update');
});

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

        Route::get('/events/index', [EventController::class, 'index'])->name('index');
        Route::get('/events/edit', [EventController::class, 'edit'])->name('edit');
        Route::get('/events/create', [EventController::class, 'create'])->name('create');
        Route::post('/events/create/', [EventController::class, 'store'])->name('store');
    });
});

//====================== Reservation ==========================
Route::prefix('reservations')->name('reservations.')->group(function () {
    Route::get('/index', [ReservationController::class, 'index'])->name('index');
    Route::get('/create/{event}', [ReservationController::class, 'create'])->name('create');
    Route::post('/create', [ReservationController::class, 'store'])->name('store');

    // Session token with finalize page
    Route::get('/finalize/{token}', [ReservationController::class, 'finalize'])->name('finalize');
    // Confirm and save the reservation permanently
    Route::post('/confirm/{token}', [ReservationController::class, 'confirmReservation'])->name('confirm');
});



// ====================== EXTRA ======================
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
