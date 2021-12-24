<?php

namespace ElipZis\Setting\Facades;

use ElipZis\Setting\Repositories\SettingRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \ElipZis\Setting\Models\Setting|bool get(string $key)
 * @method static mixed getValue(string $key, $default = null, $devValue = null)
 * @method static \ElipZis\Setting\Models\Setting set(string $key, $value, string $type = null)
 * @method static array all()
 * @method static void storeConfig(string $filename, $data)
 *
 * @see SettingRepository
 */
class Setting extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'simple-setting';
    }
}
