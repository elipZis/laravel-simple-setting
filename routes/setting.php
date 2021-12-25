<?php

use ElipZis\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

//Only register our routes if wanted
if(config('simple-setting.routing.enabled')) {
    $prefix = config('simple-setting.routing.prefix');
    $middleware = config('simple-setting.routing.middleware');

    Route::group(['prefix' => $prefix, 'middleware' => $middleware], static function() use ($prefix) {
        Route::get('/{setting}/value', [SettingController::class, 'value'])->name($prefix . '_value');
        Route::get('/{setting}', [SettingController::class, 'get'])->name($prefix . '_get');
        Route::get('/', [SettingController::class, 'all'])->name($prefix . '_all');
    });
}


