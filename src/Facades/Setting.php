<?php

namespace ElipZis\Setting\Facades;

use ElipZis\Setting\Repositories\SettingRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \ElipZis\Setting\Models\Setting|bool get(string $key)
 * @method static mixed getValue(string $key, mixed $default = null, mixed $devValue = null)
 * @method static \ElipZis\Setting\Models\Setting set(string $key, mixed $value, string $type = null)
 * @method static array all()
 * @method static void storeConfig(?string $filename = null, mixed $data = null)
 *
 * @see SettingRepository
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'simple-setting';
    }
}
