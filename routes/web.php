<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;

// Default homepage → redirect to payment selection
Route::get('/', function () {
       return view('welcome');
});

//authentication routes
// Route::middleware('guest')->group(function () {
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::resource('events', EventController::class);
// });

//Protected routes, meaning have to log in to access
Route::middleware('auth')->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    //authenticated user routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    //Manager Application routes
    Route::get('/apply-manager', [UserController::class, 'showManagerApplication'])->name('manager.apply');
    // Route::put('/apply-manager', [UserController::class, 'submitManagerApplication'])->name('manager.submit');
    Route::post('/apply-manager', [UserController::class, 'submitManagerApplication'])->name('manager.submit');


    //Payment routes
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('paymentPage');
    Route::post('/payment/checkout', [PaymentController::class, 'checkoutForm'])->name('payment.checkout');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/status', [PaymentController::class, 'status'])->name('payment.status');

    //Event routes
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

// Admin stuff
Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);


    // Protected admin routes
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

// Include default auth routes if they exist
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
?>

=======
// Payment routes
Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/checkout', [PaymentController::class, 'checkoutForm'])->name('payment.checkout');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/status', [PaymentController::class, 'status'])->name('payment.status');
>>>>>>> Stashed changes
