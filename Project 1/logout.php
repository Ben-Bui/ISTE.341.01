<?php
session_name("BugTracker");
session_start();
session_destroy();
header("Location: login.php");
exit;
?>