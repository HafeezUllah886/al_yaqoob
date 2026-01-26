<?php

use App\Http\Controllers\RolesContoller;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('roles', RolesContoller::class);
});
