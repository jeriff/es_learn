<?php
//协程调度器
$scheduler = new Swoole\Coroutine\Scheduler;
//添加任务到调度器 不会立刻执行 start后才会执行
$scheduler->add(function () {
    Co::sleep(1);
    echo "Done.\n";
});
//添加并行任务到调度器 start后会同时启动n个fn协程 并行运行
$scheduler->parallel(10, function ($t, $n) {
    Co::sleep($t);
    echo "Co ".Co::getCid()."\n";
}, 0.05, 'A');
$res = $scheduler->start();
var_dump($res);