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

if (isset($_POST['EDIT'])) {

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
                        <h4 class="page-title">LIST OF USER</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-5">
                                    <!-- Large modal -->
                                    <button type="button" onclick="addUser()" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#primary-header-modal"><i
                                            class="mdi mdi-plus-circle me-2"></i> ADD
                                        USER</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="basic-datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User ID</th>
                                            <th>Fullname</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Create Date</th>
                                            <th style="width: 75px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $stmt = $pdo->query("SELECT * FROM tbl_user");

                                        while ($row = $stmt->fetch()) {
                                        ?>
                                        <tr>
                                            <td><?=$row['user_id']?></td>
                                            <td><?=$row['fullname']?></td>
                                            <td><?=$row['username']?></td>
                                            <td><?=$row['email']?></td>
                                            <td><?=$row['role']?></td>
                                            <td><?=$row['created_at']?></td>
                                            <td>
                                                <button type="button" onClick="editUser(<?=$row['user_id']?>)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#primary-header-modal">
                                                    <i class="mdi mdi-square-edit-outline"></i>EDIT</button>
                                                <button type="button" class="btn btn-danger" onClick="deleteUser(<?=$row['user_id']?>)"><i class="mdi mdi-delete"></i>DELETE</button>
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
                            <h4 class="modal-title" id="primary-header-modalLabel">Create New User</h4>
                        </div>
                        <div class="modal-body" id="fetchdata">

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="savechanges" class="btn btn-primary" name="SAVE">Save changes</button>
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
function addUser() {
    $('#savechanges').attr('name', 'SAVE');
    $.ajax({
        url: 'fetch-user.php',
        type: 'POST',
        success: function(response) {
            $('#fetchdata').html(response); // Load the fetched data from the update table PHP file
        }
    })
};

function editUser(uid) {
    $('#savechanges').attr('name', 'EDIT');
    $.ajax({
        url: 'fetch-user.php',
        type: 'POST',
        data: {
            user_id: uid
        },
        success: function(response) {
            $('#fetchdata').html(response); // Load the fetched data from the update table PHP file
        }
    })
};

function deleteUser(uid) {
    Swal.fire({
        title: "Are you sure to delete this user?",
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
                url: '../modules/helper.php?f=deleteUser', // helper.php is the file that contains the function for deleting a user
                data: {
                    uid: uid
                },
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User has been deleted successfully.',
                        icon: 'success',
                        timer: 2000,
                    }).then((result) => {
                        console.log(data);
                        window.location.href = 'user.php'; // Change to your target URL
                    });
                }
            });
        }
    });
};
</script>

<?php include('../theme/footer.php'); ?>