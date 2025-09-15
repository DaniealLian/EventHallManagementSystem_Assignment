<?php

use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserRegistrationController;

Route::apiResource('events', EventApiController::class);
Route::post('/register', [UserRegistrationController::class, 'register']);
