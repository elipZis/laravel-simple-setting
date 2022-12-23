# Simple key/value typed settings for your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elipzis/laravel-simple-setting.svg?style=flat-square)](https://packagist.org/packages/elipzis/laravel-simple-setting)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elipzis/laravel-simple-setting/run-tests.yml?branch=main)](https://github.com/elipzis/laravel-simple-setting/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elipzis/laravel-simple-setting/php-cs-fixer.yml?branch=main)](https://github.com/elipzis/laravel-simple-setting/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elipzis/laravel-simple-setting.svg?style=flat-square)](https://packagist.org/packages/elipzis/laravel-simple-setting)

Create, store and use

* key/value settings,
* typed from numbers over dates to array,
* cached for quick access and
* automatically synchronized to a configured disc as a static json export.

Create any setting you like

```php
Setting::create([
    'key'   => 'setting.example.int',
    'type'  => 'integer',
    'value' => 336,
]);
```

and get it back, anywhere in your app

```php
$example = Setting::getValue('setting.example.int');
```

or access the statically created e.g. `settings.json` export to reduce Webserver load!

## Installation

You can install the package via composer:

```bash
composer require elipzis/laravel-simple-setting
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="simple-setting-config"
```

This is the contents of the published config file:

```php
    return [
        'repository' => [
            //The table name where to store the settings.
            'table' => 'settings',
            //The used cache configuration
            'cache' => [
                'prefix' => 'settings',
                'ttl'    => 3600
            ]
        ],
    
        'routing' => [
            //Should routes be available to access the settings?
            'enabled'    => true,
            //What path prefix to be used
            'prefix'     => 'setting',
            //Any middleware?
            'middleware' => [],
        ],
    
        'sync' => [
            //Where to statically sync the settings to
            'disc'     => env('FILESYSTEM_DRIVER', 'local'),
            //The filename to write to
            'filename' => 'settings.json',
            //Whether to automatically (re-)sync the settings to the disc with every change
            'auto'     => true
        ]
    ];
```

Before you publish the migrations, publish the config, if you would like to alter e.g. the table name.

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="simple-setting-migrations"
php artisan migrate
```

## Usage

### Creation

The following types can be used:

```php
Setting::create([
    'key'   => 'setting.example.int',
    'type'  => 'integer',
    'value' => 336,
]);
```

```php
$now = Carbon::now();
Setting::create([
    'key'   => 'setting.example.datetime',
    'type'  => 'datetime', //or date
    'value' => $now->addWeeks(2),
]);
```

```php
Setting::create([
    'key'   => 'setting.example.bool',
    'type'  => 'boolean',
    'value' => false
]);
```

```php
Setting::create([
    'key'   => 'setting.example.array',
    'type'  => 'array',
    'value' => [
        'exampleA' => 'A',
        'exampleB' => 'B',
        'exampleC' => 'C',
    ]
]);
```

```php
Setting::create([
    'key'   => 'setting.example.string',
    'type'  => 'string',
    'value' => '((x^0.5)/0.9)+10'
]);
```

### Retrieval

Return the whole model

```php
Setting::get('test');
```

which would return something like

```php
{"test":{"id":1,"key":"test","value":"test","type":"string","created_at":"2021-12-25T10:18:07.000000Z","updated_at":"2021-12-25T10:18:07.000000Z"}}
```

keyed by the `key`.

If you just need the value, call

```php
Setting::getValue('test');
```

which returns only the value, in this case `test`.

### Static export

Every change/creation of a setting is automatically updating a statically exported file, by default `settings.json` on your default filesystem disc. This should ensure a reduced Webserver load for external access by e.g. your SPA frontend so that they just need to access a for example to S3 exported CDN-cached file, without "hammering" the Webserver every time.

#### Command

Settings can/will be (re-)synced to your disc for static access automatically, if configured. You can (re-)sync these by calling the command

```php
php artisan setting:sync
```

All settings will be exported to a json file to the configured disc.

### Controller

If you have routing activated, you may access the settings via routes, e.g. `GET https://yourdomain.tld/setting/{setting}` to get a setting by key.

*Note: Routes only return values and have no `setter` endpoint!*

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [elipZis GmbH](https://elipZis.com)
- [NeA](https://github.com/nea)
- [All Contributors](https://github.com/elipZis/laravel-simple-setting/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
