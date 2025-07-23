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

if (isset($_POST['screenshot_id'])) {
    $stmt = $pdo->query("SELECT * FROM tbl_screenshot WHERE screenshot_id = '".$_POST['screenshot_id']."'");
    while ($result = $stmt->fetch()) {
        if ($result) {
            // Assign values to variables
            $screenshot_data   = $result['screenshot_data'];
        }
    }
}


?>
<div class="body">
    <form id="form_validation" method="POST">
        <div class="form-floating mb-3">
            <img class="img-fluid img-thumbnail" src="data:image/png;base64,<?=base64_encode($screenshot_data);?>" alt="Image cannot be diplay. ERROR at the moment">
        </div>

    </form>
</div>