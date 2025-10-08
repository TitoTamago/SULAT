<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SULAT | LOGIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="theme/assets/images/favicon.ico">

    <!-- App css -->
    <link href="theme/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
</head>

<body class="authentication-bg" data-layout-config='{"darkMode":false}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div id="login-container">
                    <div class="main">
                        <div class="container a-container" id="a-container">
                            <form id="a-form" class="form" method="POST">
                                <h2 class="form_title title">Create Account</h2>
                                <span class="form__span">Use email for registration</span>
                                <input type="hidden" name="action" value="register">
                                <input class="form__input" type="text" name="fullname" placeholder="Fullname" required>
                                <input class="form__input" type="text" name="username" placeholder="Username" required>
                                <input class="form__input" type="email" name="email" placeholder="Email" required>
                                <input class="form__input" type="password" name="password" placeholder="Password" required>
                                <button type="submit" class="form__button button submit" name="SIGN_UP">SIGN UP</button>
                            </form>
                        </div>

                        <div class="container b-container" id="b-container">
                            <form id="b-form" class="form" method="POST">
                                <h2 class="form_title title">Sign in to Website</h2>
                                <span class="form__span">Use your email account</span>
                                <input type="hidden" name="action" value="login">
                                <input class="form__input" type="text" name="username" placeholder="Email or Username" required>
                                <input class="form__input" type="password" name="password" placeholder="Password" required>
                                <!-- <a class="form__link" href="#">Forgot your password?</a> -->
                                <button type="submit" class="form__button button submit" name="SIGN_IN">SIGN IN</button>
                            </form>
                        </div>

                        <div class="switch" id="switch-cnt">
                            <div class="switch__circle"></div>
                            <div class="switch__circle switch__circle--t"></div>

                            <div class="switch__container" id="switch-c1">
                                <h2 class="switch__title title">Welcome Back !</h2>
                                <p class="switch__description description">
                                    To keep connected with us please login with your Account
                                </p>
                                <button class="switch__button button switch-btn">SIGN IN</button>
                            </div>

                            <div class="switch__container is-hidden" id="switch-c2">
                                <h2 class="switch__title title">Hello Friend !</h2>
                                <p class="switch__description description">
                                    Enter your personal details and start journey with us
                                </p>
                                <button class="switch__button button switch-btn">SIGN UP</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <script>
        document.write(new Date().getFullYear())
        </script> © SULAT
    </footer>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- bundle -->
    <script src="theme/assets/js/vendor.min.js"></script>
    <script src="theme/assets/js/app.min.js"></script>

    <script>
    $(document).ready(function() {
        // Handle both forms
        $("#a-form, #b-form").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "modules/auth.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    Swal.fire({
                        icon: res.status,
                        title: res.title,
                        text: res.message,
                        timer: 2500,
                        showConfirmButton: false
                    });

                    if (res.status === "success" && res.redirect) {
                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 2500);
                    }

                    // After successful register, auto-switch back to login form
                    if (res.status === "success" && res.title === "Account Created") {
                        setTimeout(() => {
                            $("#switch-cnt").addClass("is-txr");
                            $(".switch__circle").addClass("is-txr");
                            $("#switch-c1").toggleClass("is-hidden");
                            $("#switch-c2").toggleClass("is-hidden");
                            $("#a-container").toggleClass("is-txl");
                            $("#b-container").toggleClass("is-txl is-z200");
                        }, 2500);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "Something went wrong. Please try again."
                    });
                }
            });
        });

        let $switchCtn = $("#switch-cnt");
        let $switchC1 = $("#switch-c1");
        let $switchC2 = $("#switch-c2");
        let $switchCircle = $(".switch__circle");
        let $switchBtn = $(".switch-btn");
        let $aContainer = $("#a-container");
        let $bContainer = $("#b-container");
        let $allButtons = $(".submit");

        let changeForm = function(e) {
            $switchCtn.addClass("is-gx");
            setTimeout(function() {
                $switchCtn.removeClass("is-gx");
            }, 1500);

            $switchCtn.toggleClass("is-txr");
            $switchCircle.toggleClass("is-txr");

            $switchC1.toggleClass("is-hidden");
            $switchC2.toggleClass("is-hidden");
            $aContainer.toggleClass("is-txl");
            $bContainer.toggleClass("is-txl").toggleClass("is-z200");
        };

        // Attach events
        $switchBtn.on("click", changeForm);
    });
    </script>
</body>


<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:16 GMT -->

</html>