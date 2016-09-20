yii2-settings
===================

[![Latest Stable Version](https://poser.pugx.org/lav45/yii2-settings/v/stable)](https://packagist.org/packages/lav45/yii2-settings)
[![License](https://poser.pugx.org/lav45/yii2-settings/license)](https://packagist.org/packages/lav45/yii2-settings)
[![Total Downloads](https://poser.pugx.org/lav45/yii2-settings/downloads)](https://packagist.org/packages/lav45/yii2-settings)

This extension helps you to easily store and retrieve data for your application.

## Installation

The preferred way to install this extension through [composer](http://getcomposer.org/download/).

You can set the console

```
$ composer require "lav45/yii2-settings" --prefer-dist
```

or add

```
"lav45/yii2-settings": "1.0.*"
```

in ```require``` section in `composer.json` file.

### migrate

[migrations/m160426_220525_settings.php](migrations/m160426_220525_settings.php)

Apply with the console command:
```
~$ yii migrate/up --migrationPath=vendor/lav45/yii2-settings/migrations
```

## Component Setup

To use the Setting Component, you need to configure the components array in your application configuration:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'lav45\settings\Settings',
            'as cache' => [
                'class' => 'lav45\settings\behaviors\CacheBehavior',
            ],
            'as quick_access' => [
                'class' => 'lav45\settings\behaviors\QuickAccessBehavior',
            ],
            'as context' => [
                'class' => 'lav45\settings\behaviors\ContextBehavior',
            ],
        ]
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

// Get not exist key
$settings->get('array.options.img'); // => null

// Get default value if key exist
$settings->get('array.options.imgs', []); // => []
```

### ContextBehavior

This extension allows to retrieve data depending on the context. For example, depending on the selected language.

```php
$settings = Yii::$app->settings;

$settings->context('en-US')->set('key', ['data']); // => true

$settings->context('en-US')->get('key'); // => ['data']

$settings->context('ru-RU')->get('key'); // => null
```

## License

**yii2-settings** it is available under a BSD 3-Clause License. Detailed information can be found in the `LICENSE.md`.
