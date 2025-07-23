<?php include('../includes/init.php');
include('../theme/header.php');

?>

<!-- ============================================================== -->
<!-- Start Page Content Here -->
<!-- ============================================================== -->

<style>
body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    overflow: hidden;
}

#toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    position: absolute;
    top: 30px;
    left: 50px;
}

button {
    padding: 10px;
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
<!-- Start Content-->
<div class="container-fluid">
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
<!-- container -->

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->

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
                alert('Image saved successfully: ' + data.file);
                console.log('Image saved successfully: ' + data.file);
                // Optional: Trigger OCR or update UI here
            } else {
                alert('Failed to save image: ' + data.error);
                console.log('Failed to save image: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Error sending image to server: ' + error);
            console.log('Error sending image to server: ' + error);
        }
    });
}
</script>

<?php include('../theme/footer.php'); ?>