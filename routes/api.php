<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupermarketSaleController;

Route::prefix('sales')->group(function(){
    
    Route::post('/auth/token', [AuthController::class, 'generateCredentials']);
    
    
    Route::middleware(['api.key'])->group(function(){
    // Endpoint GET
    Route::get('/',[SupermarketSaleController::class, 'index']);
    });

    Route::middleware(['jwt.auth'])->group(function(){
    // Endpoint POST
    Route::post('/search', [SupermarketSaleController::class, 'search']);
    Route::post('/store', [SupermarketSaleController::class, 'store']);
    Route::post('/statistics', [SupermarketSaleController::class, 'statistics']);
    });
    
});
