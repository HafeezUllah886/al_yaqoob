<?php

use App\Http\Controllers\ajaxController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/search_products', [ajaxController::class, 'searchProducts']);
    Route::get('/get_branch_accounts/{branch_id}', [ajaxController::class, 'getBranchAccounts']);
    Route::get('/get_product_units/{product_id}', [ajaxController::class, 'getProductUnits']);

});
