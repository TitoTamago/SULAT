<?php
include('../includes/init.php');
session_start();

$username = $_POST['username'];
$plainPassword = $_POST['password']; // plain text password input

// Fetch user by username only
$check_user = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE username = :username", [
    'username' => $username
]);

if ($check_user && password_verify($plainPassword, $check_user['password'])) {
    // Assign values to variables
    $user_id   = $check_user['user_id'];
    $email     = $check_user['email'];
    $role      = $check_user['role'];
    $created_at = date("Y-m-d H:i:s");

    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;

    // Insert log
    $data = [
        'user_id'   => $user_id,
        'email'     => $email,
        'timestamp' => $created_at,
    ];
    $result = addRecord($pdo, 'tbl_logs', $data);

    // Redirect with success
    if ($result) {
        $_SESSION["swal_fire"] = array(
            "title" => "Login Successful",
            "text" => "Welcome back!",
            "icon" => "success",
            "timer" => 2000
        );
    } else {
        $_SESSION["swal_fire"] = array(
            "title" => "Login Logged Failed",
            "text" => "But you're still logged in.",
            "icon" => "warning",
            "timer" => 2000
        );
    }

    header("Location: ai-chat-dashboard.php");
    exit;

} else {
    echo "<script>alert('Invalid credentials!'); window.location.href='ai-chat-dashboard.php';</script>";
}