<?php

namespace Elipzis\Setting\Database\Factories;

use ElipZis\Setting\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Create simple integer settings
 */
class SettingFactory extends Factory {

    protected $model = Setting::class;

    public function definition(): array {
        return [
            'key'   => $this->faker->unique()->word(),
            'type'  => 'integer',
            'value' => $this->faker->unique()->numberBetween()
        ];
    }
}
