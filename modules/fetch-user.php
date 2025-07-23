<?php
include "../includes/init.php"; 

session_start(); // Execute Session

//Declaration of variables and default value
$user_id  = "";
$fullname  = "";
$username  = "";
$email  = "";
$password  = "";
$role  = "";

if (isset($_POST['user_id'])) {
    $result = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE user_id = :user_id", ['user_id' => $_POST['user_id']]);
    if ($result) {
        // Assign values to variables
        $user_id    = $result['user_id'];
        $fullname   = $result['fullname'];
        $username   = $result['username'];
        $email      = $result['email'];
        $password   = $result['password'];
        $role       = $result['role'];
        $created_at = $result['created_at'];
    }
}


?>
<div class="body">

    <form id="form_validation" method="POST">

        <input type="hidden" class="form-control" name="user_id" value="<?=$user_id?>" />
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="fullname" id="floatingInput" placeholder="name@example.com" value="<?=$fullname?>" />
            <label for="floatingInput">Fullname</label>
        </div>


        <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" value="<?=$email?>" />
            <label for="floatingInput">Email address</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="floatingInput" placeholder="name@example.com" value="<?=$username?>" />
            <label for="floatingInput">Username</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="name@example.com" value="<?=$password?>" />
            <label for="floatingPassword">Password</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" name="role" id="floatingSelect" aria-label="Floating label select example">
                <option selected value="<?=$role?>"><?=$role?></option>
                <option value="ADMIN">ADMIN</option>
                <option value="USER">USER</option>
            </select>
            <label for="floatingSelect">Works with selects</label>
        </div>

    </form>
</div>