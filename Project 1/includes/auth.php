<?php
session_name("BugTracker");
session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

function checkRole($requiredRole) {
    if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != $requiredRole) {
        header("Location: index.php");
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1;//admin has role ID 1
}

function isManager() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2;//manager has role ID 2
}

function isUser() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3;//user has role ID 3
}

function getCurrentUserProject() {
    return $_SESSION['project_id'] ?? null;//get user's assigned project or null
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));//clean input to prevent XSS
}

function validateDate($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);//check if date format is YYYY-MM-DD
}

function validateFutureDate($date) {
    return strtotime($date) > time();//check if date is in future
}

function canEditBug($bugAssignedToId, $currentUserId, $currentUserRole) {
    if ($currentUserRole == 1 || $currentUserRole == 2) {//admin or manager
        return true;//admins and managers can edit any bug
    } elseif ($currentUserRole == 3 && $bugAssignedToId == $currentUserId) {//user and bug assigned to them
        return true;//users can only edit bugs assigned to them
    }
    return false;//default cannot edit
}
?>