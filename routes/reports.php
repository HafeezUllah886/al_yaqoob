<?php

use App\Http\Controllers\reports\SalesReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('reports/sales', [SalesReportController::class, 'index'])->name('reports.sales.index');
    Route::get('reports/sales/details', [SalesReportController::class, 'details'])->name('reports.sales.details');

});
