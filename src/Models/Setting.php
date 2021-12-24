<?php

namespace ElipZis\Setting\Models;

use DateTime;
use ElipZis\Setting\Repositories\SettingRepository;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Always set the "type" BEFORE the "value" when creating
 * as eloquent casts the attributes in order!
 *
 * For example:
 * ```
 * Setting::create([
 *   'key'   => 'setting.example.int',
 *   'type'  => 'integer',
 *   'value' => 336,
 * ]);
 * ```
 *
 * @property string $key
 * @property string $type
 * @property mixed  $value
 */
class Setting extends Model {

    use HasFactory, MassPrunable;

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'type'
    ];
    /**
     * The possible cast types
     */
    public const TYPES = [
        'string'   => 'Text',
        'integer'  => 'Integer',
        'double'   => 'Double',
        'date'     => 'Date',
        'datetime' => 'DateTime',
        'boolean'  => 'Boolean',
        'array'    => 'JSON',
    ];

    /**
     * The value field must be setup as "castable"
     * otherwise the getCastType method wouldn't be called.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'string'
    ];

    /**
     *
     */
    protected static function boot() {
        parent::boot();

        $func = static function(Setting $model) {
            if(isset($model->original['value']) && $model->value !== $model->original['value']) {
                Cache::forget(SettingRepository::getCacheKey($model->key));
                if(config('simple-setting.sync.auto')) {
                    \ElipZis\Setting\Facades\Setting::storeConfig('settings.json', \ElipZis\Setting\Facades\Setting::all());
                }
            }
        };

        //Clear this setting from the cache if updated
        self::updated($func);
        self::saved($func);
    }

    /**
     * Overriding the default attribute setter, to resolve JSON/Array mismatches
     *
     * @param $value
     * @return $this|Setting
     */
    public function setValueAttribute($value) {
        $key = 'value';

        // If an attribute is listed as a "date", we'll convert it from a DateTime
        // instance into a form proper for storage on the database tables using
        // the connection grammar's date format. We will auto set the values.
        if($value && $this->isDateAttribute($key)) {
            $value = $this->fromDateTime($value);
        }

        if($this->isJsonCastable($key) && !is_null($value)) {
            if(is_string($value)) {
                $value = json_decode(json_encode($value), true);
            } else {
                $value = $this->castAttributeAsJson($key, $value);
            }
        }

        // If this attribute contains a JSON ->, we'll set the proper value in the
        // attribute's underlying array. This takes care of properly nesting an
        // attribute in the array's value in the case of deeply nested items.
        if(Str::contains($key, '->')) {
            return $this->fillJsonAttribute($key, $value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Trying to cast by our configured type,
     * otherwise have eloquent take over
     *
     * @param string $key
     * @return mixed|string
     */
    protected function getCastType($key) {
        if($key === 'value' && !empty($this->type)) {
            return $this->type;
        }
        return parent::getCastType($key);
    }

    /**
     * @param Builder $query
     * @param string  $key
     * @return Builder
     */
    public function scopeKey(Builder $query, string $key) {
        return $query->where('key', '=', $key);
    }

    /**
     * Trying to derive the values type by simply checking
     *
     * @param $value
     * @return string
     */
    public static function getType($value) {
        if($value instanceof DateTime) {
            return 'datetime';
        }
        $type = gettype($value);
        if(isset(static::TYPES[$type])) {
            return $type;
        }
        if($type === 'object') {
            return 'array';
        }
        return 'string';
    }

    /**
     * @return Repository|Application|mixed|string
     */
    public function getTable() {
        return config('simple-setting.repository.table');
    }
}
