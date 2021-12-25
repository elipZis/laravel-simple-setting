<?php

namespace ElipZis\Setting\Repositories;

use Closure;
use ElipZis\Setting\Models\Setting;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use JsonException;

/**
 * The handler between facade and repository, where the settings are stored
 */
class SettingRepository {
    /**
     * Save the given data to a json config file on a configured disc.
     * If no filename is given, it falls back to the config or a default `settings.json`.
     * If no data is given, all settings are exported.
     *
     * @param string|null $filename
     * @param mixed|null  $data
     * @throws JsonException
     */
    public function storeConfig(?string $filename = null, mixed $data = null) {
        $filename = $filename ?? config('simple-setting.sync.filename') ?? 'settings.json';
        Storage::disk(config('simple-setting.sync.disc'))->put(
            $filename,
            json_encode($data ?? $this->all(), JSON_THROW_ON_ERROR),
            Filesystem::VISIBILITY_PUBLIC
        );
    }

    /**
     * Return all settings from the database, keyed by the setting key
     *
     * @return mixed[string]
     */
    public function all() {
        return $this->remember('ALL', static function() {
            return Setting::query()->get(['key', 'value', 'type'])->keyBy('key')->map(static function($item) {
                return $item['value'];
            });
        });
    }

    /**
     * Return a full setting model
     *
     * @param string $key
     * @return Setting|null
     */
    public function get(string $key): ?Setting {
        return $this->getSetting($key);
    }

    /**
     * Get a setting model
     *
     * @param string $key
     * @return Setting|null
     */
    public function getSetting(string $key): ?Setting {
        return $this->remember($key, static function() use ($key) {
            return Setting::query()->key($key)->first();
        });
    }

    /**
     * Get a setting value
     *
     * @param string     $key
     * @param mixed|null $default
     * @param mixed|null $devValue
     * @return mixed
     */
    public function getValue(string $key, mixed $default = null, mixed $devValue = null) {
        //For testing purposes, allow to pass a dev value, returned in local dev environments
        if($devValue && App::environment(['local', 'development', 'dev'])) {
            return $devValue;
        }

        $setting = $this->getSetting($key);
        if($setting) {
            return $setting->value;
        }

        return $default;
    }

    /**
     * Set or create a new setting value
     *
     * @param string      $key   Where to store
     * @param mixed       $value What to store
     * @param string|null $type  The forced value type, or the type will be derived
     * @return Setting
     */
    public function set(string $key, mixed $value, string $type = null): Setting {
        $setting = Setting::firstOrNew([
            'key' => $key,
        ]);
        $setting->type = $type ?? Setting::getType($value);
        $setting->value = $value;
        $setting->save();

        return $setting;
    }

    /**
     * @param string  $cacheKey
     * @param Closure $func
     * @return mixed
     */
    protected function remember(string $cacheKey, Closure $func) {
        //Remember, remember, the closure you're given...
        return Cache::remember(static::getCacheKey($cacheKey), config('simple-setting.cache.ttl'), $func);
    }

    /**
     * @param string $key
     * @return string
     */
    public static function getCacheKey(string $key) {
        return static::getCachePrefix() . '_' . $key;
    }

    /**
     * @return Repository|Application|mixed
     */
    public static function getCachePrefix() {
        return config('simple-setting.cache.prefix');
    }
}
