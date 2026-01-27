<?php

use App\Http\Controllers\BranchesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\UnitsController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
require __DIR__.'/finance.php';
require __DIR__.'/purchase.php';
require __DIR__.'/stock.php';
require __DIR__.'/sale.php';
require __DIR__.'/pos.php';
require __DIR__.'/reports.php';
require __DIR__.'/quot.php';
require __DIR__.'/todo.php';
require __DIR__.'/ajaxRequests.php';
require __DIR__.'/scrape.php';
require __DIR__.'/users.php';

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('units', UnitsController::class);
    Route::resource('categories', CategoriesController::class);
    Route::get('product/generateCode', [ProductsController::class, 'generateCode'])->name('product.generatecode');
    Route::get('product/printbarcode/{id}', [ProductsController::class, 'barcodePrint'])->name('product.barcodePrint');
    Route::resource('product', ProductsController::class);

    Route::get('/productAjax', [ProductsController::class, 'ajaxCreate']);

    Route::resource('branches', BranchesController::class);
    Route::resource('stockTransfer', StockTransferController::class);

    Route::get('stockTransfer/delete/{id}', [StockTransferController::class, 'destroy'])->name('stockTransfer.delete')->middleware(confirmPassword::class);

});
