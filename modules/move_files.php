<?php
include('../includes/init.php');
session_start();

$file_ids = $_POST['file_ids'];
$target_folder = $_POST['target_folder'];
$user_id = $_SESSION['user_id'];

if (empty($file_ids) || !$target_folder) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Build dynamic placeholders for query
$placeholders = implode(',', array_fill(0, count($file_ids), '?'));
$sql = "UPDATE tbl_screenshot SET d_id = ? WHERE user_id = ? AND screenshot_id IN ($placeholders)";
$stmt = $pdo->prepare($sql);
$params = array_merge([$target_folder, $user_id], $file_ids);

if ($stmt->execute($params)) {
    echo json_encode(['success' => true]);
    $_SESSION["swal_fire"] = array(
        "title" => "Moved Successful",
        "text" => "Files successfully moved.",
        "icon" => "success",
        "timer" => 2000
    );
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
}
?>