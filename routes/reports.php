<?php

use App\Http\Controllers\customerWiseSaleReportController;
use App\Http\Controllers\customerWiseSaleReportController_2;
use App\Http\Controllers\dailycashbookController;
use App\Http\Controllers\ledgerReportController;
use App\Http\Controllers\profitController;
use App\Http\Controllers\DailyProductSalesReportController;
use App\Http\Controllers\productWiseSaleReportController;
use App\Http\Controllers\stockReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/reports/profit', [profitController::class, 'index'])->name('reportProfit');
    Route::get('/reports/profit/{from}/{to}', [profitController::class, 'data'])->name('reportProfitData');

    Route::get('/reports/dailycashbook', [dailycashbookController::class, 'index'])->name('reportCashbook');
    Route::get('/reports/dailycashbook/{date}', [dailycashbookController::class, 'details'])->name('reportCashbookData');

    Route::get('/reports/ledger', [ledgerReportController::class, 'index'])->name('reportLedger');
    Route::get('/reports/ledger/{from}/{to}/{type}', [ledgerReportController::class, 'data'])->name('reportLedgerData');

    Route::get('/reports/dailysales', [DailyProductSalesReportController::class, 'index'])->name('dailySalesReport');
    Route::get('/reports/dailysales/{date}', [DailyProductSalesReportController::class, 'data'])->name('dailySalesReportData');

    Route::get('/reports/stock_report', [stockReportController::class, 'index'])->name('stock_report');

    Route::get('/reports/product_wise_sale_report', [productWiseSaleReportController::class, 'index'])->name('productWiseSaleReport');
    Route::get('/reports/data/product_wise_sale_report', [productWiseSaleReportController::class, 'data'])->name('productWiseSaleReportData');

    Route::get('/reports/customer_wise_sale_report', [customerWiseSaleReportController::class, 'index'])->name('customerWiseSaleReport');
    Route::get('/reports/data/customer_wise_sale_report', [customerWiseSaleReportController::class, 'data'])->name('customerWiseSaleReportData');

    Route::get('/reports/customer_wise_sale_report2', [customerWiseSaleReportController_2::class, 'index'])->name('customerWiseSaleReport2');
    Route::get('/reports/data/customer_wise_sale_report2', [customerWiseSaleReportController_2::class, 'data'])->name('customerWiseSaleReportData2');

});

