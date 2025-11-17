<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Products API Routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    
    // Admin routes (TODO: Add auth middleware)
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

// Services API Routes
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/{id}', [ServiceController::class, 'show']);
    
    // Admin routes (TODO: Add auth middleware)
    Route::post('/', [ServiceController::class, 'store']);
    Route::put('/{id}', [ServiceController::class, 'update']);
    Route::delete('/{id}', [ServiceController::class, 'destroy']);
    Route::patch('/{id}/update-clients', [ServiceController::class, 'updateClients']);
});

// Contacts API Routes
Route::prefix('contacts')->group(function () {
    // Public route
    Route::post('/', [ContactController::class, 'store']);
    
    // Admin routes (TODO: Add auth middleware)
    Route::get('/', [ContactController::class, 'index']);
    Route::get('/statistics', [ContactController::class, 'statistics']);
    Route::get('/{id}', [ContactController::class, 'show']);
    Route::patch('/{id}/status', [ContactController::class, 'updateStatus']);
    Route::delete('/{id}', [ContactController::class, 'destroy']);
});
