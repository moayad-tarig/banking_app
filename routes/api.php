<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountDepositController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\PinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//route group with auth prefix 
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthenticationController::class, 'user']);
        Route::get('/logout', [AuthenticationController::class, 'logout']);
    });
});

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix('onboarding')->group(function () {
        Route::post('setup/pin', [PinController::class, 'setupPin']);

        Route::middleware(['has.set.pin'])->group(function () {
            Route::post('generate/account-number', [AccountController::class, 'store']);
            Route::post('validate/pin', [PinController::class, 'validatePin']);
        });
    });

    Route::middleware(['has.set.pin'])->group(function () {
         Route::post('account/deposit' , [AccountDepositController::class , 'store']);
    });
});
