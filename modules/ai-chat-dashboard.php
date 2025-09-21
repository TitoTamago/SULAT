<?php include('../includes/init.php');
include('../theme/header.php');
include "../includes/google-search-api/search-handler.php";
$searchLimitReached = checkSearchLimit();


?>

<style>
#toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    z-index: 10;
    position: absolute;
    top: 30px;
    left: 50px;
}

button {
    cursor: pointer;
}

canvas {
    border: 1px solid black;
    cursor: crosshair;
    width: 100%;
    height: 80dvh;
    display: block;
}
</style>
<!-- Main content goes here -->
<div class="container-fluid">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid ">

            <div class="row mt-2">

                <!-- CHAT AREA -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body px-0 pb-0">
                            <div id="conversation-html">
                                <!-- chat-box will be loaded here -->
                            </div>
                            <div class="row px-3 pb-3">
                                <div class="col">
                                    <div class="mt-2 bg-light p-3 rounded">
                                        <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                                            <div class="row">
                                                <div class="col-mb-12">
                                                    <?php if ($searchLimitReached): ?>
                                                    <p class="text-warning">
                                                        ⚠️ Daily Google Search API limit (100) has been reached.
                                                        The assistant will still work, but without live search results.
                                                    </p>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col mb-2 mb-sm-0">
                                                    <input type="text" class="form-control border-0" placeholder="Enter your text" required="" id="chat-input">
                                                    <div class="invalid-feedback">
                                                        Please enter your messsage
                                                    </div>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <div class="btn-group">
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-success chat-send"><i class='uil uil-message'></i></button>
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row-->
                                        </form>
                                    </div>
                                </div> <!-- end col-->
                            </div>
                            <!-- end row -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div>

                <!-- canvas area -->
                <div class="col-xl-6">
                    <div class="row">
                        <div class="card card-h-100">
                            <div class="d-flex card-header f-d-column justify-content-between" style="flex-direction: column;">
                                <div class="row">
                                    <!-- canvas area -->
                                    <div class="container-fluid">
                                        <div class="col-lg-12">
                                            <canvas id="responsive-canvas" style="background-color:aliceblue; border-radius:10px; border:var(--ct-topbar-item-color) 2px solid;"></canvas>

                                        </div>
                                        <div id="toolbar">
                                            <button class="btn btn-primary btn-sm" onclick="setMode('draw')"><i class="mdi mdi-draw"></i></button>
                                            <button class="btn btn-danger btn-sm" onclick="setMode('erase')"><i class="mdi mdi-eraser"></i></button>
                                            <button class="btn btn-warning btn-sm" onclick="setMode('default')"><i class="mdi mdi-cursor-default"></i></button>
                                            <button class="btn btn-secondary btn-sm" onclick="clearCanvas()"><i class="mdi mdi-delete"></i></button>
                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <button type="button" id="saveImageBtn" class="btn btn-sm btn-success" onclick="saveCanvasImage()">Save Image</button>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>

            </div>
        </div>
    </div> <!-- content -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../includes/js.feature.functions/canvas.feature.js"></script>

<script>
function saveCanvasImage() {
    const canvas = document.getElementById('responsive-canvas');
    const imageData = canvas.toDataURL('image/png');

    $.ajax({
        url: '../includes/google-cloud-vision-api/save_canvas_image.php',
        type: 'POST',
        data: JSON.stringify({
            image: imageData
        }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                console.log('Image saved successfully: ' + data.file);

                // ✅ Set OCR result into the input field
                $('#chat-input').val(data.text);

            } else {
                console.log('Failed to save image: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error sending image to server: ' + error);
        }

    });
}


function scrollToBottom() {
    const $chatBoxWrapper = $('#chat-box .simplebar-content-wrapper');
    $chatBoxWrapper.scrollTop($chatBoxWrapper[0].scrollHeight);
}

function fetchMessages() {
    $.get('fetch-conversation.php', function(html) {
        $('#conversation-html').html(html);

        // Scroll after DOM update
        setTimeout(scrollToBottom, 50);
    });
}

$(function() {
    const $chatForm = $('#chat-form');
    const $chatInput = $('#chat-input');

    $chatForm.on('submit', function(e) {
        e.preventDefault();
        const message = $chatInput.val().trim();
        if (message === '') return;

        $.post('chatbot-handler.php', {
            message: message
        }, function(data) {
            const res = JSON.parse(data);
            fetchMessages()

            // Now it's safe to access #chat-box
            const $chatBox = $('#chat-box');

            // Optional: scroll to bottom
            $chatBox.scrollTop($chatBox[0].scrollHeight);
        });

        $chatInput.val('');
    });
});


fetchMessages()
</script>

<?php include('../theme/footer.php'); ?>