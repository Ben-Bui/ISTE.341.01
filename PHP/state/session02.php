<?php
session_name("Noble6");
session_start();

?>
<html>
    <head>
        <title>Session 02</title>
    </head>
    <body>
<?php

    if (isset($_SESSION['name'])){
        //greet them by name
        echo "Hi, {$_SESSION['name']} from 
            {$_SESSION['school']}. <br />>
            See, I remembered your name!";

        unset($_SESSION['name']);//unsestting the 'name' session variable
        session_unset(); //unset all session variables but doesn't terminate the session

        //invalidate the session cookie ( with the session id)
        if(isset($_COOKIE[session_name()])){
            //the session cookie exists
            $params = session_get_cookie_params();
            setcookie(session_name(),'',1,$params['path'],
            $params['domain'],$params['secure'],$params['httponly']);
        }

        session_destroy();// actually destop session itself
        
        echo "<a href='session01.php'>Page 1</a>";
    }else{
?>
    <p>Sorry,I dont't know you!</p>
    <a href='session01.php'>Page 1</a>
<?php
    }

?>
    </body>
</html>