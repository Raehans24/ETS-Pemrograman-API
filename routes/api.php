<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupermarketSaleController;

Route::prefix('sales')->group(function(){
    // Endpoint GET
    Route::get('/',[SupermarketSaleController::class, 'index']);

    // Endpoint POST
    Route::post('/search', [SupermarketSaleController::class, 'search']);
    Route::post('/store', [SupermarketSaleController::class, 'store']);
    Route::post('/statistics', [SupermarketSaleController::class, 'statistics']);
    
});
