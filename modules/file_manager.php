<?php include('../includes/init.php');
include('../theme/header.php');

$user_id = $_SESSION['user_id'] ?? 0;

if(isset($_GET['d_id'])){
    $d_id = $_GET['d_id'];
} else {
    $default_directory = queryUniqueValue($pdo, "SELECT * FROM tbl_directory WHERE user_id LIKE :user_id AND d_name LIKE 'Default'", ['user_id' => $user_id]);
    $d_id = $default_directory['d_id'];
}
?>

<style>
/* List View */
#file-container.list-view {
    display: block;
}


/* Info View (shows larger cards, maybe with extra info) */
#file-container.info-view .card {
    border: 2px solid #0d6efd;
    background-color: #f8f9fa;
}

#file-container .file-item {
    transition: all 0.3s ease;
}
</style>

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
                                <i class="mdi mdi-archive-plus"></i> Create Folder
                            </button>
                        </div>
                        <div class="email-menu-list mt-3">
                            <?php
                                $stmt = $pdo->query("SELECT * FROM tbl_directory WHERE user_id = '".$_SESSION['user_id']."'");
                            while ($row = $stmt->fetch()) {?>
                            <a href="file_manager.php?d_id=<?=$row['d_id']?>" class="list-group-item border-0 <?=$row['d_id'] == $d_id ? 'text-primary' : ''?>"><i
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
                                        <input type="text" id="fileSearch" class="form-control" placeholder="Search files..." />
                                        <span class="mdi mdi-magnify search-icon"></span>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <!-- Move Files Button -->
                                <button <button type="button" class="btn btn-sm" id="btn-move-files" disabled>
                                    <i class="mdi mdi-content-save-move" data-bs-toggle="popover" data-bs-trigger="hover" title="Move Files"
                                        data-bs-content="Select multiple files and move them to another folder."></i>
                                </button>

                                <!-- List View Button -->
                                <button type="button" class="btn btn-sm" id="btn-list">
                                    <i class="mdi mdi-format-list-bulleted" data-bs-toggle="popover" data-bs-trigger="hover" title="List View"
                                        data-bs-content="Display files in a single-column list layout with detailed information."></i>
                                </button>

                                <!-- Grid View Button -->
                                <button type="button" class="btn btn-sm btn-light" id="btn-grid">
                                    <i class="mdi mdi-view-grid" data-bs-toggle="popover" data-bs-trigger="hover" title="Grid View"
                                        data-bs-content="Display files in a grid layout with thumbnail previews."></i>
                                </button>

                            </div>
                        </div>

                        <div class="mt-3">
                            <div id="file-container" class="row mx-n1 g-0 grid-view">
                                <?php $stmt = $pdo->query("SELECT *, OCTET_LENGTH(screenshot_data) AS file_size_bytes FROM tbl_screenshot 
                                WHERE user_id = '".$_SESSION["user_id"]."' AND d_id = $d_id 
                                ORDER BY created_at DESC");

                                while ($row = $stmt->fetch()) {
                                    $fileSizeBytes = $row['file_size_bytes'];
                                    $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);
                                    $screenshot_name = $row['screenshot_name'] ?: "capture_".$row['screenshot_id'].".".$row['image_type'];
                                ?>

                                <div class="col-xxl-4 col-lg-6 file-item">
                                    <div class="card m-1 shadow-none border">
                                        <div class="p-2">
                                            <div class="row align-items-center">
                                                <div class="col-1">
                                                    <!-- Selection Checkbox -->
                                                    <input type="checkbox" class="form-check-input file-select" value="<?=$row['screenshot_id']?>" />
                                                </div>
                                                <div class="col-auto">
                                                    <div class="avatar-sm">
                                                        <span class="avatar-title bg-light text-secondary rounded">
                                                            <img src="data:image/png;base64,<?=base64_encode($row['screenshot_data']);?>" width="100%" />
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col ps-0">
                                                    <div class="dropdown float-end">
                                                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown">
                                                            <i class="mdi mdi-dots-horizontal"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" onClick="veiwImage(<?=$row['screenshot_id']?>)" data-bs-toggle="modal" data-bs-target="#primary-header-modal">View
                                                                File</a>
                                                            <a class="dropdown-item" onClick="renameFile(<?=$row['screenshot_id']?>)" href="javascript:void(0)">Rename File</a>
                                                            <a class="dropdown-item" onClick="deleteData(<?=$row['screenshot_id']?>)">Delete File</a>
                                                        </div>
                                                    </div>
                                                    <a href="javascript:void(0);" id="file-name-<?=$row['screenshot_id']?>" class="text-muted fw-bold"><?=$screenshot_name?></a>
                                                    <p class="mb-0 font-13"><?=$fileSizeMB?> MB</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <?php } ?>
                            </div>

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
                <form method="POST">
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title" id="primary-header-modalLabel">VIEW SCREENSHOT</h4>
                    </div>
                    <div class="modal-body" id="fetchdata">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="savechanges" class="btn btn-primary" name="SAVE">Save changes</button>
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
document.addEventListener("DOMContentLoaded", function() {
    const fileContainer = document.getElementById("file-container");
    const btnList = document.getElementById("btn-list");
    const btnGrid = document.getElementById("btn-grid");

    btnList.addEventListener("click", function() {
        changeLayout("list");
        setActiveButton(btnList);
    });

    btnGrid.addEventListener("click", function() {
        changeLayout("grid");
        setActiveButton(btnGrid);
    });

    function changeLayout(mode) {
        const fileItems = fileContainer.querySelectorAll(".file-item");

        fileItems.forEach(item => {
            // Remove any existing column classes
            item.classList.remove("col-12", "col-xxl-4", "col-lg-6");

            if (mode === "list") {
                item.classList.add("col-12");
            } else if (mode === "grid") {
                item.classList.add("col-xxl-4", "col-lg-6");
            } else if (mode === "info") {
                // You can keep grid size but highlight
                item.classList.add("col-xxl-4", "col-lg-6");
                item.querySelector(".card").classList.add("border-primary", "bg-light");
            }
        });

        // Reset card highlights if leaving info mode
        if (mode !== "info") {
            fileContainer.querySelectorAll(".card").forEach(card => {
                card.classList.remove("border-primary", "bg-light");
            });
        }
    }

    function setActiveButton(activeButton) {
        [btnList, btnGrid].forEach(btn => btn.classList.remove("btn-light"));
        activeButton.classList.add("btn-light");
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const fileContainer = document.getElementById("file-container");
    const searchInput = document.getElementById("fileSearch");

    // Search filter
    searchInput.addEventListener("keyup", function() {
        const searchTerm = this.value.toLowerCase().trim();
        const fileItems = fileContainer.querySelectorAll(".file-item");

        fileItems.forEach(item => {
            const fileName = item.querySelector("a.text-muted").textContent.toLowerCase();
            const fileSize = item.querySelector("p").textContent.toLowerCase();

            // Show if name or size matches
            if (fileName.includes(searchTerm) || fileSize.includes(searchTerm)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const moveBtn = document.getElementById("btn-move-files");

    // Monitor checkbox selection
    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("file-select")) {
            const selected = document.querySelectorAll(".file-select:checked").length;
            moveBtn.disabled = selected === 0;
        }
    });

    moveBtn.addEventListener("click", function() {
        const selectedIds = Array.from(document.querySelectorAll(".file-select:checked"))
            .map(cb => cb.value);

        if (selectedIds.length === 0) return;

        // Load the move UI into modal
        moveFiles(selectedIds);
    });
});

function moveFiles(selectedIds) {
    $.ajax({
        url: 'fetch-folder-available.php',
        type: 'POST',
        data: {
            d_id_curr: <?=$d_id?>,
            file_ids: selectedIds
        },
        success: function(response) {
            // Set modal title dynamically
            $('#primary-header-modalLabel').text('MOVE FILES');
            // Inject HTML returned from PHP
            $('#fetchdata').html(response);
            // Show the modal
            $('#primary-header-modal').modal('show');

            $('#savechanges').html('Move')

            $('#savechanges').on('click', function() {
                const folderId = $('#targetFolder').val();
                if (!folderId) {
                    alert('Please select a target folder.');
                    return;
                }

                $.ajax({
                    url: 'move_files.php',
                    type: 'POST',
                    data: {
                        file_ids: selectedIds,
                        target_folder: folderId
                    },
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                $('#primary-header-modal').modal('hide');
                                location.reload();
                            } else {
                                alert('Error: ' + res.message);
                            }
                        } catch (err) {
                            console.error(err);
                            alert('Unexpected server response.');
                        }
                    },
                    error: function() {
                        alert('Failed to move files.');
                    }
                });
            });
        },
        error: function() {
            alert('Failed to load move files interface.');
        }
    });
}


function veiwImage(uid, rename_status = false) {
    $('#savechanges').hide();
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
                        window.location.href = 'file_manager.php?d_id=<?=$d_id?>'; // Change to your target URL
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
                        window.location.href = 'file_manager.php?d_id=<?=$d_id?>'; // Change to your target URL
                    });
                }
            });
        }
    });
};
</script>

<?php include('../theme/footer.php'); ?>