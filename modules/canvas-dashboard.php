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
    <div class="row mt-2">
        <div class="card card-h-100">
            <div class="d-flex card-header f-d-column justify-content-between" style="flex-direction: column;">
                <div class="row">
                    <!-- canvas area -->
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <canvas id="responsive-canvas" style="background-color:aliceblue; border-radius:10px; border:var(--ct-topbar-item-color) 2px solid;"></canvas>

                        </div>
                        <div id="toolbar">
                            <!-- Toolbar Buttons -->
                            <button class="btn btn-primary btn-sm" onclick="setMode('draw')" data-bs-toggle="popover" data-bs-trigger="hover" title="Drawing Tool"
                                data-bs-content="Draw freely on the canvas using the pen tool.">
                                <i class="mdi mdi-draw"></i>
                            </button>

                            <button class="btn btn-danger btn-sm" onclick="setMode('erase')" data-bs-toggle="popover" data-bs-trigger="hover" title="Eraser Tool"
                                data-bs-content="Erase parts of your drawing with a thick brush.">
                                <i class="mdi mdi-eraser"></i>
                            </button>

                            <button class="btn btn-warning btn-sm" onclick="setMode('default')" data-bs-toggle="popover" data-bs-trigger="hover" title="Pointer Mode"
                                data-bs-content="Return to normal cursor mode without drawing or erasing.">
                                <i class="mdi mdi-cursor-default"></i>
                            </button>

                            <button class="btn btn-secondary btn-sm" onclick="clearCanvas()" data-bs-toggle="popover" data-bs-trigger="hover" title="Clear Canvas"
                                data-bs-content="Clear everything on the canvas after confirmation.">
                                <i class="mdi mdi-delete"></i>
                            </button>

                            <button class="btn btn-info btn-sm" onclick="undo()" data-bs-toggle="popover" data-bs-trigger="hover" title="Undo Action"
                                data-bs-content="Revert the most recent stroke or action.">
                                <i class="mdi mdi-undo"></i>
                            </button>

                            <button class="btn btn-info btn-sm" onclick="redo()" data-bs-toggle="popover" data-bs-trigger="hover" title="Redo Action"
                                data-bs-content="Redo the last undone stroke or action.">
                                <i class="mdi mdi-redo"></i>
                            </button>

                        </div>
                        <div class="col-lg-12 mt-2">
                            <button type="button" id="saveImageBtn" class="btn btn-sm btn-success" onclick="saveCanvasImage()">Read the Canvas</button>
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
                Swal.fire({
                    title: "Image Saved!",
                    text: "Your image has been saved successfully.\nText Returned: " + data.text,
                    icon: "success",
                    confirmButtonColor: "#727cf5",
                });
                console.log('Image saved successfully: ' + data.file);
            } else {
                Swal.fire({
                    title: "Save Failed",
                    text: "Failed to save image: " + data.error,
                    icon: "error",
                    confirmButtonColor: "#d33",
                });
                console.log('Failed to save image: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Connection Error",
                text: "Error sending image to server:\n" + error,
                icon: "error",
                confirmButtonColor: "#d33",
            });
            console.log('Error sending image to server: ' + error);
        }
    });
}
</script>

<?php include('../theme/footer.php'); ?>