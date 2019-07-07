<?php
go(function () {
    $server = new Co\Http\Server("127.0.0.1", 9502, false);
    $server->handle('/', function ($request, $response) {
        $response->end("<h1>Index</h1>");
    });
    $server->handle('/test', function ($request, $response) {
        $response->end("<h1>Test</h1>");
    });
    $server->handle('/stop', function ($request, $response) use ($server) {
        $response->end("<h1>Stop</h1>");
        $server->shutdown();
    });
    //升级为ws服务器
    $server->handle('/websocket', function ($request, $ws) {
        $ws->upgrade();
        while (true) {
            $frame = $ws->recv();
            if ($frame === false) {
                echo "error : " . swoole_last_error() . "\n";
                break;
            } else if ($frame == '') {
                break;
            } else {
                $ws->push("Hello {$frame->data}!");
                $ws->push("How are you, {$frame->data}?");
            }
        }
    });
    $server->start();
});