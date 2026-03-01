<?php

use App\Http\Controllers\reports\SalesReportController;
use App\Http\Controllers\reports\PurchasesReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('reports/sales', [SalesReportController::class, 'index'])->name('reports.sales.index');
    Route::get('reports/sales/details', [SalesReportController::class, 'details'])->name('reports.sales.details');

    Route::get('reports/purchases', [PurchasesReportController::class, 'index'])->name('reports.purchases.index');
    Route::get('reports/purchases/details', [PurchasesReportController::class, 'details'])->name('reports.purchases.details');

});
