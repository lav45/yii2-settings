<?php

defined('APP_CONFIG') || define('APP_CONFIG', 'app-config');

/**
 * @param string $key
 * @param null $default
 * @return array|string
 */
function config($key, $default = null)
{
    static $data;

    if ($data === null) {
        $data = settings()->get(APP_CONFIG, []);
    }

    return array_key_exists($key, $data) ? $data[$key] : $default;
}

/**
 * @return \lav45\settings\Settings
 */
function settings()
{
    static $model;

    if ($model !== null) {
        return $model;
    }

    $model = new lav45\settings\Settings([
        'serializer' => false,
        'storage' => [
            'class' => 'lav45\settings\storage\PhpFileStorage',
            'path' => __DIR__ . '/settings',
        ],
    ]);

    return $model;
}