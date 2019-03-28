<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KairosPublisher\Kairos;

$config = [
    [
        'host'   => 'localhost',
        'port'   => 6379,
    ],
    [
        'host'   => 'localhost',
        'port'   => 6380,
    ]
];
$redis = new Kairos();
$redis->connect($config);

$channel = 'test';
$message = '{"data":"message"}';
$redis->publish($channel, $message);

