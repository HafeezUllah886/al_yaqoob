<?php

use App\Http\Controllers\SaleController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('sale', SaleController::class);

    Route::get('sales/delete/{id}', [SaleController::class, 'destroy'])->name('sales.delete')->middleware(confirmPassword::class);

});
