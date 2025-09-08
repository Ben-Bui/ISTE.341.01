<?php
session_name("BuiLab2");
session_start();

date_default_timezone_set("America/New_York");

$validUser ="admin";
$ValidPass = "password";

if (!empty($_SESSION['loggedIn']) &&$_SESSION['loggedIn'] ===true){
    header("location: admin.php");
    exit;
}

if (isset($_GET['user']) && isset($_GET['password'])){
    $user = $_GET['user'];
    $pass = $_GET['password'];
}
?>