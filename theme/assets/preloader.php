<style>
/* Demo Styles */
#content {
    margin: 0 auto;
    padding-bottom: 50px;
    width: 80%;
    max-width: 978px;
}

h1 {
    font-size: 40px;
}


#loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* soft light gray-blue */
    z-index: 998;
    overflow: hidden;
}

/* Modernizr no-js fallback */
.no-js #loader-wrapper {
    display: none;
}

#loader {
    display: block;
    position: relative;
    left: 50%;
    top: 50%;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #2a7ec2;
    /* brand blue */
    animation: spin 1.7s linear infinite;
    z-index: 999;
}

#loader:before {
    content: "";
    position: absolute;
    top: 5px;
    left: 5px;
    right: 5px;
    bottom: 5px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #5aa9e6;
    /* lighter blue accent */
    animation: spin-reverse .6s linear infinite;
}

#loader:after {
    content: "";
    position: absolute;
    top: 15px;
    left: 15px;
    right: 15px;
    bottom: 15px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #ffb347;
    /* warm contrast color */
    animation: spin 1s linear infinite;
}

/* Keyframes */
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

@keyframes spin-reverse {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(-360deg);
    }
}

#loader-wrapper .loader-section {
    position: fixed;
    top: 0;
    width: 51%;
    height: 100%;
    background: #f5f7fa;
    z-index: 10;
}

#loader-wrapper .loader-section.section-left {
    left: 0;
}

#loader-wrapper .loader-section.section-right {
    right: 0;
}

/* Loaded styles */
.loaded #loader-wrapper .loader-section.section-left {
    transform: translateX(-100%);
    transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
}

.loaded #loader-wrapper .loader-section.section-right {
    transform: translateX(100%);
    transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
}

.loaded #loader {
    opacity: 0;
    transition: all 0.3s ease-out;
}

.loaded #loader-wrapper {
    visibility: hidden;
    transform: translateY(-100%);
    transition: all 0.3s 1s ease-out;
}

.loader-logo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 80px;
    /* adjust size */
    max-height: 80px;
    z-index: 1000;
    /* above spinning borders */
}
</style>

<div id="loader-wrapper">
    <div id="loader">
        <img src="theme/assets/images/logo_sm.png" alt="Logo" class="loader-logo">
    </div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

<script>
$(window).on('load', function() {
    // Force a minimum preloader display time
    setTimeout(function() {
        $('body').addClass('loaded');
    }, 1500); // 1.5 seconds
});
</script>