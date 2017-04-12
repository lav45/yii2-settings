<?php

$db_name = config('db_name', 'site-db');
$db_host = config('db_host', 'localhost');

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host={$db_host};dbname={$db_name}",
            'username' => config('db_username', 'root'),
            'password' => config('db_password', ''),
        ],
    ]
];