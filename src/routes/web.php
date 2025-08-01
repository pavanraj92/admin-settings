<?php

use Illuminate\Support\Facades\Route;
use admin\settings\Controllers\SettingManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::get('settings/getlogos', [SettingManagerController::class, 'getlogos'])->name('settings.getlogos');
    Route::post('settings/store-logos', [SettingManagerController::class, 'storeLogos'])->name('settings.storeLogos');
    Route::resource('settings', SettingManagerController::class);
});
