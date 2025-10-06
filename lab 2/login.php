<?php
session_name("BuiLab2");//session name create
session_start();

date_default_timezone_set("America/New_York");//default timezone

$validUser = "admin";   // hardcorde user
$validPass = "password"; // hardcode password

if (!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {//Check if login
    header("Location: admin.php");//redirect to admin
    exit;
}

$redirected = isset($_GET['redirected']) ? true : false;//check if redirect

if (isset($_GET['user']) && isset($_GET['password'])) {//check if user and pass in url
    $user = $_GET['user'];
    $pass = $_GET['password'];

    if ($user === $validUser && $pass === $validPass) {//if pass login success
        // correct login
        $_SESSION['loggedIn'] = true;

        $expire = time() + 600; // expire in 10 min
        $path = "/~btb4516/";
        $domain = "solace.ist.rit.edu";
        $secure = false;

        $loginTime = date("F j, Y g:i a"); // date and time
        setcookie("loggedIn", $loginTime, $expire, $path, $domain, $secure);//set cookie name with loggedIn

        header("Location: admin.php");//redirect to admin
        exit;
    } else {
        echo "<h2>Invalid Login</h2>";//if not sucess
    }
} else {
    echo "<h2>Invalid Login</h2>";//no user or pass or redirect
}
?>
