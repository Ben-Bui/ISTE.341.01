<!DOCTYPE html>
<html>
<head>
    <title>Bug Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        nav { background: #333; padding: 10px; margin-bottom: 20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 0 auto; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="bugs.php">Bugs</a>
            <?php if (isAdmin() || isManager()): ?>
                <a href="projects.php">Projects</a>
            <?php endif; ?>
            <?php if (isAdmin()): ?>
                <a href="users.php">Users</a>
            <?php endif; ?>
            <span style="color: white; float: right;">
                Welcome, <?php echo $_SESSION['user_name'] ?? 'Guest'; ?>
                <a href="logout.php">Logout</a>
            </span>
        </nav>