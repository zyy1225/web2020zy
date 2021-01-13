<!DOCTYPE html>
<?php 
    require_once 'common.php';
    $obj=new Common();
    $res=$obj->check();
//   echo($res);
    if($res==false)
    {
        ?> 
        <script type="text/javascript">
        window.location.href="/finalsys_user/index.php";
        </script> 
        <?php 
    }
    $con=mysqli_connect("localhost","websys","T4acAj7wce","websys");
    $id=$_COOKIE['id'];
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
        ?> 
        <script type="text/javascript">
        window.location.href="/finalsys_user/result/failed.html";
        </script> 
        <?php  
    } 
    else
    {
        ?> 
        <script type="text/javascript">
        window.location.href="/finalsys_user/result/success.html";
        </script> 
        <?php  
    }
?>
<html lang="zh-ch">
	<head>
		<meta charset="utf-8">
		<title>商品秒杀结果查询</title>
	</head>
	<body>
        <p>{$dbusername}<p>
    </body>
</html> 
