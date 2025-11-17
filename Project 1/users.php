<?php
require_once "includes/auth.php";
checkAuth();//make sure user is logged in

if (!isAdmin()) {//only admins can access user management
    header("Location: index.php");
    exit;
}

require_once "classes/DB.class.php";
$db = new DB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//check if form submitted
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        $name = sanitizeInput($_POST['name']);
        $roleId = sanitizeInput($_POST['roleId']);
        $projectId = !empty($_POST['projectId']) ? sanitizeInput($_POST['projectId']) : null;
        
        if (!empty($username) && !empty($password) && !empty($name)) {
            $id = $db->insertUser($username, $password, $roleId, $projectId, $name);
            if ($id > 0) {
                $message = "User created successfully!";
            } else {
                $message = "Error creating user.";
            }
        } else {
            $message = "Please fill all required fields.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $rows = $db->deleteUser($_POST['id']);
        if ($rows > 0) {
            $message = "User deleted successfully!";
        } else {
            $message = "Error deleting user.";
        }
    }
}

$users = $db->getAllUsers();
$roles = $db->getRoles();
$projects = $db->getAllProjects();
?>
<?php include "includes/header.php"; ?>
<h1>User Management</h1>

<?php if ($message): ?>
    <p class="<?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<h2>Create New User</h2>
<form method="POST" action="users.php">
    <input type="hidden" name="action" value="create">
    <div class="form-group">
        <label>Username *</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Password *</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-group">
        <label>Full Name *</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-group">
        <label>Role *</label>
        <select name="roleId" required>
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['Id']; ?>"><?php echo $role['Role']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Project (for Users only)</label>
        <select name="projectId">
            <option value="">No Project</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?php echo $project['Id']; ?>"><?php echo $project['Project']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <input type="submit" value="Create User">
</form>

<h2>User List</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Role</th>
        <th>Project</th>
        <th>Actions</th>
    </tr>
    <?php if (empty($users)): ?>
        <tr><td colspan="6">No users found.</td></tr>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['Id']; ?></td>
                <td><?php echo $user['Username']; ?></td>
                <td><?php echo $user['Name']; ?></td>
                <td><?php 
                    foreach ($roles as $role) {
                        if ($role['Id'] == $user['RoleID']) {
                            echo $role['Role'];
                            break;
                        }
                    }
                ?></td>
                <td><?php 
                    if ($user['ProjectId']) {
                        foreach ($projects as $project) {
                            if ($project['Id'] == $user['ProjectId']) {
                                echo $project['Project'];
                                break;
                            }
                        }
                    } else {
                        echo 'None';
                    }
                ?></td>
                <td>
                    <form method="POST" action="users.php" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $user['Id']; ?>">
                        <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<?php include "includes/footer.php"; ?>