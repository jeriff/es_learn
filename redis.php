<?php
go(function (){
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    //$redis->setOptions(['compatibility_mode' => true]);
    // $co_get_val = $redis->get('novalue');
    // $co_zrank_val = $redis->zRank('novalue', 1);
    // $co_hgetall_val = $redis->hGetAll('hkey');
    // $co_hmget_val = $redis->hmGet('hkey', array(3, 5));
    // $co_zrange_val = $redis->zRange('zkey', 0, 99, true);
    // $co_zrevrange_val = $redis->zRevRange('zkey', 0, 99, true);
    // $co_zrangebyscore_val = $redis->zRangeByScore('zkey', 0, 99, ['withscores' => true]);
    // $co_zrevrangebyscore_val = $redis->zRevRangeByScore('zkey', 99, 0, ['withscores' => true]);
    // $val = $redis->get('key');
    // var_dump($val);
    //发布消息
    $res = $redis->publish('channel1','hello');
    var_dump($res);
    //订阅
    // if ($redis->subscribe(['channel1', 'channel2', 'channel3'])) // 或者使用psubscribe
    // {
    //     while ($msg = $redis->recv()) {
    //         // msg是一个数组, 包含以下信息
    //         // $type # 返回值的类型：显示订阅成功
    //         // $name # 订阅的频道名字 或 来源频道名字
    //         // $info  # 目前已订阅的频道数量 或 信息内容
    //         var_dump($msg);
    //         list($type, $name, $info) = $msg;
    //         if ($type == 'subscribe') // 或psubscribe
    //         {
    //             // 频道订阅成功消息，订阅几个频道就有几条
    //         }
    //         else if ($type == 'unsubscribe' && $info == 0) // 或punsubscribe
    //         {
    //             break; // 收到取消订阅消息，并且剩余订阅的频道数为0，不再接收，结束循环
    //         }
    //         else if ($type == 'message') // 若为psubscribe，此处为pmessage
    //         {
    //             // 打印来源频道名字
    //             var_dump($name);
    //             // 打印消息
    //             var_dump($info);
    //             // 处理消息
    //             // balabalaba....
    //             if (true) // 某个情况下需要退订
    //             {
    //                 $redis->unsubscribe([$name]); // 继续recv等待退订完成
    //             }
    //         }
    //     }
    // }
});