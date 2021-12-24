<?php

use ElipZis\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

//Only register our routes if wanted
if(config('simple-setting.routing.enabled')) {
    $prefix = config('simple-setting.routing.prefix');
    $middleware = config('simple-setting.routing.middleware');

    Route::group(['prefix' => $prefix, 'middleware' => $middleware], static function() use ($prefix) {
        Route::get('/{setting}', [SettingController::class, 'getSetting'])->name($prefix . '_get');
        Route::get('/value/{setting}', [SettingController::class, 'getValue'])->name($prefix . '_value');
    });
}


