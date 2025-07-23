<?php

use Illuminate\Support\Facades\Route;
use admin\settings\Controllers\SettingManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('settings', SettingManagerController::class);
});
