const canvas = document.getElementById("responsive-canvas");
const ctx = canvas.getContext("2d");

let drawing = false;
let mode = "default";

// Resize canvas based on parent size
function resizeCanvas() {
    canvas.width = canvas.parentElement.clientWidth;
    canvas.height = canvas.parentElement.clientHeight;
}

window.addEventListener("resize", resizeCanvas);
resizeCanvas();

function setMode(newMode) {
    mode = newMode;
    canvas.style.cursor = mode === "default" ? "default" : "crosshair";
}

function getCoords(e) {
    const rect = canvas.getBoundingClientRect();
    if (e.touches) {
        return {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top,
        };
    } else {
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top,
        };
    }
}

// Mouse Events
canvas.addEventListener("mousedown", (e) => {
    if (mode === "default") return;
    drawing = true;
    const { x, y } = getCoords(e);
    ctx.beginPath();
    ctx.moveTo(x, y);
});

canvas.addEventListener("mousemove", (e) => {
    if (!drawing) return;
    const { x, y } = getCoords(e);
    ctx.lineWidth = mode === "erase" ? 20 : 2;
    ctx.strokeStyle = mode === "erase" ? "aliceblue" : "black";
    ctx.lineTo(x, y);
    ctx.stroke();
});

canvas.addEventListener("mouseup", () => {
    drawing = false;
    ctx.closePath();
});

// Touch Events
canvas.addEventListener("touchstart", (e) => {
    if (mode === "default") return;
    drawing = true;
    const { x, y } = getCoords(e);
    ctx.beginPath();
    ctx.moveTo(x, y);
});

canvas.addEventListener("touchmove", (e) => {
    if (!drawing) return;
    e.preventDefault(); // prevent scrolling while drawing
    const { x, y } = getCoords(e);
    ctx.lineWidth = mode === "erase" ? 20 : 2;
    ctx.strokeStyle = mode === "erase" ? "aliceblue" : "black";
    ctx.lineTo(x, y);
    ctx.stroke();
});

canvas.addEventListener("touchend", () => {
    drawing = false;
    ctx.closePath();
});

function clearCanvas(uid) {
    Swal.fire({
        title: "Restart Canvas",
        text: "Are you sure you want to restart the canvas?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#727cf5",
        cancelButtonColor: "#d33",
        confirmButtonText: "CONFIRM",
    }).then((result) => {
        if (result.isConfirmed) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    });
}
