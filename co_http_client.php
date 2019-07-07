<?php
go(function(){
    $cli = new Swoole\Coroutine\Http\Client('127.0.0.1', 9502);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => '*',
        'Accept-Encoding' => 'gzip',
    ]);

    $cli->set([ 'timeout' => 1]);
    //与recv配合使用 直接跳出 后面的get暂时不接受返回
    //$cli->setDefer();
    //$cli->get('/');
    //获取返回数据
    //$data = $cli->recv();
    //var_dump($cli->body);
    //下载文件
    //$res = $cli->download('/20140113/8800276_184927469000_2.png', __DIR__ . '/logo.png');
    //var_dump($res);
    //$cli->close();


    // //升级为ws连接
    $ret = $cli->upgrade("/websocket");
    if ($ret) {
        while(true) {
            $cli->push("hello");
            var_dump($cli->recv());
            co::sleep(0.1);
        }
    }
});
