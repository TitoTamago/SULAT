<?php include('../includes/init.php');
include('../theme/header.php');
$user_id = $_SESSION['user_id'] ?? 0; // replace with actual logic
?>

<!-- Start Content-->
<div class="container-fluid">
    <div class="row mt-2">
        <!-- chat area -->
        <div class="col-xxl-12 col-xl-12 order-xl-12">
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
        <!-- end chat area-->

    </div> <!-- container -->

</div> <!-- content -->
<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
            console.log(data);
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