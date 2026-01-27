<?php

use App\Http\Controllers\RolesContoller;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('roles', RolesContoller::class);
    Route::get('users/changeStatus/{id}', [UsersController::class, 'changeStatus'])->name('users.changeStatus')->middleware(confirmPassword::class);
    Route::post('users/changePassword/{id}', [UsersController::class, 'changePassword'])->name('users.changePassword');
    Route::resource('users', UsersController::class);
});
