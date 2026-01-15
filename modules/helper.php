<?php
require_once '../includes/init.php'; // Include your PDO connection
session_start();

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
        echo json_encode(['success' => false, 'message' => 'Screenshot ID is missing.']);
    }
}

function deleteScreenshot() {
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here
        $result = deleteRecord($pdo, 'tbl_screenshot', 'screenshot_id', $uid);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Screenshot deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Screenshot.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Screenshot ID is missing.']);
    }
}

function createFolder() {
    if (isset($_POST['folder_name'])) {
        $folder_name = $_POST['folder_name'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here 
        
        $data = [
            'd_name' => $folder_name,
            'user_id' => $_SESSION['user_id'],
            'created_at' => date("Y-m-d H:i:s"),
        ];
        
        $result = addRecord($pdo, 'tbl_directory', $data);


        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Folder created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create Folder.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Folder ID is missing.']);
    }
}


function renameFolder() {
    if (isset($_POST['d_id'])) {
        $folder_name = $_POST['d_name'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here 
        
        $data = [
            'd_name' => $folder_name,
        ];

        
        // Call the dynamic edit function
        $result = editRecord($pdo, 'tbl_directory', $data, 'd_id', $_POST['d_id']);


        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Folder created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create Folder.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Folder ID is missing.']);
    }
}

function deleteFolder() {
    if (isset($_POST['d_id'])) {
        $d_id = $_POST['d_id'];  // Get the user ID to delete
        
        // Pass the $pdo to the deleteRecord function
        global $pdo;  // Make sure to use the global $pdo here
        $result = deleteRecord($pdo, 'tbl_directory', 'd_id', $d_id);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Folder deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Folder.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Folder ID is missing.']);
    }
}
?>