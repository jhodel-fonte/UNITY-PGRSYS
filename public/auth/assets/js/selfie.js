document.addEventListener('DOMContentLoaded', () => {
    const preview = document.getElementById('preview-container');
    const cameraBtn = document.getElementById('camera-btn');
    const btnText = document.getElementById('btn-text');
    const fileInput = document.getElementById('file-upload');
    const confirmBtn = document.getElementById('confirm-btn');
    const originalPreviewHTML = preview.innerHTML; // Store the original SVG content
    console.log(userId);
    
    // --- Sample ID (replace with actual user ID logic if necessary) ---
    let stream = null;
    let isCameraActive = false;
    let currentPhotoData = null; // Stores the final captured image data (Base64)

    // --- HELPER FUNCTION: TOGGLE CONFIRM BUTTON VISIBILITY ---
    function toggleConfirmButton(show) {
        confirmBtn.style.display = show ? 'block' : 'none';
    }

    // --- HELPER FUNCTION: STOP CAMERA STREAM & UPDATE BUTTON STATE ---
    function stopCamera(isPhotoCaptured = false) {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        isCameraActive = false;

        // Update the Take/Retake button text based on whether a photo is visible
        if (isPhotoCaptured) {
            btnText.innerText = "Retake Photo";
        } else {
            // Revert to initial state if no photo is visible
            btnText.innerText = "Take Photo";
            preview.innerHTML = originalPreviewHTML;
            // Also ensure error state is reset
            cameraBtn.disabled = false;
            cameraBtn.style.backgroundColor = ''; // Reset background color
            cameraBtn.style.pointerEvents = '';
        }
    }

    // --- HELPER FUNCTION: HANDLE CAMERA PERMISSION ERRORS (NEW) ---
    function handleCameraError(errorName) {
        stopCamera();
        toggleConfirmButton(false);

        let message = "Error accessing camera.";
        if (errorName === 'NotAllowedError' || errorName === 'SecurityError') {
            // User denied permission or browser blocked access
            message = 'Camera Access Denied. Allow Camera Permission.';
        } else if (errorName === 'NotFoundError') {
            message = 'No Camera Found on this device.';
        }
        
        // Update the UI for visual feedback
        btnText.innerText = message;
        cameraBtn.disabled = true;
        cameraBtn.style.backgroundColor = '#dc3545'; 
        cameraBtn.style.pointerEvents = 'none';
    }


    // --- *** COMPLETED: HANDLE CONFIRMATION CLICK (AJAX Upload) *** ---
    confirmBtn.addEventListener('click', async () => {
        if (!currentPhotoData) {
            alert("No photo captured or selected to upload.");
            return;
        }

        // Disable button during upload
        confirmBtn.disabled = true;
        confirmBtn.innerText = "Uploading...";

        const uploadData = {
            id: userId, // The user ID to associate the image with
            image_data: currentPhotoData, // The Base64 string of the image
            image_type: 'selfie' // Indicate the type of image
        };
        
        try {
            const response = await fetch('../../app/controllers/uploadImages.php?image=selfie', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(uploadData)
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                // alert(`Upload Successful! File saved as: ${result.filename}`);
                Swal.fire({
                    icon: 'success',
                    title: 'Image Confirmed!',
                    html: `Your Image has been saved.<br><strong>Filename:</strong> ${result.filename}`,
                    timer: 1800,
                    showConfirmButton: false
                });
                setTimeout(() => { window.location.href = `idCapture.php`; }, 1500);
            } else {
                // alert(`Upload Failed: ${result.message || 'Unknown error'}`);
                Swal.fire({
                    icon: 'error',
                    title: 'Verification Failed',
                    text: result.message || 'An unknown error occurred on the server.',
                    confirmButtonText: 'Try Again'
                });
            }

        } catch (error) {
            console.error('Network or server error:', error);
            Swal.fire({
                icon: 'warning',
                title: 'Connection Error',
                text: 'An error occurred while connecting to the verification server. Please try again.',
                confirmButtonText: 'Dismiss'
            });
        } finally {
            // Re-enable button
            confirmBtn.disabled = false;
            confirmBtn.innerText = "Confirm Photo";
        }
    });


    // --- 1. HANDLE FILE UPLOAD ---
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            stopCamera(true); // Stop camera and set button to Retake
            
            const reader = new FileReader();
            reader.onload = function(e) {
                currentPhotoData = e.target.result;
                preview.innerHTML = `<img src="${currentPhotoData}" class="media-content">`;
                toggleConfirmButton(true); // Show the Confirm button
            }
            reader.readAsDataURL(file);
        } else {
             // Handle case where user opens the file dialog but cancels
             if (!currentPhotoData) {
                 stopCamera(false);
             }
        }
    });

    // --- 2. HANDLE CAMERA BUTTON LOGIC (Unchanged) ---
    cameraBtn.addEventListener('click', async () => {
        
        // Scenario A: Camera is active, so we want to CAPTURE (Snap Photo)
        if (isCameraActive) {
            const video = preview.querySelector('video');
            
            // Canvas logic for capturing frame
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            
            // Apply mirroring logic to the canvas capture
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Note: Canvas.toDataURL() returns Base64 dataURL, including the MIME type prefix
            currentPhotoData = canvas.toDataURL('image/jpeg', 0.9); // Use jpeg for smaller file size, 0.9 quality
            preview.innerHTML = `<img src="${currentPhotoData}" class="media-content">`;
            
            stopCamera(true); // Stop camera and set button to Retake
            toggleConfirmButton(true); // Show the Confirm button
            return;
        }

        // Scenario B: Camera is NOT active (Idle or Retake)
        
        // If the button says 'Retake Photo', clear current image and revert to the placeholder
        if (btnText.innerText === "Retake Photo") {
            currentPhotoData = null;
            stopCamera(false); // Clears preview, resets button text/state
            // Now, fall through to START CAMERA logic
        }

        // Start the camera
        try {
            toggleConfirmButton(false); // Hide confirm button before starting camera
            
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            
            const video = document.createElement('video');
            video.srcObject = stream;
            video.autoplay = true;
            video.playsInline = true; // Important for mobile browsers
            video.classList.add('media-content');
            video.style.transform = "scaleX(-1)"; // Mirror the video for selfie view

            preview.innerHTML = '';
            preview.appendChild(video);
            
            video.onloadedmetadata = () => {
                isCameraActive = true;
                btnText.innerText = "Capture Photo";
            };
            
        } catch (err) {
            console.error("Error accessing camera:", err);
            handleCameraError(err.name); 
        }
    });

    // Initialize UI on load
    toggleConfirmButton(false); 
});