<?php
require_once "includes/auth.php";
checkAuth();//make sure user is logged in
require_once "classes/DB.class.php";

$db = new DB();
$action = $_GET['action'] ?? 'list';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//check if form submitted
    if ($action == 'create') {
        $bugData = [
            'projectId' => sanitizeInput($_POST['projectId']),
            'ownerId' => $_SESSION['user_id'],//current user is the owner
            'assignedToId' => !empty($_POST['assignedToId']) ? sanitizeInput($_POST['assignedToId']) : null,
            'statusId' => (!empty($_POST['assignedToId']) && $_POST['assignedToId'] != '') ? 2 : 1, // check if bug assign
            'priorityId' => !empty($_POST['priorityId']) ? sanitizeInput($_POST['priorityId']) : 2, // big priority to medium default
            'summary' => sanitizeInput($_POST['summary']),
            'description' => sanitizeInput($_POST['description']),
            'targetDate' => !empty($_POST['targetDate']) ? sanitizeInput($_POST['targetDate']) : null
        ];
        
        // check date
        if (!empty($bugData['targetDate']) && !validateFutureDate($bugData['targetDate'])) {
            $message = "Error: Target date must be in the future.";
        } elseif (!empty($bugData['summary']) && !empty($bugData['description']) && !empty($bugData['projectId'])) {
            $id = $db->insertBug($bugData);
            if ($id > 0) {
                $message = "Bug reported successfully!";
                $action = 'list';
            } else {
                $message = "Error reporting bug.";
            }
        } else {
            $message = "Please fill all required fields.";
        }
    } elseif ($action == 'edit' && isset($_POST['id'])) {
        $bugId = sanitizeInput($_POST['id']);
        $currentBug = $db->getBugById($bugId);//get current bug data
        
        // Check if user has permission to edit this bug u
        $canEdit = canEditBug($currentBug['assignedToId'], $_SESSION['user_id'], $_SESSION['role_id']);
        
        if (!$canEdit) {
            $message = "Error: You don't have permission to edit this bug.";
        } else {
            $bugData = [
                'assignedToId' => !empty($_POST['assignedToId']) ? sanitizeInput($_POST['assignedToId']) : null,
                'statusId' => sanitizeInput($_POST['statusId']),
                'priorityId' => sanitizeInput($_POST['priorityId']),
                'summary' => sanitizeInput($_POST['summary']),
                'description' => sanitizeInput($_POST['description']),
                'fixDescription' => sanitizeInput($_POST['fixDescription']),
                'targetDate' => !empty($_POST['targetDate']) ? sanitizeInput($_POST['targetDate']) : null,
                'dateClosed' => ($_POST['statusId'] == 3 && empty($_POST['dateClosed'])) ? date('Y-m-d H:i:s') : $_POST['dateClosed']
            ];
            
            if (!empty($bugData['targetDate']) && !validateFutureDate($bugData['targetDate'])) {
                $message = "Error: Target date must be in the future.";
            } else {
                $rows = $db->updateBug($bugId, $bugData);
                if ($rows > 0) {
                    $message = "Bug updated successfully!";
                    $action = 'list';
                } else {
                    $message = "Error updating bug.";
                }
            }
        }
    }
}

// Get data for filters and forms
$projects = $db->getAllProjects();
$statuses = $db->getStatuses();
$priorities = $db->getPriorities();
$users = $db->getAllUsersForAssignment();

// check what user is filtering
$filters = [];
if (isset($_GET['project']) && $_GET['project'] != 'all') {
    $filters['project'] = sanitizeInput($_GET['project']);
}
if (isset($_GET['status'])) {
    $filters['status'] = sanitizeInput($_GET['status']);
}

// Get bugs based on user role/filters
if (isUser()) {
    $userProject = $db->getUserProject($_SESSION['user_id']);//get project
    $filters['project'] = $userProject['ProjectId'];//filter project only
}
$bugs = $db->getAllBugs($filters);//get bugs based on filters
?>
<?php include "includes/header.php"; ?>
<h1>Bug Management</h1>

<?php if ($message): ?>
    <p class="<?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($action == 'create'): ?>
    <h2>Report New Bug</h2>
    <form method="POST" action="bugs.php?action=create">
        <div class="form-group">
            <label>Project *</label>
            <select name="projectId" required>
                <option value="">Select Project</option>
                <?php foreach ($projects as $project): ?>
                    <?php if (isUser()): ?>
                        <!-- Users can only select their assign project -->
                        <?php if ($project['Id'] == getCurrentUserProject()): ?>
                            <option value="<?php echo $project['Id']; ?>" selected><?php echo $project['Project']; ?></option>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Admins/managers can select any project -->
                        <option value="<?php echo $project['Id']; ?>"><?php echo $project['Project']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Summary *</label>
            <input type="text" name="summary" required maxlength="250">
        </div>
        <div class="form-group">
            <label>Description *</label>
            <textarea name="description" required maxlength="2500"></textarea>
        </div>
        <?php if (isAdmin() || isManager()): ?>
            <div class="form-group">
                <label>Assign To</label>
                <select name="assignedToId">
                    <option value="">Unassigned</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['Id']; ?>"><?php echo $user['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priorityId">
                    <?php foreach ($priorities as $priority): ?>
                        <option value="<?php echo $priority['Id']; ?>" <?php echo $priority['Id'] == 2 ? 'selected' : ''; ?>>
                            <?php echo $priority['Priority']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Target Date</label>
                <input type="date" name="targetDate" min="<?php echo date('Y-m-d'); ?>">
            </div>
        <?php endif; ?>
        <input type="submit" value="Report Bug">
        <a href="bugs.php">Cancel</a>
    </form>

<?php elseif ($action == 'edit' && isset($_GET['id'])): ?>
    <?php
    $bugId = sanitizeInput($_GET['id']);
    $bug = $db->getBugById($bugId);
    
    // Check if user has permission to edit bug 
    $canEdit = canEditBug($bug['assignedToId'], $_SESSION['user_id'], $_SESSION['role_id']);
    
    if (!$bug) {
        echo "<p class='error'>Bug not found.</p>";
        include "includes/footer.php";
        exit;
    } elseif (!$canEdit) {
        echo "<p class='error'>Access denied. You don't have permission to edit this bug.</p>";
        include "includes/footer.php";
        exit;
    }
    ?>
    <h2>Edit Bug</h2>
    <form method="POST" action="bugs.php?action=edit">
        <input type="hidden" name="id" value="<?php echo $bug['id']; ?>">
        <div class="form-group">
            <label>Summary *</label>
            <input type="text" name="summary" value="<?php echo $bug['summary']; ?>" required>
        </div>
        <div class="form-group">
            <label>Description *</label>
            <textarea name="description" required><?php echo $bug['description']; ?></textarea>
        </div>
        <?php if (isAdmin() || isManager()): ?>
            <div class="form-group">
                <label>Assign To</label>
                <select name="assignedToId">
                    <option value="">Unassigned</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['Id']; ?>" <?php echo $user['Id'] == $bug['assignedToId'] ? 'selected' : ''; ?>>
                            <?php echo $user['Name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label>Status</label>
            <select name="statusId">
                <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo $status['Id']; ?>" <?php echo $status['Id'] == $bug['statusId'] ? 'selected' : ''; ?>>
                        <?php echo $status['Status']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Priority</label>
            <select name="priorityId">
                <?php foreach ($priorities as $priority): ?>
                    <option value="<?php echo $priority['Id']; ?>" <?php echo $priority['Id'] == $bug['priorityId'] ? 'selected' : ''; ?>>
                        <?php echo $priority['Priority']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Fix Description</label>
            <textarea name="fixDescription"><?php echo $bug['fixDescription']; ?></textarea>
        </div>
        <div class="form-group">
            <label>Target Date</label>
            <input type="date" name="targetDate" value="<?php echo $bug['targetDate'] ? substr($bug['targetDate'], 0, 10) : ''; ?>" min="<?php echo date('Y-m-d'); ?>">
        </div>
        <input type="submit" value="Update Bug">
        <a href="bugs.php">Cancel</a>
    </form>

<?php else: ?>
    <div style="margin-bottom: 20px;">
        <a href="bugs.php?action=create" style="background: #007bff; color: white; padding: 10px; text-decoration: none;">Report New Bug</a>
        
        <?php if (isAdmin() || isManager()): ?>
        <div style="float: right;">
            <form method="GET" action="bugs.php" style="display: inline;">
                <select name="project" onchange="this.form.submit()">
                    <option value="all">All Projects</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo $project['Id']; ?>" <?php echo ($filters['project'] ?? '') == $project['Id'] ? 'selected' : ''; ?>>
                            <?php echo $project['Project']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <?php endif; ?>
        
        <div style="margin-top: 10px;">
            <a href="bugs.php?status=all">All Bugs</a> |
            <a href="bugs.php?status=open">Open Bugs</a> |
            <a href="bugs.php?status=overdue">Overdue Bugs</a>
            <?php if (isAdmin() || isManager()): ?>
                | <a href="bugs.php?status=unassigned">Unassigned Bugs</a>
            <?php endif; ?>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Summary</th>
            <th>Project</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Assigned To</th>
            <th>Date Raised</th>
            <th>Actions</th>
        </tr>
        <?php if (empty($bugs)): ?>
            <tr><td colspan="8">No bugs found.</td></tr>
        <?php else: ?>
            <?php foreach ($bugs as $bug): ?>
                <tr>
                    <td><?php echo $bug['id']; ?></td>
                    <td><?php echo $bug['summary']; ?></td>
                    <td><?php echo $bug['project_name']; ?></td>
                    <td><?php 
                        foreach ($statuses as $status) {
                            if ($status['Id'] == $bug['statusId']) {
                                echo $status['Status'];
                                break;
                            }
                        }
                    ?></td>
                    <td><?php 
                        foreach ($priorities as $priority) {
                            if ($priority['Id'] == $bug['priorityId']) {
                                echo $priority['Priority'];
                                break;
                            }
                        }
                    ?></td>
                    <td><?php echo $bug['assigned_name'] ?? 'Unassigned'; ?></td>
                    <td><?php echo $bug['dateRaised']; ?></td>
                    <td>
                        <?php
                        $canEdit = canEditBug($bug['assignedToId'], $_SESSION['user_id'], $_SESSION['role_id']);
                        
                        if ($canEdit): ?>
                            <a href="bugs.php?action=edit&id=<?php echo $bug['id']; ?>">Edit</a>
                        <?php else: ?>
                            <span style="color: #ccc;">Edit</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
<?php endif; ?>
<?php include "includes/footer.php"; ?>