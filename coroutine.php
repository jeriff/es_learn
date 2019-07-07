<?php

//协程谁先完成io执行谁先获取执行权
// echo 123;
// go(function (){
//     defer(function () {
//         var_dump('协程接受后执行该方法1');
//     });
//     //获取协程的唯一id
//     var_dump(Swoole\Coroutine::getCid());
//     var_dump(234);
//     go(function (){
//         defer(function () {
//             var_dump('协程接受后执行该方法2');
//         });
//         var_dump(Swoole\Coroutine::getCid());
//         co::sleep(3);
//         var_dump(555);
//     });
//     co::sleep(3);
//     var_dump(111);
// });
//var_dump(567);
// go(function (){
//     defer(function () {
//         var_dump('协程接受后执行该方法3');
//     });
//     //获取协程函数调用栈。
//     $trace = Swoole\Coroutine::getBackTrace(Swoole\Coroutine::getCid());
//     var_dump($trace);
//     //var_dump(Swoole\Coroutine::getCid());
//     //var_dump(789);
//
//     //co::sleep(5);
//     //var_dump(333);
// });
//获取协程状态
//var_dump(Swoole\Coroutine::stats());
//var_dump(666);
//遍历当前进程内的所有协程
// $coros = Swoole\Coroutine::listCoroutines();
// foreach($coros as $cid)
// {
//     var_dump(Swoole\Coroutine::getBackTrace($cid));
// }


co::create(function (){
    //获取域名对应的ip
    //var_dump(co::gethostbyname("www.baidu.com", AF_INET));
    //获取域名对应的ip 返回多个
    //var_dump(co::getaddrinfo("www.baidu.com", AF_INET));
    //执行一条shell指令
    //var_dump(co::exec("md5sum ".__FILE__));
    //读取文件
    //$r =  co::readFile(__DIR__ . "/tcp_server.php");
    //var_dump($r);
    //写文件
    //$r =  co::writeFile(__DIR__ . "/test.log","hello swoole!");
    //var_dump($r);
    //获取文件系统信息
    //var_dump(co::statvfs('/'));
    //$fp = fopen(__DIR__ . "/tcp_server.php", "r");
    //fseek($fp, 256);
    //协程方式读取文件
    //$r =  co::fread($fp);
    //协程方式获取文件
    //$r =  co::fgets($fp);
    //var_dump($r);

    //$fp = fopen(__DIR__ . "/test.log", "r+");
    //$r =  co::fwrite($fp, "hello world\n", 5);
    //var_dump($r);
    //var_dump(swoole_strerror(swoole_last_error(), 9));
});
//协程主动让出执行权及返还执行权
// $cid = go(function () {
//     echo "co 1 start\n";
//     //让出执行权
//     co::yield();
//     echo "co 1 end\n";
// });
//
// go(function () use ($cid) {
//     echo "co 2 start\n";
//     co::sleep(0.5);
//     //返还执行权
//     co::resume($cid);
//     echo "co 2 end\n";
// });
exit;

$http = new swoole_http_server("0.0.0.0", 9501);

$http->on("request", function ($request, $response) {
    $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    //执行步骤
    //1 调用connect将触发协程切换
    //2 异步处理连接请求 将执行权限返回给上层 打印123 send属于协程上线文 也会跳过
    //3 继续执行打印324
    //4 recv切换回协程执行 将执行权限交给（1 等待connect返回 2 send发送数据 3 recv接受数据 4 将执行权限返回给上层）
    //5 打印789
    //6 打印ret结果
    //每一个协程内部都是 串连自上而下执行
    $client->connect("127.0.0.1", 9502, 0.5);
    var_dump(123);

    $client->send("hello world from swoole");
    //调用recv将触发协程切换
    var_dump(324);
    $ret = $client->recv();
    var_dump(789);
    var_dump($ret);

    $response->header("Content-Type", "text/plain");
    $response->end($ret);
    $client->close();

});

$http->start();