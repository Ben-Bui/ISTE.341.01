<?php
session_name("Lab2Session");
session_start();

// Check if user is logged in
if (empty($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // redirect back to login.php with message
    header("Location: login.php?message=nologin");
    exit;
}

// Display login cookie value
if (isset($_COOKIE['loggedIn'])) {
    echo "<h2>You logged in {$_COOKIE['loggedIn']}</h2>";
}

// Destroy session and unset cookies
$_SESSION = [];
session_unset();
session_destroy();

if (isset($_COOKIE[session_name()])) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

if (isset($_COOKIE['loggedIn'])) {
    setcookie('loggedIn', '', time() - 3600); // expire the cookie
}

echo "<p>Session and cookies cleared.</p>";
echo "<a href='login.php'>Back to Login</a>";
?>
