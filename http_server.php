<?php
$http = new swoole_http_server("0.0.0.0", 9503);

$http->on('request', function ($request, $response) {
    $response->header("Content-Type", "text/html; charset=utf-8");
    $db = new Swoole\Coroutine\MySQL();
    $db->connect([
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '123456',
        'database' => 'mysql',
    ]);
    $data = $db->query('select * from db');
    $response->end(json_encode($data));
});

$http->start();
