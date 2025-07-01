<?php

use Illuminate\Support\Facades\Route;
use admin\settings\Controllers\SettingManagerController;

Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {  
    Route::middleware('auth:admin')->group(function () {
        Route::resource('settings', SettingManagerController::class);
    });
});
