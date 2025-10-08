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

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Screenshot Manager</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Right Sidebar -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="mt-3">
                        <h5 class="mb-2">Recent Screenshots</h5>
                        <div class="row mx-n1 g-0">
                            <?php $stmt = $pdo->query("SELECT *, OCTET_LENGTH(screenshot_data) AS file_size_bytes FROM tbl_screenshot WHERE user_id = '".$_SESSION["user_id"]."' ORDER BY created_at DESC");
                            while ($row = $stmt->fetch()) {
                            $fileSizeBytes = $row['file_size_bytes'];
                            $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);
                            $screenshot_name = $row['screenshot_name'] ?: "screenshot_".$row['screenshot_id'].".".$row['image_type'];
                            $username = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE user_id = :id", ['id' => $row['user_id']]);
                            ?>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="card m-1 shadow-none border">
                                    <div class="p-2">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-light text-secondary rounded">
                                                        <img src="data:image/png;base64,<?=base64_encode($row['screenshot_data']);?>" width="100%" />
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col ps-0">
                                                <div class="dropdown float-end">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- View option -->
                                                        <a class="dropdown-item" onClick="veiwImage(<?=$row['screenshot_id']?>)" data-bs-toggle="modal" data-bs-target="#primary-header-modal">View
                                                            File</a>
                                                        <!-- Rename option -->
                                                        <a class="dropdown-item" onClick="renameFile(<?=$row['screenshot_id']?>)" href="javascript:void(0)">Rename File</a>
                                                        <!-- Delete option -->
                                                        <a class="dropdown-item" onClick="deleteData(<?=$row['screenshot_id']?>)">Delete File</a>
                                                    </div>
                                                </div>
                                                <a href="javascript:void(0);" id="file-name-<?=$row['screenshot_id']?>" class="text-muted fw-bold"><?=$screenshot_name?></a>
                                                <p class="mb-0 font-13"><?=$fileSizeMB?> MB</p>
                                            </div>
                                        </div> <!-- end row -->
                                    </div> <!-- end .p-2-->
                                </div> <!-- end col -->
                            </div> <!-- end col-->
                            <?php }?>
                        </div> <!-- end row-->
                    </div> <!-- end .mt-3-->
                </div>
                <!-- end card-body -->
            </div> <!-- end card-box -->

        </div> <!-- end Col -->
    </div><!-- End row -->

</div> <!-- container -->

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
function veiwImage(uid, rename_status = false) {
    $.ajax({
        url: 'view-image.php',
        type: 'POST',
        data: {
            screenshot_id: uid,
            rename: rename_status
        },
        success: function(response) {
            console.log("success");
            $('#fetchdata').html(response); // Load the fetched data from the update table PHP file
        }
    })
};

function renameFile(id) {
    const nameElement = document.getElementById(`file-name-${id}`);
    const oldName = nameElement.textContent.trim();

    // Create input field
    const input = document.createElement("input");
    input.type = "text";
    input.value = oldName;
    input.className = "form-control form-control-sm d-inline-block";
    input.style.width = "auto";
    input.style.maxWidth = "220px";
    input.style.display = "inline-block";
    input.style.fontWeight = "bold";
    input.style.color = "#6c757d";

    // Replace link with input
    nameElement.replaceWith(input);
    input.focus();
    input.select();

    // Save on Enter
    input.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const newName = input.value.trim();
            saveNewFileName(id, newName, oldName, input); // ✅ pass input
        } else if (e.key === "Escape") {
            cancelRename(input, oldName, id);
        }
    });

    // Save when clicking outside
    input.addEventListener("blur", function() {
        const newName = input.value.trim();
        saveNewFileName(id, newName, oldName, input); // ✅ pass input
    });
}

function saveNewFileName(id, newName, oldName, input) {
    // If unchanged or empty, revert
    if (!newName || newName === oldName) {
        revertToText(id, oldName, input);
        return;
    }

    // AJAX rename
    $.ajax({
        url: '../modules/helper.php?f=renameScreenshot',
        type: "POST",
        data: {
            uid: id,
            new_name: newName
        },
        success: function(response) {
            Swal.fire({
                icon: "success",
                title: "File Renamed",
                text: `File renamed to "${newName}" successfully.`,
                confirmButtonColor: "#727cf5",
                timer: 1500,
                showConfirmButton: false,
            });

            revertToText(id, newName, input); // ✅ always revert to <a>
        },
        error: function() {
            Swal.fire({
                icon: "error",
                title: "Rename Failed",
                text: "There was a problem renaming the file.",
                confirmButtonColor: "#d33",
            });

            revertToText(id, oldName, input);
        },
    });
}

function cancelRename(input, oldName, id) {
    revertToText(id, oldName, input);
}

function revertToText(id, fileName, input = null) {
    const link = document.createElement("a");
    link.href = "javascript:void(0);";
    link.id = `file-name-${id}`;
    link.className = "text-muted fw-bold";
    link.textContent = fileName;

    if (input && input.parentNode) {
        input.parentNode.replaceChild(link, input);
    } else {
        const existingInput = document.querySelector("input");
        if (existingInput && existingInput.parentNode) {
            existingInput.parentNode.replaceChild(link, existingInput);
        }
    }
}


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