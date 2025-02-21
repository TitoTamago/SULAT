<?php include("../theme/header.html"); ?>

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
    z-index: 10;
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

<div class="content-page">
    <div class="content">

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
                                    <button type="button" id="snipButton" class="btn btn-sm btn-primary" onclick="setMode('draw')"><i class="mdi mdi-draw"></i></button>
                                    <button type="button" id="snipButton" class="btn btn-sm btn-danger" onclick="setMode('erase')"><i class="mdi mdi-eraser"></i></button>
                                    <button type="button" id="snipButton" class="btn btn-sm btn-warning" onclick="setMode('default')"><i class="mdi mdi-cursor-default"></i></button>
                                    <button type="button" id="snipButton" class="btn btn-sm btn-success"><i class="mdi mdi-content-cut"></i></button>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div>
        <!-- container -->

    </div>
    <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->


<script>
const canvas = document.getElementById('responsive-canvas');

const ctx = canvas.getContext("2d");
let drawing = false;
let mode = "default";

// Function to resize the canvas dynamically to fit the screen
function resizeCanvas() {
    const canvas = document.getElementById("responsive-canvas");
    canvas.width = canvas.parentElement.clientWidth;
    canvas.height = canvas.parentElement.clientHeight;
}

window.addEventListener("resize", resizeCanvas);
resizeCanvas();

// Function to change the drawing mode (pen, eraser, or default mouse)
function setMode(newMode) {
    mode = newMode;
    canvas.style.cursor = mode === "default" ? "default" : "crosshair";
}

// Event listener for mouse down to start drawing
canvas.addEventListener("mousedown", (e) => {
    if (mode === "default") return;
    drawing = true;
    const rect = canvas.getBoundingClientRect();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
});

// Event listener for mouse move to draw or erase
canvas.addEventListener("mousemove", (e) => {
    if (!drawing) return;
    ctx.lineWidth = mode === "erase" ? 20 : 2;
    ctx.strokeStyle = mode === "erase" ? "aliceblue" : "black";
    const rect = canvas.getBoundingClientRect();
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.stroke();
});

// Event listener for mouse up to stop drawing
canvas.addEventListener("mouseup", () => {
    drawing = false;
    ctx.closePath();
});
</script>

<?php include("../theme/footer.html"); ?>