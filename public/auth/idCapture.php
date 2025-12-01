<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ID Verification Component</title>
<link rel="stylesheet" href="assets/idCapture.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<div class="overlay"></div>
<div class="profile-card">
    <h2>Verify Your Identity</h2>
    <p>Please capture or upload a clear image of your Government ID (Driver's License, Passport, etc.).</p>

    <div class="id-preview" id="preview-container">
        <div class="id-preview-text">
            Place ID card here.<br>
            Ensure all four corners are visible.
        </div>
    </div>

    <button class="btn btn-primary" id="camera-btn">
        <svg style="width:20px;height:20px;fill:white" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/><path d="M0 0h24v24H0z" fill="none"/><path d="M20 4h-3.17L15 2H9L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
        <span id="btn-text">Capture ID</span>
    </button>

    <div class="divider">OR</div>

    <input type="file" id="file-upload" accept="image/*">
    <label for="file-upload" class="btn btn-secondary">
        Upload ID Image
    </label>
    
    <button class="btn btn-confirm" id="confirm-btn">
        Confirm ID / Next
    </button> 
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/captureId.js"></script>
</body>
</html>