<?php include('../includes/init.php');
include('../theme/header.php');

if (isset($_POST['SAVE'])) {
    // Collect form data dynamically
    $data = [
        'fullname' => $_POST['fullname'],
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'role' => $_POST['role']
    ];

    // Call the dynamic add function
    $result = addRecord($pdo, 'tbl_user', $data);
    
    if ($result) {
    $_SESSION["swal_fire"] = array(
        "title" => "Save Successful",
        "text" => "User successfully saved.",
        "icon" => "success",
        "timer" => 2000
    );
    header("Location: user.php");
    exit(); // Always call exit() after header to stop further script execution
    } else {
        echo "Failed to add user!";
    }
}

if (isset($_POST['edit'])) {

    // Get the user_id and collect form data dynamically
    $user_id = $_POST['user_id'];
    $data = [
        'fullname' => $_POST['fullname'],
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'role' => $_POST['role']
    ];

    // Call the dynamic edit function
    $result = editRecord($pdo, 'tbl_user', $data, 'user_id', $user_id);
    
    if ($result) {
        echo "User edited successfully!";
    } else {
        echo "Failed to edit user!";
    }
}


?>

<!-- Main content goes here -->
<div class="container-fluid">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">LIST OF SCREENSHOT</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="basic-datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Screenshot ID</th>
                                            <th>Username</th>
                                            <th>Screenshot</th>
                                            <th>Image Type</th>
                                            <th>Create Date</th>
                                            <th style="width: 75px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $stmt = $pdo->query("SELECT * FROM tbl_screenshot WHERE user_id = '".$_SESSION["user_id"]."'");

                                        while ($row = $stmt->fetch()) {
                                            $username = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE user_id = :id", ['id' => $row['user_id']]);
                                        ?>
                                        <tr>
                                            <td><?=$row['screenshot_id']?></td>
                                            <td><?=$username['fullname'] ?? 'User not Available'?></td>
                                            <td>
                                                <!-- <img src="data:image/png;base64,<?=base64_encode($row['screenshot_data']);?>" width="200" /> -->

                                                <center><button type="button" class="btn btn-info" onClick="veiwImage(<?=$row['screenshot_id']?>)" data-bs-toggle="modal"
                                                        data-bs-target="#primary-header-modal"><i class="mdi mdi-image-area"></i>VIEW</button>
                                                </center>
                                            </td>
                                            <td><?=$row['image_type']?></td>
                                            <td><?=$row['created_at']?></td>
                                            <td>
                                                <center><button type="button" class="btn btn-danger" onClick="deleteData(<?=$row['user_id']?>)"><i class="mdi mdi-delete"></i>DELETE</button></center>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>
                    document.write(new Date().getFullYear())
                    </script> © Sulat
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-md-block">
                        <a href="javascript: void(0);">About</a>
                        <a href="javascript: void(0);">Support</a>
                        <a href="javascript: void(0);">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Primary Header Modal -->
        <div id="primary-header-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="user.php" method="POST">
                        <div class="modal-header modal-colored-header bg-primary">
                            <h4 class="modal-title" id="primary-header-modalLabel">VIEW SCREENSHOT</h4>
                        </div>
                        <div class="modal-body" id="fetchdata">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </footer>
    <!-- end Footer -->
</div>
<script>
function veiwImage(uid) {
    $.ajax({
        url: 'view-image.php',
        type: 'POST',
        data: {
            screenshot_id: uid
        },
        success: function(response) {
            console.log("success");
            $('#fetchdata').html(response); // Load the fetched data from the update table PHP file
        }
    })
};

function deleteData(uid) {
    Swal.fire({
        title: "Are you sure to delete this Screenshot?",
        text: "You will not be able to recover this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#727cf5",
        cancelButtonColor: "#d33",
        confirmButtonText: "CONFIRM"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'post',
                url: '../modules/helper.php?f=deleteScreenshot', // helper.php is the file that contains the function for deleting a user
                data: {
                    uid: uid
                },
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Screenshot has been deleted successfully.',
                        icon: 'success',
                        timer: 2000,
                    }).then((result) => {
                        console.log(data);
                        window.location.href = 'screenshot.php'; // Change to your target URL
                    });
                }
            });
        }
    });
};
</script>

<?php include('../theme/footer.php'); ?>