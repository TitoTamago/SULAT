const canvas = document.getElementById("responsive-canvas");
const ctx = canvas.getContext("2d");

let drawing = false;
let mode = "default";

// --- UNDO/REDO STACKS ---
let history = [];
let redoStack = [];
const MAX_HISTORY = 50; // optional limit for memory safety

// Resize canvas based on parent size
function resizeCanvas() {
    const tempImage = ctx.getImageData(0, 0, canvas.width, canvas.height); // preserve content
    canvas.width = canvas.parentElement.clientWidth;
    canvas.height = canvas.parentElement.clientHeight;
    ctx.putImageData(tempImage, 0, 0); // restore after resize
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

// --- SAVE STATE FUNCTION ---
function saveState() {
    if (history.length >= MAX_HISTORY) history.shift();
    history.push(canvas.toDataURL());
    redoStack = []; // clear redo stack after new action
}

// --- RESTORE STATE FUNCTION ---
function restoreState(imageDataURL) {
    const img = new Image();
    img.src = imageDataURL;
    img.onload = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
    };
}

// --- MOUSE EVENTS ---
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
    if (!drawing) return;
    drawing = false;
    ctx.closePath();
    saveState(); // save snapshot after stroke ends
});

// --- TOUCH EVENTS ---
canvas.addEventListener("touchstart", (e) => {
    if (mode === "default") return;
    drawing = true;
    const { x, y } = getCoords(e);
    ctx.beginPath();
    ctx.moveTo(x, y);
});

canvas.addEventListener("touchmove", (e) => {
    if (!drawing) return;
    e.preventDefault(); // prevent scrolling
    const { x, y } = getCoords(e);
    ctx.lineWidth = mode === "erase" ? 20 : 2;
    ctx.strokeStyle = mode === "erase" ? "aliceblue" : "black";
    ctx.lineTo(x, y);
    ctx.stroke();
});

canvas.addEventListener("touchend", () => {
    if (!drawing) return;
    drawing = false;
    ctx.closePath();
    saveState();
});

// --- CLEAR CANVAS ---
function clearCanvas() {
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
            history = [];
            redoStack = [];
        }
    });
}

// --- UNDO ---
function undo() {
    if (history.length > 0) {
        const lastState = history.pop();
        redoStack.push(canvas.toDataURL());
        const prevState = history[history.length - 1];
        if (prevState) {
            restoreState(prevState);
        } else {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    } else {
        Swal.fire("Nothing to undo!", "", "info");
    }
}

// --- REDO ---
function redo() {
    if (redoStack.length > 0) {
        const nextState = redoStack.pop();
        history.push(canvas.toDataURL());
        restoreState(nextState);
    } else {
        Swal.fire("Nothing to redo!", "", "info");
    }
}

// Initialize empty canvas in history
saveState();
