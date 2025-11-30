<?php
include('../includes/init.php');
ob_start();
session_start();
if (!isset($_SESSION['user_id'])){
    // Redirect to login page
    header("Location: /SULAT/login.php"); // change "/" to your homepage URL if different
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from coderthemes.com/hyper/saas/apps-ecommerce-sellers.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:02 GMT -->

<head>
    <meta charset="utf-8" />
    <title>SULAT | Systems</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App Favicon -->
    <link rel="shortcut icon" href="../theme/assets/images/favicon.ico">

    <!-- Core CSS -->
    <link href="../theme/assets/css/icons.modified.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/app.modified.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Third Party CSS -->
    <link href="../theme/assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/fixedHeader.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/fixedColumns.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/simplemde.min.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/quill.bubble.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/quill.core.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/quill.snow.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/css/vendor/frappe-gantt.css" rel="stylesheet" type="text/css" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>

<style>
.modal-backdrop.show {
    opacity: 0.8;
}
</style>


<body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true" id="mainContent">
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- LOGO -->
            <a href="" class="logo text-center logo-light" disabled>
                <span class="logo-lg">
                    <img src="../theme/assets/images/logo.png" alt="" height="32">
                </span>
                <span class="logo-sm">
                    <img src="../theme/assets/images/logo_sm.png" alt="" height="32">
                </span>
            </a>

            <!-- LOGO -->
            <a href="" class="logo text-center logo-dark" disabled>
                <span class="logo-lg">
                    <img src="../theme/assets/images/logo-dark.png" alt="" height="32">
                </span>
                <span class="logo-sm">
                    <img src="../theme/assets/images/logo_sm_dark.png" alt="" height="32">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>

                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title side-nav-item">System Demo</li>

                    <li class="side-nav-item">
                        <a href="../modules/ai-chat-dashboard.php" class="side-nav-link">
                            <i class="uil-calender"></i>
                            <span> SULAT </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="../modules/chat-dashboard.php" class="side-nav-link">
                            <i class="uil-comments-alt"></i>
                            <span> Chatbot </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="../modules/canvas-dashboard.php" class="side-nav-link">
                            <i class="uil-comments-alt"></i>
                            <span> Canvas </span>
                        </a>
                    </li>

                    <li class="side-nav-title side-nav-item">Utility</li>

                    <?php if($_SESSION['role'] === "ADMIN"){?>
                    <li class="side-nav-item">
                        <a href="../modules/user.php" class="side-nav-link">
                            <i class="uil-rss"></i>
                            <span> System User </span>
                        </a>
                    </li>
                    <?php }?>

                    <li class="side-nav-item">
                        <a href="../modules/file_manager.php" class="side-nav-link">
                            <i class="uil-folder-plus"></i>
                            <span> File Manager </span>
                        </a>
                    </li>

                    <li class="side-nav-title side-nav-item mt-1">Other</li>

                    <li class="side-nav-item">
                        <a href="../includes/clear.php" class="side-nav-link">
                            <i class="uil-sign-out-alt"></i>
                            <span> Logout </span>
                        </a>
                    </li>

                    <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->


        <!-- Page Content Start -->
        <div class="content-page">
            <!-- Topbar Start -->
            <div class="navbar-custom">
                <ul class="list-unstyled topbar-menu float-end mb-0">

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="dripicons-view-apps noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg p-0">

                            <form class="p-3">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-sm btn-success" onclick="fullscreenSnip()">
                                        <i class="mdi mdi-content-cut"></i> Fullscreen Snip
                                    </button>
                                    <button id="captureScreenshot" type="button" class="btn btn-sm mt-2 btn-warning">
                                        <i class="mdi mdi-selection"></i> Region Snip
                                    </button>
                                </div>
                            </form>

                        </div>
                    </li>

                    <li class="notification-list">
                        <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                            <i class="dripicons-gear noti-icon"></i>
                        </a>
                    </li>

                </ul>
                <button class="button-menu-mobile open-left">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>
            <!-- end Topbar -->


            <!-- Login Modal -->
            <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"">
                <div class=" modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="login-form" method="POST" action="auth.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <input type="hidden" value="login" name="action">
                                <label for="username" class="form-label">Username/Email</label>
                                <input type="text" class="form-control" name="username" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>