<?php
session_name("Noble");
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
        echo "<a href='session01.php'>Page 1</a>"
    }else{
?>
    <p>Sorry,I dont't know you!</p>
    <a href='session01.php'>Page 1</a>
<?php
}

?>
    </body>
</html>