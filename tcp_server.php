<?php
$serv = new swoole_server("127.0.0.1", 9502);
register_shutdown_function('handleFatal');
function handleFatal($serv)
{
    $error = error_get_last();
    if (isset($error['type']))
    {
        switch ($error['type'])
        {
            case E_ERROR :
            case E_PARSE :
            case E_CORE_ERROR :
            case E_COMPILE_ERROR :
                $message = $error['message'];
                $file = $error['file'];
                $line = $error['line'];
                $log = "$message ($file:$line)\nStack trace:\n";
                $trace = debug_backtrace();
                foreach ($trace as $i => $t)
                {
                    if (!isset($t['file']))
                    {
                        $t['file'] = 'unknown';
                    }
                    if (!isset($t['line']))
                    {
                        $t['line'] = 0;
                    }
                    if (!isset($t['function']))
                    {
                        $t['function'] = 'unknown';
                    }
                    $log .= "#$i {$t['file']}({$t['line']}): ";
                    if (isset($t['object']) and is_object($t['object']))
                    {
                        $log .= get_class($t['object']) . '->';
                    }
                    $log .= "{$t['function']}()\n";
                }
                if (isset($_SERVER['REQUEST_URI']))
                {
                    $log .= '[QUERY] ' . $_SERVER['REQUEST_URI'];
                }
                error_log($log);
                $serv->send($this->currentFd, $log);
            default:
                break;
        }
    }
}

//设置异步任务的工作进程数量
$serv->set(array('task_worker_num' => 4));
$serv->on('connect',function ($server,$fd,$reactorId){
    sleep(5);
});
$serv->on('receive', function($serv, $fd, $from_id, $data) {
    //投递异步任务
    $task_id = $serv->task($data);
    echo "Dispath AsyncTask: id=$task_id\n";
    $serv->send($fd,'nihao');
    // $client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
    //
    // //注册连接成功回调
    // $client->on("connect", function($cli) {
    //     $cli->send("hello world\n");
    // });
    //
    // //注册数据接收回调
    // $client->on("receive", function($cli, $data){
    //     echo "Received: ".$data."\n";
    // });
    //
    // //注册连接失败回调
    // $client->on("error", function($cli){
    //     echo "Connect failed\n";
    // });
    //
    // //注册连接关闭回调
    // $client->on("close", function($cli){
    //     echo "Connection close\n";
    // });
    //
    // //发起连接
    // $client->connect('127.0.0.1', 9501, 0.5);
});

//处理异步任务
$serv->on('task', function ($serv, $task_id, $from_id, $data) {
    echo "New AsyncTask[id=$task_id]".PHP_EOL;
    //返回任务执行的结果
    $serv->finish("$data -> OK");
});

//处理异步任务的结果
$serv->on('finish', function ($serv, $task_id, $data) {
    echo "AsyncTask[$task_id] Finish: $data".PHP_EOL;
});

$serv->start();