<?php 
  // 获取payload json数据，转换成数组形式
    session_start();
    $lifeTime = 24*3600;
    setcookie(session_name(), session_id(), time() + $lifeTime, "/");
    $username=$_POST["username"]; 
    $password=$_POST["password"];
    $con=mysqli_connect("localhost","websys","T4acAj7wce","websys");
    if (!$con) { 
        echo("error");
        die('数据库连接失败'.$mysql_error()); 
    } 
    mysqli_select_db($con,"websys");
    $dbusername=null; 
    $dbpassword=null; 
    $dbphonenumber=null;
    $dbid=null;
    //$sql = "select * from users where username = '$username'";
    $sql = "select * from websys_users where username = '$username'";
    $result = mysqli_query($con,$sql);
    while ($row=mysqli_fetch_array($result)) { 
      $dbusername=$row["username"]; 
      $dbpassword=$row["password"]; 
      $dbphonenumber=$row["phonenumber"];
      $dbid=$row["id"];
    } 
    if (is_null($dbusername)) {
        echo(1);
    } 
    else { 
        //$tmp=md5($password);
    $md5_salt = "websystem2020";

    if ((md5(md5($password).$md5_salt)) != $dbpassword) { 
        echo($dbpassword);
          echo(2);
       echo(md5(md5($password)).$md5_salt);
      } 
      else {
          session_destroy();
          session_start();
          $_SESSION["username"]=$username; 
          $input = $username.".".$dbpassword;
          $token = base64_encode($input);
          setcookie("token", $token, time()+15*60);
          setcookie("username",$username,time()+15*60);
          setcookie("phonenumber",$dbphonenumber,time()+15*60);
          setcookie("id",$dbid,time()+15*60);
          echo(3);
      } 
    } 
  mysqli_close($con);//关闭数据库连接，如不关闭，下次连接时会出错 
    
  ?> 