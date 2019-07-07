<?php
$socket = new Co\Socket(AF_INET, SOCK_STREAM, 0);

go(function () use ($socket) {
    $retval = $socket->connect('127.0.0.1', 9506);
    while ($retval)
    {
        $n = $socket->send("hello server");
        var_dump($n);

        $data = $socket->recv();
        var_dump($data);

        // if (empty($data)) {
        //     $socket->close();
        //     break;
        // }
        //co::sleep(1.0);
    }
    var_dump($retval, $socket->errCode);
});