<?php
//每隔2000ms触发一次
$id1 = swoole_timer_tick(2000, function ($timer_id) {
    echo "tick-2000ms\n";
});

//3000ms后执行此函数
$id2 = swoole_timer_after(3000, function () {
    echo "after 3000ms.\n";
});

var_dump($id1,$id2);
//sleep(10);
//清除定时器
swoole_timer_clear($id1);
swoole_timer_clear($id2);
