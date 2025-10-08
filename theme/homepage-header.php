<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from coderthemes.com/hyper/saas/landing.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:17 GMT -->

<head>
    <meta charset="utf-8" />
    <title>SULAT | Homepage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="theme/assets/images/favicon.ico" />

    <!-- App css -->
    <link href="theme/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/css/icons.modified.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/css/app.modified.css" rel="stylesheet" type="text/css" id="app-style" />
</head>

<style>
.logo-dark {
    filter: drop-shadow(0 0 10px #22222243);
    /* your blue palette color */
}

.svg-blue {
    filter: brightness(0) saturate(100%) invert(22%) sepia(70%) saturate(472%) hue-rotate(180deg) brightness(95%) contrast(95%);
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body class="loading" data-layout-config='{"darkMode":false}'>
    <?php include 'theme/assets/preloader.php'; ?>
    <!-- NAVBAR START -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="
    position: fixed;
    width: 100%;
    z-index: 900;
    background-color: #2a7ec2eb;">
        <div class="container">
            <!-- logo -->
            <a href="index.php" class="navbar-brand me-lg-5">
                <img src="theme/assets/images/logo.png" alt="" class="logo-dark" height="40" />
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>

            <!-- menus -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <!-- left menu -->
                <ul class="navbar-nav me-auto align-items-center">
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link" href="#">Purpose</a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link" href="#">FAQs</a>
                    </li>
                </ul>

                <!-- right menu -->
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-0">
                        <a href="login.php" class="nav-link d-lg-none">Try it now</a>
                        <a href="login.php" class="btn btn-sm btn-light rounded-pill d-none d-lg-inline-flex">
                            <i class="mdi mdi-application-edit me-2"></i> Try it now
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->
</body>

</html>