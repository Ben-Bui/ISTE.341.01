<?php
require_once "classes/DB.class.php";
require_once "includes/auth.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//check if form submitted
    $db = new DB();
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    $user = $db->getUserByUsername($username);//get user from database
    
    if ($user && hash('sha256', $password) === $user['Password']) {//verify password 
        $_SESSION['user_id'] = $user['Id'];//store user ID 
        $_SESSION['user_name'] = $user['Name'];//store user name 
        $_SESSION['role_id'] = $user['RoleID'];//store role ID 
        $_SESSION['project_id'] = $user['ProjectId'];//store project ID 
        header("Location: index.php");//redirect to dashboard
        exit;
    } else {
        $error = "Invalid username or password";//login failed
    }
}
?>
<?php include "includes/header.php"; ?>
<h1>Bug Tracker Login</h1>
<?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<form method="POST" action="login.php">
    <div class="form-group">
        <label>Username:</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-group">
        <input type="submit" value="Login">
    </div>
</form>
<?php include "includes/footer.php"; ?>