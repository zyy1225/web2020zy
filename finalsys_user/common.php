<?php 
class Common{
    
    public function check()
    {
        
        if($_COOKIE['token']!=null)
        {
            $str = base64_decode($_COOKIE['token']);
            //echo($str);
            $data=explode('.', $str);
            $username=$data[0];
            $password=$data[1];
            //echo($data[0]);d
            $con=mysqli_connect("localhost","websys","T4acAj7wce","websys");
            if (!$con) { 
                echo("error");
              die('数据库连接失败'.$mysql_error()); 
            } 
            mysqli_select_db($con,"websys");
            $dbusername=null; 
            $dbpassword=null; 
            //$sql = "select * from users where username = '$username'";
            $sql = "select * from websys_users where username = '$username'";
            $result = mysqli_query($con,$sql);
            while ($row=mysqli_fetch_array($result)) { 
              $dbusername=$row["username"]; 
              $dbpassword=$row["password"]; 
            } 
            if ((is_null($dbusername)) || $dbpassword!=$password ) {
                return false;
            } 
            else
                return true;
          mysqli_close($con);
        }
        else
        {
            return false;
        }
    }   
}
?>