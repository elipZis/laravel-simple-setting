<?php

use ElipZis\Setting\Commands\SyncSettingsCommand;
use ElipZis\Setting\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setting::factory()->count(10)->create();
});

it('throws no exceptions when running', function () {
    artisan(SyncSettingsCommand::class)->assertSuccessful();
});

it('exports the settings to the disc', function () {
    artisan(SyncSettingsCommand::class)->assertSuccessful();
    expect(Storage::exists('settings.json'))->toBeTrue();
});

it('has the correct settings written to the disc', function () {
    artisan(SyncSettingsCommand::class)->assertSuccessful();
    $settingsStr = Storage::get('settings.json');
    expect($settingsStr)->not()->toBeNull();
    expect($settingsStr)->toBeString();
    $settings = json_decode($settingsStr, true, 512, JSON_THROW_ON_ERROR);
    expect($settings)->toBeArray();
});
