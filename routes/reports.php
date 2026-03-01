<?php

use App\Http\Controllers\reports\SalesReportController;
use App\Http\Controllers\reports\PurchasesReportController;
use App\Http\Controllers\reports\ExpenseReportController;
use App\Http\Controllers\reports\StockReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('reports/sales', [SalesReportController::class, 'index'])->name('reports.sales.index');
    Route::get('reports/sales/details', [SalesReportController::class, 'details'])->name('reports.sales.details');

    Route::get('reports/purchases', [PurchasesReportController::class, 'index'])->name('reports.purchases.index');
    Route::get('reports/purchases/details', [PurchasesReportController::class, 'details'])->name('reports.purchases.details');

    Route::get('reports/expenses', [ExpenseReportController::class, 'index'])->name('reports.expenses.index');
    Route::get('reports/expenses/details', [ExpenseReportController::class, 'details'])->name('reports.expenses.details');

    Route::get('reports/stock', [StockReportController::class, 'index'])->name('reports.stock.index');
    Route::get('reports/stock/details', [StockReportController::class, 'details'])->name('reports.stock.details');

});
