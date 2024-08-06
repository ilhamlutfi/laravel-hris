<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ResponsibilityController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('company', CompanyController::class);

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'fetch']);

    Route::apiResource('company', CompanyController::class)->except([
        'show'
    ]);

    Route::apiResource('team', TeamController::class)->except([
        'show'
    ]);

    Route::apiResource('role', RoleController::class)->except([
        'show'
    ]);

    Route::apiResource('responsibility', ResponsibilityController::class)->except([
        'show'
    ]);

    Route::apiResource('employee', EmployeeController::class)->except([
        'show'
    ]);
});
