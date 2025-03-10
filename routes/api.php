<?php

use App\Http\Controllers\Api\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//route group with auth prefix 
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthenticationController::class, 'register']);

});
