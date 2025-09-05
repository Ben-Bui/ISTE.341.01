<?php
session_name("Noble6");
session_start();

//check to see which visit this is
//if I've been here before, go to session02.php
if(!empty($_SESSION['name'])){
    //add a session variable
    $_SESSION['school'] = "RIT";
    $_SESSION['count']++;
    header("Location: session02.php");
    exit;//or die
}
?>
<html>
    <head>
        <title>Session 01</title>
    </head>
    <body>

<?php
    //check to see if 'count' session variables is set
    if (isset($_SESSION['count'])){
        //it exists
        echo"<h1>Hi, you've been here {$_SESSION['count']} times.</h1>";
        $_SESSION['count']++;
    }else{
        echo"<h1>Hi, you haven't been here before!</h1>";
        $_SESSION['count'] = 0;
    }
    $_SESSION['name'] = "Noble Team";
    echo "<h2><a href='session01.php'>Come Back!</a></h2>";

?>
    </body>
</html>