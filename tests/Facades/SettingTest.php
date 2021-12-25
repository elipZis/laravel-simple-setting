<?php

use Carbon\Carbon;
use ElipZis\Setting\Facades\Setting;
use ElipZis\Setting\Models\Setting as Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Model::factory()->count(10)->create();
});

it('has many settings', function () {
    $settings = Setting::all();
    expect($settings)->isNotEmpty()->toBeGreaterThanOrEqual(1);
    expect($settings->keys()->first())->toBeString();
    expect($settings->first())->not()->toBeNull();
});

it('can set and return a setting', function () {
    $setting = Setting::get('simple.setting');
    expect($setting)->toBeNull();

    Setting::set('simple.setting', 1);
    $setting = Setting::get('simple.setting');
    expect($setting)->not()->toBeNull();
    expect($setting->value)->toBe(1);
});

it('can set and return a setting value', function () {
    $setting = Setting::getValue('simple.setting.value');
    expect($setting)->toBeNull();
    $setting = Setting::getValue('simple.setting.value', 1);
    expect($setting)->toBe(1);

    Setting::set('simple.setting.value', 2);
    $setting = Setting::getValue('simple.setting.value');
    expect($setting)->not()->toBeNull()->toBe(2);
});

it('can set an array and return it', function () {
    Setting::set('simple.setting.array', ['key' => 'value']);
    $setting = Setting::getValue('simple.setting.array');
    expect($setting)->not()->toBeNull()->toBeArray()->toBe(['key' => 'value']);
});

it('can set a date and return it', function () {
    $now = Carbon::now();
    Setting::set('simple.setting.datetime', $now);
    $setting = Setting::getValue('simple.setting.datetime');
    expect($setting)->not()->toBeNull()->toBeInstanceOf(Carbon::class);
});

it('can set a string and return it', function () {
    Setting::set('simple.setting.string', 'string');
    $setting = Setting::getValue('simple.setting.string');
    expect($setting)->not()->toBeNull()->toBeString();
});
