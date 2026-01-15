<?php
include('../includes/init.php');
session_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

$response = [
    "password" => $_POST['password'],
    "status" => "error",
    "title" => "Failed",
    "message" => "Invalid request."
];
// ---------------- LOGIN ----------------
if ($action === "login") {
     // Sanitize input
    $username = $_POST['username'];
    $plainPassword = $_POST['password'];

    if (empty($username) || empty($plainPassword)) {
        $response["message"] = "Username and password are required.";
        echo json_encode($response);
        exit;
    }

    // Optional: Track failed login attempts (rate limiting)
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = time();
    }

    // If too many attempts within 5 minutes
    if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt']) < 300) {
        $response["title"] = "Too Many Attempts";
        $response["message"] = "Too many failed login attempts. Please wait 5 minutes before trying again.";
        echo json_encode($response);
        exit;
    }

    // Query user by username OR email
    $check_user = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE username LIKE :username OR email LIKE :email", ['username' => $username, 'email' => $username]);


    if ($check_user && password_verify($plainPassword, $check_user['password'])) {
        // ✅ Reset failed attempts
        $_SESSION['login_attempts'] = 0;

        // Assign session
        $_SESSION['user_id'] = $check_user['user_id'];
        $_SESSION['role'] = $check_user['role'];

        // Insert login log
        $data = [
            'user_id'   => $check_user['user_id'],
            'email'     => $check_user['email'],
            'timestamp' => date("Y-m-d H:i:s"),
        ];
        $logResult = addRecord($pdo, 'tbl_logs', $data);

        $response = [
            "status" => "success",
            "title" => "Login Successful",
            "message" => "Welcome back, {$check_user['username']}!",
            "redirect" => "modules/ai-chat-dashboard.php"
        ];
    } else {
        // ❌ Wrong password or user not found
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();
        $response["user"] = $check_user['user_id'];
        $response["passwordRaw"] = $check_user['password'];
        $response["message"] = "Invalid username/email or password.";
    }
    
    echo json_encode($response);
    exit;
}


// ---------------- REGISTER ----------------
if ($action === "register") {
    $fullname = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($fullname) ||empty($username) || empty($email) || empty($password)) {
        $response["message"] = "All fields are required.";
        echo json_encode($response);
        exit;
    }

    $existing_user = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE username = :username OR email = :email", [
        'username' => $username,
        'email'    => $email
    ]);

    if ($existing_user) {
        $response["message"] = "Username or Email already exists.";
        echo json_encode($response);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $data = [
        'fullname'   => $fullname,
        'username'   => $username,
        'email'      => $email,
        'password'   => $hashedPassword,
        'role'       => 'USER',
        'created_at' => date("Y-m-d H:i:s")
    ];

    $result = addRecord($pdo, 'tbl_user', $data);

    $last_created_user = queryUniqueValue($pdo, "SELECT user_id FROM tbl_user ORDER BY `tbl_user`.`user_id` DESC");

    $data1 = [
            'd_name' => "Default",
            'user_id' => $last_created_user['user_id'],
            'created_at' => date("Y-m-d H:i:s"),
        ];
        
    $result1 = addRecord($pdo, 'tbl_directory', $data1);
    $response = [
        "fullname" => $fullname,
        "username" => $username,
        "email" => $email,
        "password" => $password,
        "status" => "success",
        "title" => "Account Created",
        "message" => "Your account has been created successfully. Please log in.",
        "redirect" => null
    ];

    echo json_encode($response);
    exit;
}