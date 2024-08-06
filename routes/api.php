<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('company', CompanyController::class);

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'fetch']);

    Route::get('company/{id}', [CompanyController::class, 'index']);
    Route::apiResource('company', CompanyController::class);

    Route::get('team/{id}', [TeamController::class, 'index']);
    Route::apiResource('team', TeamController::class);
});
