<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::put('/apply-manager', [UserController::class, 'submitManagerApplication'])->name('manager.submit');

});

// Include default auth routes if they exist
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
?>