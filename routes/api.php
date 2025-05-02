<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    // Public route (no tenant header needed)
    // Public routes (no tenancy)
    Route::post('/tenant-register', [TenantController::class, 'register']);

    // Tenant-specific routes
    Route::middleware('initialize.tenancy')->group(function () {
        Route::post('/tenant-user-register', [AuthController::class, 'register']);
        Route::post('/tenant-user-login', [AuthController::class, 'login']);
    });
    Route::middleware(['auth:sanctum', 'initialize.tenancy'])->group(function () {
        Route::get('/account', [AuthController::class, 'account']);
    });
});
