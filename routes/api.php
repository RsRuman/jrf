<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use Illuminate\Support\Facades\Route;

# Public routes
Route::group(['prefix' => 'v1', 'middleware' => 'api'], function () {
    Route::post('sign-up', [AuthenticationController::class, 'signUp']);
    Route::post('login', [AuthenticationController::class, 'login']);
});


# Authenticate route
Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);
});
