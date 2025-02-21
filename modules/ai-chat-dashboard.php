<?php include("../theme/header.html"); ?>

<!-- ============================================================== -->
<!-- Start Page Content Here -->
<!-- ============================================================== -->

<style>
#responsive-canvas {
    width: 100%;
}
</style>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">SMART AI DEMO</h4>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-5 col-lg-6">
                        <div class="card card-h-100">
                            <div class="d-flex card-header f-d-column justify-content-between" style="flex-direction: column;">
                                <h4 class="card-title">CHATBOT</h4>
                                <div class="row">

                                    <!-- chat area -->
                                    <div class="col-lg-12">
                                        <div class="card h-100 overflow-hidden mb-0">

                                            <div class="card-body p-0 pt-3">
                                                <ul class="conversation-list px-3 chat-conversation" data-simplebar>
                                                    <li class="clearfix odd">
                                                        <div class="chat-avatar">
                                                            <i class="uil-chat-bubble-user" style="font-size:25px;"></i>
                                                        </div>
                                                        <div class="conversation-text">
                                                            <div class="ctext-wrap">
                                                                <p>
                                                                    what is a apple?
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="conversation-actions dropdown">
                                                            <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Copy Message</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="clearfix">
                                                        <div class="chat-avatar">
                                                            <i class="uil-raddit-alien-alt" style="font-size:25px;"></i>
                                                        </div>
                                                        <div class="conversation-text">
                                                            <div class="ctext-wrap">
                                                                <i>OpenAI</i>
                                                                <p>
                                                                    An apple is a popular fruit that grows on apple trees, belonging to the species Malus domestica. It has a round shape, typically
                                                                    red, green, or yellow skin, and a
                                                                    crisp, juicy interior. Apples are widely consumed raw, but they are also used in cooking and baking, such as in pies, sauces, and
                                                                    salads. They are rich in fiber,
                                                                    vitamins, and antioxidants, making them a healthy snack choice.
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="conversation-actions dropdown">
                                                            <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Copy Message</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div> <!-- end card-body -->

                                            <div class="card-body bg-light mt-2">
                                                <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                                                    <div class="d-flex align-items-start">
                                                        <div class="w-100">
                                                            <input type="text" class="form-control border-0" placeholder="Enter your text" required="">
                                                            <div class="invalid-feedback">Please enter your messsage </div>
                                                        </div>

                                                        <div class="btn-group ms-2">
                                                            <button type="submit" class="btn btn-success chat-send"><i class='uil uil-message'></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> <!-- end card -->
                                    </div>
                                    <!-- end chat area-->
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end card-->
                    </div> <!-- end col -->

                    <div class="col-xl-7 col-lg-6">
                        <div class="card card-h-100">
                            <div class="d-flex card-header justify-content-between" style="flex-direction: column;">
                                <h4 class="card-title">CANVAS</h4>
                                <div class="row">

                                    <!-- chat area -->
                                    <div class="container-fluid">
                                        <div class="col-lg-12">
                                            <canvas id="responsive-canvas" style="background:var(--ct-topbar-item-color); border-radius:10px; border:var(--ct-topbar-item-color) 2px solid;"></canvas>
                                            <button type="button" id="snipButton" class="btn mt-1 btn-danger">Start Snipping</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div>
            <!-- container -->

        </div>
        <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <script>
                        document.write(new Date().getFullYear())
                        </script> © Hyper - Coderthemes.com
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
        </footer>
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


    <script>
    var canvas = document.getElementById('responsive-canvas');
    var heightRatio = 0.6;
    canvas.height = canvas.width * heightRatio;
    </script>

    <?php include("../theme/footer.html"); ?>