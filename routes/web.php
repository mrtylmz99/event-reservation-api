<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Event Reservation API is running',
        'version' => '1.0.0',
        'documentation' => 'See README.md for endpoints',
        'status' => 'healthy'
    ]);
});
