<?php
require_once "includes/auth.php";
checkAuth();//make sure user is logged in

if (!isAdmin() && !isManager()) {//only admins and managers can access
    header("Location: index.php");
    exit;
}

require_once "classes/DB.class.php";
$db = new DB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//check if form submitted
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $name = sanitizeInput($_POST['name']);
        if (!empty($name)) {
            $id = $db->insertProject($name);
            if ($id > 0) {
                $message = "Project created successfully!";
            } else {
                $message = "Error creating project.";
            }
        } else {
            $message = "Please enter a project name.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        $name = sanitizeInput($_POST['name']);
        $id = sanitizeInput($_POST['id']);
        if (!empty($name)) {
            $rows = $db->updateProject($id, $name);
            if ($rows > 0) {
                $message = "Project updated successfully!";
            } else {
                $message = "Error updating project.";
            }
        } else {
            $message = "Please enter a project name.";
        }
    }
}

$projects = $db->getAllProjects();
?>
<?php include "includes/header.php"; ?>
<h1>Project Management</h1>

<?php if ($message): ?>
    <p class="<?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<h2>Create New Project</h2>
<form method="POST" action="projects.php">
    <input type="hidden" name="action" value="create">
    <div class="form-group">
        <label>Project Name *</label>
        <input type="text" name="name" required>
    </div>
    <input type="submit" value="Create Project">
</form>

<h2>Project List</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <?php if (isAdmin()): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php if (empty($projects)): ?>
        <tr><td colspan="3">No projects found.</td></tr>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <tr>
                <td><?php echo $project['Id']; ?></td>
                <td><?php echo $project['Project']; ?></td>
                <?php if (isAdmin()): ?>
                    <td>
                        <form method="POST" action="projects.php" style="display: inline;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $project['Id']; ?>">
                            <input type="text" name="name" value="<?php echo $project['Project']; ?>" required>
                            <input type="submit" value="Update">
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<?php include "includes/footer.php"; ?>