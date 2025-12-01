// HTML Elements
const video = document.getElementById("camera");
const canvas = document.getElementById("snapshot");
const captureBtn = document.getElementById("captureBtn");
const retakeBtn = document.getElementById("retakeBtn");
const useBtn = document.getElementById("useBtn");

// Request camera permission
navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
    .then(stream => video.srcObject = stream)
    .catch(() => alert("Camera permission denied!"));

// Capture photo
captureBtn.addEventListener("click", () => {
    const ctx = canvas.getContext("2d");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    ctx.drawImage(video, 0, 0);

    video.style.display = "none";
    canvas.style.display = "block";

    captureBtn.style.display = "none";
    retakeBtn.style.display = "block";
    useBtn.style.display = "block";
});

// Retake
retakeBtn.addEventListener("click", () => {
    video.style.display = "block";
    canvas.style.display = "none";

    captureBtn.style.display = "block";
    retakeBtn.style.display = "none";
    useBtn.style.display = "none";
});

// Use Photo (no backend yet)
useBtn.addEventListener("click", () => {
    const imageData = canvas.toDataURL("image/jpeg", 0.9);
    localStorage.setItem("capturedImage", imageData);

    alert("Photo selected! (For backend dev later)");
});
