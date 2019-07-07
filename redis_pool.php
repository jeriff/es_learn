<?php
//channel只能在协程内部使用
class RedisPool
{
    /**
     * @var \Swoole\Coroutine\Channel
     */
    protected $pool;

    /**
     * RedisPool constructor.
     * @param int $size 连接池的尺寸
     */
    function __construct($size = 100)
    {
        $this->pool = new Swoole\Coroutine\Channel($size);
        for ($i = 0; $i < $size; $i++)
        {
            $redis = new Swoole\Coroutine\Redis();
            $res = $redis->connect('127.0.0.1', 6379);
            if ($res == false)
            {
                throw new RuntimeException("failed to connect redis server.");
            }
            else
            {
                $this->put($redis);
            }
        }
    }

    function put($redis)
    {
        $this->pool->push($redis);
    }

    function get()
    {
        return $this->pool->pop();
    }
}
$channel = new Swoole\Coroutine\Channel(5);
go(function()use($channel){
    $pool = new RedisPool(5);
    $channel->push($pool);
});
go(function ()use($channel){
    var_dump($channel->pop()->get());
});
