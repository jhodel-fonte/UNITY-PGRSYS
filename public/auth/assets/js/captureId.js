    document.addEventListener('DOMContentLoaded', () => {
        const preview = document.getElementById('preview-container');
        const cameraBtn = document.getElementById('camera-btn');
        const btnText = document.getElementById('btn-text');
        const fileInput = document.getElementById('file-upload');
        const confirmBtn = document.getElementById('confirm-btn');
        
        let stream = null;
        let isCameraActive = false;
        let currentPhotoData = null; // Holds the Base64 image string
        
        const PLACEHOLDER_HTML = `<div class="id-preview-text">Place ID card here.<br>Ensure all four corners are visible.</div>`;


        // --- CORE FUNCTION: START CAMERA ---
        async function startCamera() {
            
            toggleConfirmButton(false); 
            
            cameraBtn.classList.remove('btn-warning');
            cameraBtn.style.cursor = 'pointer'; 
            
            if (btnText.innerText === "Retake ID") {
                currentPhotoData = null;
                preview.innerHTML = PLACEHOLDER_HTML;
            }

            stopCamera(false); 

            try {
                const constraints = {
                    video: {
                        facingMode: 'environment' // Rear camera for document scanning
                    }
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                
                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;
                video.playsInline = true;
                video.classList.add('media-content');

                preview.innerHTML = ''; 
                preview.classList.add('video-active');
                preview.appendChild(video);
                
                video.onloadedmetadata = () => {
                    isCameraActive = true;
                    btnText.innerText = "Snap ID";
                };
                
            } catch (err) {
                console.error("Error accessing camera:", err);
                
                cameraBtn.classList.add('btn-warning');
                btnText.innerText = "Camera Permission Denied";
                cameraBtn.style.cursor = 'not-allowed';

                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                     // Using console log instead of alert for better UX in a production environment
                     console.log("Access to the camera was denied. Please check your browser settings and try again.");
                } else {
                     console.log("Could not start camera: " + err.name);
                }
                
                preview.innerHTML = PLACEHOLDER_HTML;
                isCameraActive = false;
                preview.classList.remove('video-active'); 
            }
        }


        // --- HELPER FUNCTION: TOGGLE CONFIRM BUTTON VISIBILITY ---
        function toggleConfirmButton(show) {
            confirmBtn.style.display = show ? 'block' : 'none';
        }

        // --- HELPER FUNCTION: STOP CAMERA STREAM ---
        function stopCamera(stopUIUpdate = true) {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            isCameraActive = false;
            preview.classList.remove('video-active');
            
            if (stopUIUpdate) {
                if(!preview.querySelector('img')) {
                     cameraBtn.classList.remove('btn-warning');
                     cameraBtn.style.cursor = 'pointer';
                }

                if(preview.querySelector('img')) {
                     btnText.innerText = "Retake ID";
                } else {
                     btnText.innerText = "Capture ID";
                }
            }
        }

        // --- HELPER FUNCTION: HANDLE CONFIRMATION CLICK (UPDATED) ---
        confirmBtn.addEventListener('click', async () => {
            if (!currentPhotoData) {
                console.error("No image data available to confirm.");
                return;
            }

            stopCamera();
            console.log("Attempting to send image data to server...");
            
            try {
                // Send the Base64 image data to the PHP handler
                const response = await fetch('../../app/controllers/uploadImages.php?image=id', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        imageData: currentPhotoData
                    })
                });

                const result = await response.json();

                if (result.success) {
                    console.log("Server success:", result.message, "File:", result.filename);
                    // Provide success feedback to the user
                    // In a real app, you would redirect or show a "success" state
                    // alert(`ID confirmed and saved! Filename: ${result.filename}`); 
                    Swal.fire({
                        icon: 'success',
                        title: 'ID Confirmed!',
                        html: `Your ID has been saved.<br><strong>Filename:</strong> ${result.filename}`,
                        timer: 1800,
                        showConfirmButton: false
                    });
                    setTimeout(() => { window.location.href = `../redirecting.php`; }, 1500);
                } else {
                    console.error("Server error:", result.message);
                    // alert(`Verification Failed: ${result.message}`);
                    Swal.fire({
                        icon: 'error',
                        title: 'Verification Failed',
                        text: result.message || 'An unknown error occurred on the server.',
                        confirmButtonText: 'Try Again'
                    });
                }

            } catch (error) {
                console.error("Fetch failed:", error);
                // alert("An error occurred while connecting to the verification server.");
                Swal.fire({
                    icon: 'warning',
                    title: 'Connection Error',
                    text: 'An error occurred while connecting to the verification server. Please try again.',
                    confirmButtonText: 'Dismiss'
                });
            }
        });


        // --- 1. HANDLE FILE UPLOAD ---
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                stopCamera(); 
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentPhotoData = e.target.result;
                    preview.innerHTML = `<img src="${currentPhotoData}" class="media-content">`;
                    toggleConfirmButton(true); 
                }
                reader.readAsDataURL(file);
            }
        });

        // --- 2. HANDLE CAMERA BUTTON LOGIC ---
        cameraBtn.addEventListener('click', async () => {
            
            if (cameraBtn.classList.contains('btn-warning')) {
                console.log("Camera access is required. Please grant permission in your browser settings.");
                return;
            }

            // Scenario A: Capture Photo
            if (isCameraActive) {
                const video = preview.querySelector('video');
                
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                currentPhotoData = canvas.toDataURL('image/png');
                preview.innerHTML = `<img src="${currentPhotoData}" class="media-content">`;
                
                stopCamera(); 
                toggleConfirmButton(true); 
                return;
            }

            // Scenario B: Start Camera
            if (btnText.innerText === "Retake ID") {
                currentPhotoData = null;
                preview.innerHTML = PLACEHOLDER_HTML;
                toggleConfirmButton(false);
            }
            
            await startCamera(); 
        });

        // Initialize UI on load
        toggleConfirmButton(false); 
    });