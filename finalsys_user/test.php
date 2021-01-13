 <?php

        $redis = new Redis();
    	$redis->connect('127.0.0.1', 6379);
        // $redis->auth('password'); # 如果没有密码则不需要这行
     
    // 	//把 'test'字符串存入 redis
    //     $redis->set('test_sum', 100);
        $redis->set('login_nums', 3);
        // 把 'test_name' 的 值从 redis 读取出来 
        echo $redis->get('login_nums');
?>