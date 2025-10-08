<?php
require_once '../includes/init.php'; // Include your PDO connection

// Handle the function call dynamically
handleRequest();

function deleteUser() {
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here
        $result = deleteRecord($pdo, 'tbl_user', 'user_id', $uid);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID is missing.']);
    }
}


function renameScreenshot() {
    if (isset($_POST['uid'])) {
        $screenshot_id = $_POST['uid'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here
        
        $data = [
            'screenshot_name' => $_POST['new_name'],
        ];

        // Call the dynamic edit function
        $result = editRecord($pdo, 'tbl_screenshot', $data, 'screenshot_id', $screenshot_id);
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID is missing.']);
    }
}

function deleteScreenshot() {
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here
        $result = deleteRecord($pdo, 'tbl_screenshot', 'screenshot_id', $uid);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID is missing.']);
    }
}
?>