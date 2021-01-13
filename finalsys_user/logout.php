<?php 
    session_start();
    session_destroy();
    setcookie("username", "", time()-15*60);
    setcookie("token", "", time()-15*60);
    setcookie("phonenumber","",time()-15*60);
    setcookie("id","",time()-15*60);
    ?> 
        <script type="text/javascript">
        window.location.href="/finalsys_user/index.php";
        </script> 
    <?php 
?> 