<?php

use App\Http\Controllers\reports\BalanceSheetReportController;
use App\Http\Controllers\reports\CashFlowReportController;
use App\Http\Controllers\reports\SalesReportController;
use App\Http\Controllers\reports\PurchasesReportController;
use App\Http\Controllers\reports\ExpenseReportController;
use App\Http\Controllers\reports\NonBusinessExpenseReportController;
use App\Http\Controllers\reports\StockReportController;
use App\Http\Controllers\reports\ProfitLossReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('reports/sales', [SalesReportController::class, 'index'])->name('reports.sales.index');
    Route::get('reports/sales/details', [SalesReportController::class, 'details'])->name('reports.sales.details');

    Route::get('reports/purchases', [PurchasesReportController::class, 'index'])->name('reports.purchases.index');
    Route::get('reports/purchases/details', [PurchasesReportController::class, 'details'])->name('reports.purchases.details');

    Route::get('reports/expenses', [ExpenseReportController::class, 'index'])->name('reports.expenses.index');
    Route::get('reports/expenses/details', [ExpenseReportController::class, 'details'])->name('reports.expenses.details');

    Route::get('reports/non_business_expenses', [NonBusinessExpenseReportController::class, 'index'])->name('reports.non_business_expenses.index');
    Route::get('reports/non_business_expenses/details', [NonBusinessExpenseReportController::class, 'details'])->name('reports.non_business_expenses.details');

    Route::get('reports/stock', [StockReportController::class, 'index'])->name('reports.stock.index');
    Route::get('reports/stock/details', [StockReportController::class, 'details'])->name('reports.stock.details');

    // Profit & Loss Report
    Route::get('/profit-loss', [ProfitLossReportController::class, 'index'])->name('reports.profit_loss.index');
    Route::get('/profit-loss/details', [ProfitLossReportController::class, 'details'])->name('reports.profit_loss.details');

    // Balance Sheet Report
    Route::get('/balance-sheet', [BalanceSheetReportController::class, 'index'])->name('reports.balance_sheet.index');
    Route::get('/balance-sheet/details', [BalanceSheetReportController::class, 'details'])->name('reports.balance_sheet.details');

    // Cash Flow Report
    Route::get('/cash-flow', [CashFlowReportController::class, 'index'])->name('reports.cash_flow.index');
    Route::get('/cash-flow/details', [CashFlowReportController::class, 'details'])->name('reports.cash_flow.details');
});
