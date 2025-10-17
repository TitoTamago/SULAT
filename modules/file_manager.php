<?php include('../includes/init.php');
include('../theme/header.php');

if(isset($_GET['d_id'])){
    $d_id = $_GET['d_id'];
}
?>

<!-- Main content goes here -->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">File Manager</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <!-- Right Sidebar -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Left sidebar -->
                    <div class="page-aside-left" style="height: 60dvh; overflow-y: auto;overflow-x: hidden; position:static;">
                        <div class="btn-group d-block mb-2">
                            <button type="button" onclick="createFolder()" class="btn btn-success w-100">
                                <i class="mdi mdi-plus"></i> Create Folder
                            </button>
                        </div>
                        <div class="email-menu-list mt-3">
                            <?php
                                $stmt = $pdo->query("SELECT * FROM tbl_directory WHERE user_id = '".$_SESSION['user_id']."'");
                            while ($row = $stmt->fetch()) {?>
                            <a href="javascript:void(0)" onclick='redirectFolder(<?=$row["d_id"]?>)' class="list-group-item border-0"><i
                                    class="mdi mdi-folder-outline font-18 align-middle me-2"></i><?=$row['d_name']?></a>
                            <?php }?>
                        </div>
                    </div>

                    <!-- End Left sidebar -->
                    <div class="page-aside-right">
                        <div class="d-lg-flex justify-content-between align-items-center">
                            <div class="app-search">
                                <form>
                                    <div class="mb-2 position-relative">
                                        <input type="text" class="form-control" placeholder="Search files..." />
                                        <span class="mdi mdi-magnify search-icon"></span>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-sm btn-light"><i class="mdi mdi-format-list-bulleted"></i></button>
                                <button type="submit" class="btn btn-sm"><i class="mdi mdi-view-grid"></i></button>
                                <button type="submit" class="btn btn-sm"><i class="mdi mdi-information-outline"></i></button>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="row mx-n1 g-0" style="height: 50dvh;overflow-y: auto;overflow-x: hidden; position:static;">
                                <?php $stmt = $pdo->query("SELECT *, OCTET_LENGTH(screenshot_data) AS file_size_bytes FROM tbl_screenshot WHERE user_id = '".$_SESSION["user_id"]."' ORDER BY created_at DESC");
                                while ($row = $stmt->fetch()) {
                                    $fileSizeBytes = $row['file_size_bytes'];
                                    $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);
                                    $screenshot_name = $row['screenshot_name'] ?: "capture_".$row['screenshot_id'].".".$row['image_type'];
                                    $username = queryUniqueValue($pdo, "SELECT * FROM tbl_user WHERE user_id = :id", ['id' => $row['user_id']]);
                                ?>
                                <div class="col-xxl-4 col-lg-6">
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
                                <div class="col-xxl-4 col-lg-6">
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
                                <div class="col-xxl-4 col-lg-6">
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
                        </div>
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

function redirectFolder(input, oldName, id) {
    revertToText(id, oldName, input);
}

function createFolder(uid) {
    Swal.fire({
        title: 'Enter Folder Name',
        input: 'text',
        inputPlaceholder: 'Type here...',
        showCancelButton: true,
        confirmButtonText: 'Create',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('Please enter a Folder Name!')
            }
            return value
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'post',
                url: '../modules/helper.php?f=createFolder', // helper.php is the file that contains the function for deleting a user
                data: {
                    folder_name: `${result.value}`
                },
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Folder has been created successfully.',
                        icon: 'success',
                        timer: 2000,
                    }).then((result) => {
                        console.log(data);
                        // window.location.href = 'file_manager.php'; // Change to your target URL
                    });
                }
            });
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
                        window.location.href = 'file_manager.php'; // Change to your target URL
                    });
                }
            });
        }
    });
};
</script>

<?php include('../theme/footer.php'); ?>