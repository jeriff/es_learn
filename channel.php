<?php
$channel = new Swoole\Coroutine\Channel(5);
go(function()use($channel){
    //获取通道的状态
    var_dump($channel->stats());
    //通道中元素的数量
    var_dump($channel->length());
    //查看通道是否为空
    var_dump($channel->isEmpty());
    //查看通道是否已满
    var_dump($channel->isFull());
    //查看通道设置的数量
    var_dump($channel->capacity);
    //查看错误信息
    var_dump($channel->errCode);
    //关闭通道 所以排队中的生产者和消费者都将返回false
    $channel->close();
});