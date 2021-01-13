<?php 
    //向GO post数据
    function http($post_data)
    {
        
        $url='http://localhost:9000/Phpsend';
    
        //操作执行
        $postdata = http_build_query($post_data);
        $options = array(
        'http' => array(
          'method' => 'POST',
          'header' => 'Content-type:application/x-www-form-urlencoded',
          'content' => $postdata,
          'timeout' => 15 * 60 // 超时时间（单位:s）
        )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    }
 
    //先处理表单数据
    $id=$_COOKIE['id'];
    $num=$_POST['num'];
    $username=$_POST['username'];
    $phonenumber=$_POST['phonenumber'];
    $address=$_POST['address'];
    $remark=$_POST['remark'];
    $post_data = array(
      'id' => $id,
      'num' => $num,
      'username' => $username,
      'phonenumber' => $phonenumber,
      'address' => $address,
      'remark' => $remark
    );
    //检验是否超出库存
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $rabbit_nums=$redis->get('rabbit_nums');
    if($rabbit_nums>=$num)
    {
        //验证该ID是否已经购买过
        //每个ID只允许购买一次
        $con=mysqli_connect("localhost","websys","T4acAj7wce","websys");
        if (!$con) { 
            echo("error");
          die('数据库连接失败'.$mysql_error()); 
        } 
        mysqli_select_db($con,"websys");
        $sql = "select * from websys_purchaser_info where id = '$id'";
        $result = mysqli_query($con,$sql);
        $dbusername=null;
        while ($row=mysqli_fetch_array($result)) { 
          $dbusername=$row["username"]; 
        } 
        if (is_null($dbusername)) {
            http($post_data);
            $redis->set('rabbit_nums', $rabbit_nums-$num);
            echo(1);
        } 
        else
        {
            echo(2);
        }
    }
    else
    {
        echo(3);
    }
    ?> 