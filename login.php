<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | SULAT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="theme/assets/images/favicon.ico">

    <!-- App css -->
    <link href="theme/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">

</head>


<style>
body.authentication-bg {
    background-image: url(theme/assets/images/bg-pattern-light.svg);
    background-size: cover;
    background-position: center;
}

/* Scope everything under #login-container */
#login-container,
#login-container *,
#login-container *::after,
#login-container *::before {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    user-select: none;
}

/* Generic */
#login-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    background-color: #ecf0f3;
    color: #a0a5a8;
}

/**/
#login-container .main {
    position: relative;
    width: 1000px;
    min-width: 1000px;
    min-height: 600px;
    height: 600px;
    padding: 25px;
    background-color: #ecf0f3;
    box-shadow: 10px 10px 10px #d1d9e6, -10px -10px 10px #f9f9f9;
    border-radius: 12px;
    overflow: hidden;
}

@media (max-width: 1200px) {
    #login-container .main {
        transform: scale(.7);
    }
}

@media (max-width: 1000px) {
    #login-container .main {
        transform: scale(.6);
    }
}

@media (max-width: 800px) {
    #login-container .main {
        transform: scale(.5);
    }
}

@media (max-width: 600px) {
    #login-container .main {
        transform: scale(.4);
    }
}

#login-container .container {
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    width: 600px;
    height: 100%;
    padding: 25px;
    background-color: #ecf0f3;
    transition: 1.25s;
}

#login-container .form {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

#login-container .form__icon {
    object-fit: contain;
    width: 30px;
    margin: 0 5px;
    opacity: .5;
    transition: .15s;
}

#login-container .form__icon:hover {
    opacity: 1;
    transition: .15s;
    cursor: pointer;
}

#login-container .form__input {
    width: 350px;
    height: 40px;
    margin: 4px 0;
    padding-left: 25px;
    font-size: 13px;
    letter-spacing: .15px;
    border: none;
    outline: none;
    font-family: 'Montserrat', sans-serif;
    background-color: #ecf0f3;
    transition: .25s ease;
    border-radius: 8px;
    box-shadow: inset 2px 2px 4px #d1d9e6, inset -2px -2px 4px #f9f9f9;
}

#login-container .form__input:focus {
    box-shadow: inset 4px 4px 4px #d1d9e6, inset -4px -4px 4px #f9f9f9;
}

#login-container .form__span {
    margin-top: 30px;
    margin-bottom: 12px;
}

#login-container .form__link {
    color: #181818;
    font-size: 15px;
    margin-top: 25px;
    border-bottom: 1px solid #a0a5a8;
    line-height: 2;
}

#login-container .title {
    font-size: 34px;
    font-weight: 700;
    line-height: 3;
    color: #181818;
}

#login-container .description {
    font-size: 14px;
    letter-spacing: .25px;
    text-align: center;
    line-height: 1.6;
}

#login-container .button {
    width: 180px;
    height: 50px;
    border-radius: 25px;
    margin-top: 50px;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 1.15px;
    background-color: #4B70E2;
    color: #f9f9f9;
    box-shadow: 8px 8px 16px #d1d9e6, -8px -8px 16px #f9f9f9;
    border: none;
    outline: none;
}

/**/
#login-container .a-container {
    z-index: 100;
    left: calc(100% - 600px);
}

#login-container .b-container {
    left: calc(100% - 600px);
    z-index: 0;
}

#login-container .switch {
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 400px;
    padding: 50px;
    z-index: 200;
    transition: 1.25s;
    background-color: #ecf0f3;
    overflow: hidden;
    box-shadow: 4px 4px 10px #d1d9e6, -4px -4px 10px #f9f9f9;
}

#login-container .switch__circle {
    position: absolute;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background-color: #ecf0f3;
    box-shadow: inset 8px 8px 12px #d1d9e6, inset -8px -8px 12px #f9f9f9;
    bottom: -60%;
    left: -60%;
    transition: 1.25s;
}

#login-container .switch__circle--t {
    top: -30%;
    left: 60%;
    width: 300px;
    height: 300px;
}

#login-container .switch__container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    position: absolute;
    width: 400px;
    padding: 50px 55px;
    transition: 1.25s;
}

#login-container .switch__button {
    cursor: pointer;
}

#login-container .switch__button:hover {
    box-shadow: 6px 6px 10px #d1d9e6, -6px -6px 10px #f9f9f9;
    transform: scale(.985);
    transition: .25s;
}

#login-container .switch__button:active,
#login-container .switch__button:focus {
    box-shadow: 2px 2px 6px #d1d9e6, -2px -2px 6px #f9f9f9;
    transform: scale(.97);
    transition: .25s;
}

/**/
#login-container .is-txr {
    left: calc(100% - 400px);
    transition: 1.25s;
    transform-origin: left;
}

#login-container .is-txl {
    left: 0;
    transition: 1.25s;
    transform-origin: right;
}

#login-container .is-z200 {
    z-index: 200;
    transition: 1.25s;
}

#login-container .is-hidden {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    transition: 1.25s;
}

#login-container .is-gx {
    animation: is-gx 1.25s;
}

@keyframes is-gx {

    0%,
    10%,
    100% {
        width: 400px;
    }

    30%,
    50% {
        width: 500px;
    }
}
</style>

<body class="authentication-bg" data-layout-config='{"darkMode":false}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div id="login-container">
                    <div class="main">
                        <div class="container a-container" id="a-container">
                            <form id="a-form" class="form" method="" action="">
                                <h2 class="form_title title">Create Account</h2>
                                <span class="form__span">Use email for registration</span>
                                <input class="form__input" type="text" placeholder="Name">
                                <input class="form__input" type="text" placeholder="Email">
                                <input class="form__input" type="password" placeholder="Password">
                                <button class="form__button button submit">SIGN UP</button>
                            </form>
                        </div>

                        <div class="container b-container" id="b-container">
                            <form id="b-form" class="form" method="" action="">
                                <h2 class="form_title title">Sign in to Website</h2>
                                <span class="form__span">Use your email account</span>
                                <input class="form__input" type="text" placeholder="Email">
                                <input class="form__input" type="password" placeholder="Password">
                                <a class="form__link" href="#">Forgot your password?</a>
                                <button class="form__button button submit">SIGN IN</button>
                            </form>
                        </div>

                        <div class="switch" id="switch-cnt">
                            <div class="switch__circle"></div>
                            <div class="switch__circle switch__circle--t"></div>

                            <div class="switch__container" id="switch-c1">
                                <h2 class="switch__title title">Welcome Back !</h2>
                                <p class="switch__description description">
                                    To keep connected with us please login with your personal info
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
        2018 -
        <script>
        document.write(new Date().getFullYear())
        </script> © Hyper - Coderthemes.com
    </footer>

    <!-- bundle -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>

    <script>
    let switchCtn = document.querySelector("#switch-cnt");
    let switchC1 = document.querySelector("#switch-c1");
    let switchC2 = document.querySelector("#switch-c2");
    let switchCircle = document.querySelectorAll(".switch__circle");
    let switchBtn = document.querySelectorAll(".switch-btn");
    let aContainer = document.querySelector("#a-container");
    let bContainer = document.querySelector("#b-container");
    let allButtons = document.querySelectorAll(".submit");

    let getButtons = (e) => e.preventDefault()

    let changeForm = (e) => {
        switchCtn.classList.add("is-gx");
        setTimeout(function() {
            switchCtn.classList.remove("is-gx");
        }, 1500)

        switchCtn.classList.toggle("is-txr");
        switchCircle[0].classList.toggle("is-txr");
        switchCircle[1].classList.toggle("is-txr");

        switchC1.classList.toggle("is-hidden");
        switchC2.classList.toggle("is-hidden");
        aContainer.classList.toggle("is-txl");
        bContainer.classList.toggle("is-txl");
        bContainer.classList.toggle("is-z200");
    }

    let mainF = (e) => {
        for (var i = 0; i < allButtons.length; i++)
            allButtons[i].addEventListener("click", getButtons);
        for (var i = 0; i < switchBtn.length; i++)
            switchBtn[i].addEventListener("click", changeForm)
    }

    window.addEventListener("load", mainF);
    </script>

</body>

<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:16 GMT -->

</html>