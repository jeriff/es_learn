<?php
use Swoole\Coroutine\Server;
use Swoole\Coroutine\Server\Connection;
go(function () {
    $server = new Server('0.0.0.0', 9601, false);
    $server->handle(function (Connection $conn) use ($server) {
        while(true) {
            $data = $conn->recv();
            $json = json_decode($data, true);
            //Assert::eq(is_array($json), $json['data'], 'hello');
            $conn->send("world\n");
        }
    });
    $server->start();
});