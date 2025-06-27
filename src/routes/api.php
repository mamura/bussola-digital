<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;

Route::prefix('cart')->group(function () {
    Route::post('/', [CartController::class, 'store']);
    Route::get('/', [CartController::class, 'index']);
    Route::delete('/clear', [CartController::class, 'clear']);
    Route::patch('/{id}', [CartController::class, 'update']);
    Route::delete('/{id}', [CartController::class, 'destroy']);
    
});

Route::prefix('checkout')->group(function () {
    Route::post('/', [CheckoutController::class, 'store']);
    Route::post('/preview', [CheckoutController::class, 'preview']);
});
