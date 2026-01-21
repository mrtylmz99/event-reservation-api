<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Venue Routes
    Route::get('venues', [VenueController::class, 'index']);
    Route::get('venues/{id}', [VenueController::class, 'show']);
    Route::get('venues/{id}/seats', [VenueController::class, 'getSeats']);
    
    // Admin only routes for Venues
    Route::post('venues', [VenueController::class, 'store']);
    Route::put('venues/{id}', [VenueController::class, 'update']);
    Route::delete('venues/{id}', [VenueController::class, 'destroy']);

    // Seat Routes
    Route::post('seats/block', [SeatController::class, 'block']);
    Route::delete('seats/release', [SeatController::class, 'release']);

    // Event Routes
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{id}', [EventController::class, 'show']);
    Route::get('events/{id}/seats', [EventController::class, 'getSeats']);
    
    // Admin only routes for Events
    Route::post('events', [EventController::class, 'store']);
    Route::put('events/{id}', [EventController::class, 'update']);
    Route::delete('events/{id}', [EventController::class, 'destroy']);

    // Reservation Routes
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::get('reservations/{id}', [ReservationController::class, 'show']);
    Route::post('reservations/{id}/confirm', [ReservationController::class, 'confirm']);
    Route::delete('reservations/{id}', [ReservationController::class, 'cancel']);

    // Ticket Routes
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('tickets/{id}', [TicketController::class, 'show']);
    Route::get('tickets/{id}/download', [TicketController::class, 'download']);
});
