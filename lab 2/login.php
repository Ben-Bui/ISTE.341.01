<?php
session_name("Lab2Session");
session_start();

// set default timezone for cookie date
date_default_timezone_set("America/New_York");

// Hardcoded username and password
$validUser = "admin";
$validPass = "password";

// Check if user is already logged in
if (!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: admin.php");
    exit;
}

// Check if username and password are provided
if (isset($_GET['user']) && isset($_GET['password'])) {
    $user = $_GET['user'];
    $pass = $_GET['password'];

    if ($user === $validUser && $pass === $validPass) {
        // Correct login
        $_SESSION['loggedIn'] = true;

        // Set a cookie with current date/time for 10 minutes
        $loginTime = date("F j, Y g:i a");
        setcookie("loggedIn", $loginTime, time() + 600); // 600 sec = 10 min

        header("Location: admin.php");
        exit;
    } else {
        // Incorrect login
        $message = "Invalid Login";
    }
} else {
    $message = "Invalid Login";
}

// Check if redirected from admin.php without login
if (isset($_GET['message']) && $_GET['message'] === "nologin") {
    $message = "You need to log in";
}
?>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Login Page</h1>
        <?php
        if (!empty($message)) {
            echo "<p>$message</p>";
        }
        ?>
        <p>Provide username and password via URL like: <br>
        <code>?user=admin&password=password</code></p>
    </body>
</html>
