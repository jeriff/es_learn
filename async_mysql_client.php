<?php
$db = new Swoole\MySQL;
$server = array(
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '123456',
    'database' => 'test',
);

$db->connect($server, function ($db, $result) {
    $db->query("show tables", function (Swoole\MySQL $db, $result) {
        var_dump($result);
        $db->close();
    });
});