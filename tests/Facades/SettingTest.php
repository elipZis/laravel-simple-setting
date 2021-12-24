<?php

use ElipZis\Setting\Facades\Setting;
use ElipZis\Setting\Models\Setting as Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    Model::factory()->count(10)->create();
});

it('has many settings', function() {
    $settings = Setting::all();
    $this->assertNotEmpty($settings);
    $this->assertGreaterThanOrEqual(1, count($settings));

    $this->assertIsString($settings->keys()->first());
    $this->assertNotNull($settings->first());
});

it('can return a setting', function() {
    $setting = Setting::get('simple.setting');
    $this->assertFalse($setting);

    Setting::set('simple.setting', 1);
    $setting = Setting::get('simple.setting');
    $this->assertNotNull($setting);
    $this->assertEquals(1, $setting->value);
});

it('can return a setting value', function() {
    $setting = Setting::getValue('simple.setting.value');
    $this->assertNull($setting);
    $setting = Setting::getValue('simple.setting.value', 1);
    $this->assertEquals(1, $setting);

    Setting::set('simple.setting.value', 2);
    $setting = Setting::getValue('simple.setting.value');
    expect($setting)->not()->toBeNull();
    expect($setting)->toBe(2);
});
