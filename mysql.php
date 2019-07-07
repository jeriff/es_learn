<?php
go(function (){
    $db = new Swoole\Coroutine\MySQL();
    $db->connect([
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'root',
        'password' => '123456',
        'database' => 'test_db',
        'fetch_mode' => true//设置是否需要通过fetch来获取数据
    ]);
    //直接查询
    //$res = $db->query('select sleep(1)');
    //var_dump($res);
    //字符转义
    //$data = $db->escape("abc'efg\r\n");
    //预处理请求
    $stmt = $db->prepare('SELECT * FROM polls_choice WHERE id = ?');
    if ($stmt == false)
    {
        var_dump($db->errno, $db->error);
    }
    else
    {
        //
        //$ret2 = $stmt->execute(array(1));
        $ret2 = $stmt->execute(array(1));
        var_dump($stmt->fetchAll());
    }

    // $db->begin();
    // $db->query("insert into polls_choice (choice_text,votes,question_id) values ('hello',3,1)");
    // $db->commit();
});
