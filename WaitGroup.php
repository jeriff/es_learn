<?php

/**
 * 阻塞进程 等待协程返回
 * Class WaitGroup
 */
class WaitGroup
{
    private $count = 0;
    private $chan;

    /**
     * waitgroup constructor.
     * @desc 初始化一个channel
     */
    public function __construct()
    {
        $this->chan = new chan;
    }

    public function add()
    {
        $this->count++;
    }

    public function done()
    {
        $this->chan->push(true);
    }

    public function wait()
    {
        while($this->count--)
        {
            $this->chan->pop();
        }
    }

}

go(function () {
    $wg = new waitgroup();
    $result = [];

    $wg->add();
    //启动第一个协程
    go(function () use ($wg, &$result) {
        co::sleep(3);
        //启动一个协程客户端client，请求淘宝首页
        $cli = new Swoole\Coroutine\Http\Client('www.taobao.com', 443, true);
        $cli->setHeaders([
            'Host' => "www.taobao.com",
            "User-Agent" => 'Chrome/49.0.2587.3',
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip',
        ]);
        $cli->set(['timeout' => 1]);
        $cli->get('/index.php');

        $result['taobao'] = $cli->body;
        $cli->close();

        $wg->done();
    });

    $wg->add();
    //启动第二个协程
    go(function () use ($wg, &$result) {
        co::sleep(3);
        //启动一个协程客户端client，请求百度首页
        $cli = new Swoole\Coroutine\Http\Client('www.baidu.com', 443, true);
        $cli->setHeaders([
            'Host' => "www.baidu.com",
            "User-Agent" => 'Chrome/49.0.2587.3',
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip',
        ]);
        $cli->set(['timeout' => 1]);
        $cli->get('/index.php');

        $result['baidu'] = $cli->body;
        $cli->close();

        $wg->done();
    });

    //挂起当前协程，等待所有任务完成后恢复
    $wg->wait();
    //这里 $result 包含了 2 个任务执行结果
    //var_dump($result);
});