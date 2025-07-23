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
    let selectionBox = null;
    let startX, startY, endX, endY;
    let isSelecting = false;

    // The mouse down event to start selection
    const onMouseDown = (e) => {
        if (e.button !== 0) return; // Only left-click to start selection
        isSelecting = true;

        // Get the starting position (account for scroll)
        mouseX = e.pageX;
        mouseY = e.pageY;

        // Get the starting position (account for scroll)
        startX = e.pageX + window.scrollX;
        startY = e.pageY + window.scrollY;

        // Create the selection box
        selectionBox = document.createElement("div");
        selectionBox.style.position = "absolute";
        selectionBox.style.zIndex = "10000"; // high z-index to ensure it is on top
        selectionBox.style.border = "2px dashed red";
        selectionBox.style.background = "rgba(255, 0, 0, 0.2)";
        selectionBox.style.left = `${mouseX}px`;
        selectionBox.style.top = `${mouseY}px`;
        document.body.appendChild(selectionBox);
    };

    // The mouse move event to resize and reposition the selection box
    const onMouseMove = (e) => {
        if (!isSelecting || !selectionBox) return;

        // Get the end position (account for scroll)
        mouse_endX = e.pageX;
        mouse_endY = e.pageY;

        // Get the end position (account for scroll)
        endX = e.pageX + window.scrollX;
        endY = e.pageY + window.scrollY;

        // Update the size and position of the selection box
        selectionBox.style.width = `${Math.abs(mouse_endX - mouseX)}px`;
        selectionBox.style.height = `${Math.abs(mouse_endY - mouseY)}px`;
        selectionBox.style.left = `${Math.min(mouseX, mouse_endX)}px`;
        selectionBox.style.top = `${Math.min(mouseY, mouse_endY)}px`;
    };

    // The mouse up event to capture the selection area
    const onMouseUp = async (e) => {
        if (!isSelecting) return;
        isSelecting = false;

        // Remove event listeners after the selection is done
        document.removeEventListener("mousedown", onMouseDown);
        document.removeEventListener("mousemove", onMouseMove);
        document.removeEventListener("mouseup", onMouseUp);

        // Remove the selection box
        if (selectionBox) {
            document.body.removeChild(selectionBox);
        }

        // Calculate the selected area based on mouse positions
        const captureArea = {
            x: Math.min(startX, endX),
            y: Math.min(startY, endY),
            width: Math.abs(endX - startX),
            height: Math.abs(endY - startY),
        };

        // Use html2canvas to capture the selected area (adjusted for scroll)
        const canvas = await html2canvas(document.body, {
            x: captureArea.x - window.scrollX, // Adjust x based on scroll position
            y: captureArea.y - window.scrollY, // Adjust y based on scroll position
            width: captureArea.width,
            height: captureArea.height,
            scrollX: window.scrollX, // Adjust the scroll position for accurate capture
            scrollY: window.scrollY, // Adjust the scroll position for accurate capture
            useCORS: true, // Enable CORS to capture content from other origins
            allowTaint: true, // Allow tainted images to be captured
        });

        // Open the editor with the captured canvas
        openEditor(canvas);
    };

    // Add event listeners for mouse actions
    document.addEventListener("mousedown", onMouseDown);
    document.addEventListener("mousemove", onMouseMove);
    document.addEventListener("mouseup", onMouseUp);
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
