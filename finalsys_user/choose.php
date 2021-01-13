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
?>
<html lang="zh-ch">
	<head>
		<meta charset="utf-8">
		<title>商品秒杀</title>
	</head>
 
	<body>
        <form action="home.php">
	    <table border="dotted" align="center" style="width:300px; height:20px;">
        <tr align="center">
            <td>
            <button  name="select">进入购买界面</button>
            </td>
        </tr>
        </table>
        </form>
        <form action="result.php">
	    <table border="dotted" align="center" style="width:300px; height:20px;">
        <tr align="center">
            <td>
            <button  name="select">查询购买情况</button>
            </td>
        </tr>
        </table>
        </form>
        <form action="logout.php">
	    <table border="dotted" align="center" style="width:300px; height:20px;">
        <tr align="center">
            <td>
            <button  name="select">退出登录</button>
            </td>
        </tr>
        </table>
        </form>
    </body>
</html> 
