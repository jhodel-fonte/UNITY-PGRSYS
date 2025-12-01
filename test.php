<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    /* 1. Page Background (Simulating your building background) */
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
        background-size: cover;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* 2. The Overlay/Backdrop */
    .overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5); /* Dim the background */
        backdrop-filter: blur(5px); /* Modern blur effect */
        z-index: 1;
    }

    /* 3. The Main Card */
    .profile-card {
        position: relative;
        z-index: 2;
        background: #ffffff;
        width: 100%;
        max-width: 400px;
        padding: 40px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        text-align: center;
    }

    /* Typography */
    .profile-card h2 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 24px;
    }
    
    .profile-card p {
        margin: 0 0 30px 0;
        color: #666;
        font-size: 14px;
    }

    /* 4. The Profile Preview Circle */
    .image-preview {
        width: 150px;
        height: 150px;
        background-color: #f0f2f5;
        border-radius: 50%;
        margin: 0 auto 30px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
    }

    /* Placeholder Icon inside the circle */
    .image-preview svg {
        width: 60px;
        height: 60px;
        fill: #cbd5e0;
    }

    /* 5. The Buttons */
    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.1s ease;
        margin-bottom: 12px;
    }

    .btn:active {
        transform: scale(0.98);
    }

    .btn-primary {
        background-color: #007bff; /* The Blue from your image */
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-primary:hover {
        background-color: #0069d9;
    }

    .btn-secondary {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
        width: 200px;
        height: 50px;
    
        margin: 12px auto; 
        display: flex;     
        
        align-items: center;    
        justify-content: center;
        
        
        padding: 0; 
    }

    .btn-secondary:hover {
        background-color: #e2e6ea;
    }

    /* Hide the ugly default file input */
    #file-upload {
        display: none;
    }
    
    .divider {
        margin: 20px 0;
        color: #aaa;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .divider::before, .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }

</style>
</head>
<body>

<div class="overlay"></div>

<div class="profile-card">
    <h2>Set Profile Picture</h2>
    <p>Take a selfie or upload a photo to get started</p>

    <div class="image-preview" id="preview-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>

    <button class="btn btn-primary">
        <svg style="width:20px;height:20px;fill:white" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/><path d="M0 0h24v24H0z" fill="none"/><path d="M20 4h-3.17L15 2H9L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-5 11.5V13H9v2.5L5.5 12 9 8.5V11h6V8.5l3.5 3.5-3.5 3.5z" opacity="0"/><circle cx="12" cy="12" r="3.2"/><path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
        Take Photo
    </button>

    <div class="divider">OR</div>

    <input type="file" id="file-upload" accept="image/*">
    <label for="file-upload" class="btn btn-secondary">
        Upload from Gallery
    </label>
</div>

</body>
</html>