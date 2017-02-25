<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

new \yii\console\Application([
    'id' => 'unit',
    'basePath' => __DIR__,
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite::memory:',
        ],
        'cache' => [
            'class' => 'yii\caching\DbCache',
        ],
    ]
]);

Yii::$app->runAction('migrate/up', [
    'migrationPath' => __DIR__ . '/../migrations',
    'interactive' => 0
]);

Yii::$app->runAction('migrate/up', [
    'migrationPath' => __DIR__ . '/../vendor/yiisoft/yii2/caching/migrations',
    'interactive' => 0
]);