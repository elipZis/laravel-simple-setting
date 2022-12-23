<?php

use ElipZis\Setting\Http\Controllers\SettingController;
use ElipZis\Setting\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setting::factory()->count(10)->create();
    Setting::create([
        'key' => 'test.settingcontroller',
        'type' => 'string',
        'value' => 'test',
    ]);
    Route::get('/{setting}/value', [SettingController::class, 'value']);
    Route::get('/{setting}', [SettingController::class, 'get']);
    Route::get('/', [SettingController::class, 'all']);
});

it('will return all settings', function () {
    $retVal = getJson('/')->assertSuccessful()->json();
    expect($retVal)->toBeArray()->toHaveCount(1)->toHaveKey('settings');
    expect($retVal['settings'])->toHaveCount(11)->toHaveKey('test.settingcontroller');
});

it('will return a single setting model', function () {
    $retVal = getJson('/test.settingcontroller')->assertSuccessful()->json();
    expect($retVal)->toBeArray()->toHaveCount(1)->toHaveKey('test.settingcontroller');
    expect($retVal['test.settingcontroller'])->toBeArray()->toHaveKey('key')->toHaveKey('value');
    expect($retVal['test.settingcontroller']['key'])->toBe('test.settingcontroller');
    expect($retVal['test.settingcontroller']['value'])->toBe('test');
});

it('will return a single setting value', function () {
    $retVal = getJson('/test.settingcontroller/value')->assertSuccessful()->json();
    expect($retVal)->toBeArray()->toHaveCount(1)->toHaveKey('test.settingcontroller');
    expect($retVal['test.settingcontroller'])->toBe('test');
});
