<?php
$server = new Swoole\Server('127.0.0.1', 9501);
$server->set(array(
    'worker_num' => 2,
    'task_worker_num' => 2,
    'task_async' => true
));
echo 123;
/**
 * 用户进程实现了广播功能，循环接收管道消息，并发给服务器的所有连接
 */
$process = new Swoole\Process(function($process) use ($server) {
    while (true) {
        $msg = $process->read();
        foreach($server->connections as $conn) {
            $server->send($conn, $msg);
        }
    }
});
$server->addProcess($process);


$server->on('pipeMessage', function($serv, $src_worker_id, $data) {
    echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
});
$server->on('task', function ($serv, $task_id, $reactor_id, $data){
    //var_dump($task_id, $reactor_id, $data);
    $serv->finish($data);
});

$server->on('finish', function ($serv, $fd, $reactor_id){
    var_dump('finish');
});


$server->on('receive', function ($serv, $fd, $reactor_id, $data) use ($process) {
    //群发收到的消息
    $process->write($data);
    //结束当前work进程
    //$res = $serv->stop();
    //var_dump($res);
    //关闭服务器
    //$serv->shutdown();
    //定时器
    // $serv->tick(1000, function() use ($serv, $fd) {
    //     $serv->send($fd, "hello world");
    // });
    //sendwait目前仅可用于SWOOLE_BASE模式
    //$serv->sendwait($fd, "hello worldasdasdasdsadasdsad");
    //$serv->close($fd);
    //指定连接是否存在
    //var_dump($serv->exist($fd));
    //暂停接受数据
    //$serv->pause($fd);
    //恢复接受数据
    //$serv->resume($fd);
    // if (trim($data) == 'task')
    // {
    //     $serv->task("async task coming");
    // }
    // else
    // {
    //     $worker_id = 1 - $serv->worker_id;
    //     $serv->sendMessage("hello task process", $worker_id);
    //     $task_id = $serv->task("async task coming",0);
    //     var_dump(1111);
    //     var_dump($task_id);
    // }
    //4.0.4前该方法是阻塞的
    //$serv->taskwait("taskwait coming");
    //获取客户端信息
    //var_dump($serv->getClientInfo($fd));
    //获取客户端列表并广播
    // $start_fd = 0;
    // while(true)
    // {
    //     $conn_list = $serv->getClientList($start_fd, 10);
    //     if ($conn_list===false or count($conn_list) === 0)
    //     {
    //         echo "finish\n";
    //         break;
    //     }
    //     $start_fd = end($conn_list);
    //     var_dump($conn_list);
    //     foreach($conn_list as $fd)
    //     {
    //         $serv->send($fd, "broadcast");
    //     }
    // }
    //var_dump($serv->stats());

    //批量执行投递任务
    // $tasks[] = mt_rand(1000, 9999); //任务1
    // $tasks[] = mt_rand(1000, 9999); //任务2
    // $tasks[] = mt_rand(1000, 9999); //任务3
    // var_dump($tasks);
    //
    // //等待所有Task结果返回，超时为10s
    // $results = $serv->taskWaitMulti($tasks, 10.0);
    //
    // if (!isset($results[0])) {
    //     echo "任务1执行超时了\n";
    // }
    // if (isset($results[1])) {
    //     echo "任务2的执行结果为{$results[1]}\n";
    // }
    // if (isset($results[2])) {
    //     echo "任务3的执行结果为{$results[2]}\n";
    // }

    // $tasks[0] = "hello world";
    // $tasks[1] = ['data' => 1234, 'code' => 200];
    // $result = $serv->taskCo($tasks, 0.5);
    //var_dump($result);
    //var_dump($serv->getLastError());
    //设置进程为保护状态 不受心跳检测干扰
    //var_dump($serv->protect($fd));
    //swoole 内部编号
    var_dump($serv->worker_id);
    //进程号
    var_dump($serv->worker_pid);
});

$server->on('WorkerStart', function($serv, $workerId) {
    //是否为task进程
    //var_dump($serv->taskworker);
    //var_dump(get_included_files()); //此数组中的文件表示进程启动前就加载了，所以无法reload
    //延时执行
    // $serv->defer(function() {
    //     var_dump('defer');
    // });
    //定时器
    // $serv->tick(1000, function ($id) {
    //     var_dump($id);
    // });
});

$server->start();