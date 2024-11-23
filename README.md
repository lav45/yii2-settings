yii2-settings
===================

[![Latest Stable Version](https://poser.pugx.org/lav45/yii2-settings/v/stable)](https://packagist.org/packages/lav45/yii2-settings)
[![License](https://poser.pugx.org/lav45/yii2-settings/license)](https://packagist.org/packages/lav45/yii2-settings)
[![Total Downloads](https://poser.pugx.org/lav45/yii2-settings/downloads)](https://packagist.org/packages/lav45/yii2-settings)
[![Code Coverage](https://scrutinizer-ci.com/g/lav45/yii2-settings/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lav45/yii2-settings/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lav45/yii2-settings/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lav45/yii2-settings/)
[![Code Climate](https://codeclimate.com/github/LAV45/yii2-settings/badges/gpa.svg)](https://codeclimate.com/github/LAV45/yii2-settings)

This extension is very useful for storing any settings, for your application.

## Installation

The preferred way to install this extension through [composer](http://getcomposer.org/download/).

You can set the console

```
~$ composer require lav45/yii2-settings
```

or add

```
"lav45/yii2-settings": "1.3.*"
```

in ```require``` section in `composer.json` file.

### Migrate

Apply with the console command:
```
~$ yii migrate/up --migrationPath=vendor/lav45/yii2-settings/migrations
```

or add it to your console config file ( console/config/main.php )

```php
return [
    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@app/migrations',
                '@vendor/lav45/yii2-settings/migrations',
            ],
        ],
    ],
];
```

## Component Setup

To use the Setting Component, you need to configure the components array in your application configuration:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'lav45\settings\Settings',

            // You can add an arbitrary prefix to all keys
            // 'keyPrefix' => 'key_',

            // DbStorage use as default storage
            // 'storage' => [
            //    'class' => 'lav45\settings\storage\DbStorage',
            //    'tableName' => '{{%settings}}',
            //    'db' => 'db',
            // ],

            'as cache' => [
                'class' => 'lav45\settings\behaviors\CacheBehavior',
                // 'cache' => 'cache',
                // 'cacheDuration' => 3600,
            ],
            'as access' => [
                'class' => 'lav45\settings\behaviors\QuickAccessBehavior',
            ],
            'as context' => [
                'class' => 'lav45\settings\behaviors\ContextBehavior',
            ],
        ],

        /**
         * FileStorage this is the adapter allows you to store your settings in a simple file
         */
        'configFile' => [
            'class' => 'lav45\settings\Settings',

            // Be used for data serialization
            'serializer' => ['serialize', 'unserialize'],

            'storage' => [
                'class' => 'lav45\settings\storage\FileStorage',
                // it is desirable to determine the storage location 
                // of your configuration files in a convenient place
                // 'path' => '@runtime/settings',
                // 'dirMode' => 0755,
                // 'fileSuffix' => '.bin',
            ],
        ],

        /**
         * PhpFileStorage this is an adapter to store data in php file
         * the serializer can be disabled to increase performance
         */
        'configPhpFile' => [
            'class' => 'lav45\settings\Settings',
            'serializer' => false,
            'storage' => [
                'class' => 'lav45\settings\storage\PhpFileStorage',
                // 'path' => '@runtime/settings',
                // 'fileSuffix' => '.php',
            ],
        ],
    ]
];
```

## Using

### Can default 

```php
$settings = Yii::$app->settings;

// Get not exist key
$settings->get('key'); // => null

// Get default value if key exist
$settings->get('key', []); // => []

// Save and get data
$settings->set('array', ['data']); // => true
$settings->get('array'); // => [0 => 'data']

$settings->set('object', new User()); // => true
$settings->get('object'); // => User

$settings->set('float', 123.5); // => true
$settings->get('float'); // => 123.5

$settings->set('integer', 0); // => true
$settings->get('integer'); // => 0

$settings->set('bool', false); // => true
$settings->get('bool'); // => false

$settings->set('string', 'text'); // => true
$settings->get('string'); // => text

$settings->set('null', null); // => true
$settings->get('null'); // => null

// delete data by key
$settings->delete('array'); // => true

// Use as array
$settings['array'] = ['data'];

print_r($settings['array']); // => [0 => 'data']

isset($settings['array']) // => true

unset($settings['array']);
```

### CacheBehavior

The extension, which will help to speed up data loading by caching.
If the data changes, the cache will be updated automatically.

To clean the cache, you can use this method
```php
Yii::$app->settings->cache->flush(); // => true
```

### QuickAccessBehavior

This extension allows you to quickly obtain the necessary data from a multidimensional array

```php
// Getting data from multidimensional array
$data = [
    'options' => [
        'css' => ['bootstrap.css'],
        'js' => ['jquery', 'bootstrap.js']
    ]
];
$settings = Yii::$app->settings;

// Save data
$settings->set('array', $data); // => true

// Get data by key
$settings->get('array.options.js'); // => ['jquery', 'bootstrap.js']

// Use as array
print_r($settings['array.options.js']); // => ['jquery', 'bootstrap.js']
print_r($settings['array']['options']['js']); // => ['jquery', 'bootstrap.js']

// Get not exist key
$settings->get('array.options.img'); // => null

// Get default value if key exist
$settings->get('array.options.imgs', []); // => []

// Replace value by path key 
$settings->replace('array', 'options.js', ['jquery']);
$settings->replace('array', 'options.img', ['icon.jpg', 'icon.png']);
$settings->get('array.options.js'); // => ['jquery']
$settings->get('array');
/**
 * [
 *     'options' => [
 *         'css' => ['bootstrap.css'],
 *         'js' => ['jquery'],                  // rewrite
 *         'img' => ['icon.jpg', 'icon.png'],   // added
 *     ]
 * ]
 */
```

### ContextBehavior

This extension allows to retrieve data depending on the context. For example, depending on the selected language.

```php
$settings = Yii::$app->settings;

$settings->context('en-US')->set('key', ['data']); // => true

$settings->context('en-US')->get('key'); // => ['data']

$settings->context('ru-RU')->get('key'); // => null
```

### Practical use: you can see in

* [SettingsForm](examples/SettingsForm)
* [ProjectConfiguration](https://github.com/LAV45/yii2-project-configuration)

## License

**yii2-settings** it is available under a BSD 3-Clause License. Detailed information can be found in the `LICENSE.md`.
