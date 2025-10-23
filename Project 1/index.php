<?php
require_once "includes/auth.php";
checkAuth();//makesure user is logged in
?>
<?php include "includes/header.php"; ?>
<h1>Dashboard</h1>
<?php if (isAdmin()): ?>
    <h2>Admin Functions</h2>
    <ul>
        <li><a href="users.php">User Management</a></li>
        <li><a href="projects.php">Project Management</a></li>
        <li><a href="bugs.php">Bug Management</a></li>
    </ul>
<?php elseif (isManager()): ?>
    <h2>Manager Functions</h2>
    <ul>
        <li><a href="projects.php">Project Management</a></li>
        <li><a href="bugs.php">Bug Management</a></li>
    </ul>
<?php else: ?>
    <h2>User Functions</h2>
    <ul>
        <li><a href="bugs.php">View Bugs</a></li>
        <li><a href="bugs.php?action=create">Report New Bug</a></li>
    </ul>
<?php endif; ?>
<?php include "includes/footer.php"; ?>