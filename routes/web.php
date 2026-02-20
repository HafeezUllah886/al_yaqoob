<?php

use App\Http\Controllers\BranchesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductUnitsController;
use App\Http\Controllers\UnitsController;
use App\Models\attachment;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
require __DIR__.'/finance.php';
require __DIR__.'/purchase.php';
require __DIR__.'/stock.php';
require __DIR__.'/ajaxRequests.php';
require __DIR__.'/users.php';

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('units', UnitsController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('product', ProductsController::class);
    Route::resource('product_units', ProductUnitsController::class);

    Route::resource('branches', BranchesController::class);

    Route::get('/attachment/{ref}', function ($ref) {
        $attachment = attachment::where('refID', $ref)->first();
        if (! $attachment) {
            return redirect()->back()->with('error', 'No Attachement Found');
        }

        return response()->file(public_path($attachment->path));
    })->name('viewAttachment');

});
