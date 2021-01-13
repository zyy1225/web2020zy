<?php 
    $username=$_POST["username"]; 
    $password=$_POST["password"];
    $phonenumber=$_POST["phonenumber"];
    $email=$_POST["email"];
    $md5_salt = "websystem2020";

    $password = md5(md5($password).$md5_salt);
    $con=mysqli_connect("localhost","websys","T4acAj7wce","websys");
    if (!$con) { 
        echo("error");
      die('数据库连接失败'.$mysql_error()); 
    } 
    mysqli_select_db($con,"websys");
    //$tmp=md5($password);
    $sql = "INSERT INTO `websys_users` (`id`, `username`, `password`,`phonenumber`, `email`) VALUES (NULL, '$username', '$password', '$phonenumber', '$email');";
    $result = mysqli_query($con,$sql);
    if (!$result) 
    {
        echo(1);
    } 
    else
    {
        echo(2);
    }
  mysqli_close($con);//关闭数据库连接，如不关闭，下次连接时会出错 
  ?> 