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
$channel = 'test';
$msg = [
    'data' => 'message',
];

$redis = new Kairos();
$redis->connect($config);
$result = $redis->publish($channel, $msg);

print_r($result);

