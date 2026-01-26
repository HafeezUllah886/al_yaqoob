<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Controllers\ScrapePurchaseController;
use App\Http\Controllers\ScrapeSaleController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('scrap_purchase', ScrapePurchaseController::class);
    Route::get('scrap_purchase/delete/{ref}', [ScrapePurchaseController::class, 'destroy'])->name('scrap_purchase.delete')->middleware(confirmPassword::class);

    Route::resource('scrap_sale', ScrapeSaleController::class);
    Route::get('scrap_sale/delete/{ref}', [ScrapeSaleController::class, 'destroy'])->name('scrap_sale.delete')->middleware(confirmPassword::class);

   


});
