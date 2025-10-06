<?php
session_name("BuiLab2");//session name
session_start();

date_default_timezone_set("America/New_York");//timezone

if (empty($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {//if no valid session back to login
    header("Location: login.php?redirected=1");
    exit;
}

if (isset($_COOKIE['loggedIn'])) {//if cookie exists show them log in
    echo "<h2>You logged in {$_COOKIE['loggedIn']}</h2>";
}

unset($_SESSION['loggedIn']);//remove all session
session_unset();

if (isset($_COOKIE[session_name()])) {//delet session cookie with session ID
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

$path = "/~btb4516/";//tell browser which folder the cookie is balid
$domain = "solace.ist.rit.edu";//which domain
$secure = false;//only https cuz testing
setcookie("loggedIn", "", 1, $path, $domain, $secure);//forece loggin cookie expire
?>
