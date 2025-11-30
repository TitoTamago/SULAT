document.addEventListener("click", function (e) {
    console.log("Y Position:", e.clientY);
    console.log("X Position:", e.clientX);
});

function fullscreenSnip() {
    setTimeout(() => {
        html2canvas(document.body).then((canvas) => {
            const image = canvas.toDataURL("image/png");

            // Send the base64 image to PHP for saving in the database
            saveScreenshotToDatabase(image);
        });
    }, 500); // 500ms delay
}

document.getElementById("captureScreenshot").addEventListener("click", function () {
    enableSelection();
});

function enableSelection() {

        // Disable scroll during selection
        document.body.style.touchAction = "none";
    let selectionBox = null;
    let startX, startY, endX, endY;
    let isSelecting = false;

    const getPos = (e) => {
        return {
            x: e.clientX + window.scrollX,
            y: e.clientY + window.scrollY,
            rawX: e.clientX,
            rawY: e.clientY
        };
    };
    
    const onPointerDown = (e) => {
        if (e.button !== 0 && e.pointerType === "mouse") return; // Only left-click for mouse
        
        isSelecting = true;
        const pos = getPos(e);

        startX = pos.x;
        startY = pos.y;

        selectionBox = document.createElement("div");
        selectionBox.style.position = "absolute";
        selectionBox.style.zIndex = "10000";
        selectionBox.style.border = "2px dashed red";
        selectionBox.style.background = "rgba(255, 0, 0, 0.2)";
        selectionBox.style.left = `${pos.rawX}px`;
        selectionBox.style.top = `${pos.rawY}px`;

        document.body.appendChild(selectionBox);
    };

    const onPointerMove = (e) => {
        if (!isSelecting || !selectionBox) return;
        e.preventDefault(); // 🚀 stops scrolling on mobile while dragging

        const pos = getPos(e);

        endX = pos.x;
        endY = pos.y;

        selectionBox.style.width = `${Math.abs(pos.rawX - (startX - window.scrollX))}px`;
        selectionBox.style.height = `${Math.abs(pos.rawY - (startY - window.scrollY))}px`;
        selectionBox.style.left = `${Math.min(startX - window.scrollX, pos.rawX)}px`;
        selectionBox.style.top = `${Math.min(startY - window.scrollY, pos.rawY)}px`;
    };


    const onPointerUp = async (e) => {
        if (!isSelecting) return;
        isSelecting = false;
        
        // Re-enable scroll after selection
        document.body.style.touchAction = "auto";

        document.removeEventListener("pointerdown", onPointerDown);
        document.removeEventListener("pointermove", onPointerMove);
        document.removeEventListener("pointerup", onPointerUp);

        if (selectionBox) selectionBox.remove();

        const captureArea = {
            x: Math.min(startX, endX),
            y: Math.min(startY, endY),
            width: Math.abs(endX - startX),
            height: Math.abs(endY - startY)
        };

        const canvas = await html2canvas(document.body, {
            x: captureArea.x - window.scrollX,
            y: captureArea.y - window.scrollY,
            width: captureArea.width,
            height: captureArea.height,
            scrollX: window.scrollX,
            scrollY: window.scrollY,
            useCORS: true,
            allowTaint: true,
        });

        openEditor(canvas);
    };

    document.addEventListener("pointerdown", onPointerDown);
    document.addEventListener("pointermove", onPointerMove);
    document.addEventListener("pointerup", onPointerUp);
}

function openEditor(canvas) {
    // Remove any existing editor modal
    const existingEditor = document.getElementById("screenshotEditor");
    if (existingEditor) {
        existingEditor.remove();
    }

    // Check if canvas has valid dimensions
    if (canvas.width === 0 || canvas.height === 0) {
        alert("Screenshot failed: canvas is empty.");
        return;
    }

    // Create a modal to display the canvas
    const editor = document.createElement("div");
    editor.id = "screenshotEditor";

    const scrollTop = window.scrollY;
    const docHeight = document.body.scrollHeight;

    editor.innerHTML = `
    <div style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: ${docHeight}px;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
    ">
        <div style="
        position: absolute;
        top: ${scrollTop + window.innerHeight / 2}px;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
        max-width: 90vw;
        max-height: 90vh;
        overflow: auto;
        ">
        <div style="text-align: center;">
            <canvas id="screenshotCanvas" style="max-width: 100%; height: auto; border: 1px solid #ccc;"></canvas>
        </div>
        <div style="margin-top: 10px; text-align: right;">
            <button type="button" class="btn btn-success mb-2 me-1" id="saveScreenshot">Save</button>
            <button type="button" class="btn btn-danger mb-2 me-1" id="cancelScreenshot">Cancel</button>
        </div>
        </div>
    </div>
    `;

    document.body.appendChild(editor);

    const screenshotCanvas = editor.querySelector("#screenshotCanvas");
    const ctx = screenshotCanvas.getContext("2d");

    screenshotCanvas.width = canvas.width;
    screenshotCanvas.height = canvas.height;
    ctx.drawImage(canvas, 0, 0);

    document.getElementById("saveScreenshot").addEventListener("click", () => {
        // Convert canvas to base64 image string
        const base64Image = screenshotCanvas.toDataURL("image/png");
        // Send the base64 image to the server
        saveScreenshotToDatabase(base64Image);
        closeEditor();
    });

    document.getElementById("cancelScreenshot").addEventListener("click", () => {
        closeEditor();
    });
}

function closeEditor() {
    const editor = document.getElementById("screenshotEditor");
    if (editor) {
        editor.remove();
    }
}

function downloadImage(dataUrl, filename) {
    let link = document.createElement("a");
    link.href = dataUrl;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function saveScreenshotToDatabase(base64Image) {
    $.ajax({
        url: "../includes/js.feature.functions/save-screenshot.php",
        type: "POST",
        data: {
            screenshot: base64Image,
        },
        dataType: "json",
        success: function (data) {
            if (data.success) {
                alert("Screenshot saved successfully!");
            } else {
                let errorMessage = data.error ? data.error : "Failed to save screenshot.";
                alert("Error: " + errorMessage);
                console.error("Server error:", data);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log("Response Text:", xhr.responseText);
        },
    });
}
