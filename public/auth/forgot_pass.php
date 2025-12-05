<?php
session_start();

$Otp = '';
if (isset($_SESSION['secretOtp'] )) {
    $Otp = $_SESSION['secretOtp'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - PGSRS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/style.css"> 
    <style>
        /* Hidden fields for Step 2 (Reset form) - Keeping essential JS functionality styles */
        #otp-form, #reset-form {
            display: none;
        }
    </style>
</head>
<body>

    <div class="forgot-bg">
        <div class="overlay"></div>

        <div class="container d-flex justify-content-center align-items-center min-vh-100">

            <div class="forgot-card p-5 text-center shadow-lg">
                <a href="index.php">
                    <img src="assets/img/logo.png" alt="UNITY PGSRS Logo" class="mb-2" style="width: 80px;">
                </a>

                <h3 class="fw-bold title mb-3">Forgot Password</h3>
                <p class="small mb-4" id="form-instruction">
                    Enter your registered mobile number to receive a password reset code.
                </p>

                <form id="mobile-form" action="forgot_process.php" method="POST">
                    <div class="mb-4 text-start">
                        <label for="mobile_number" class="form-label">Mobile Number</label>
                        <input 
                            type="text" 
                            id="mobile_number"
                            name="mobile_number" 
                            class="form-control form-control-lg" 
                            placeholder="09XXXXXXXXX" 
                            maxlength="11"
                            required>
                    </div>
                    <button type="submit" class="btn w-100 py-2 mb-4" id="send-code-btn">Send Reset Code</button>
                </form>

                <form id="otp-form" action="reset_process.php" method="POST">
                    <input type="hidden" name="mobile_number_hidden" id="mobile_number_hidden">
                    
                    <div class="mb-4 text-start">
                        <label for="otp_input" class="form-label">Verification Code (OTP)</label>
                        <input 
                            type="number" 
                            id="otp_input"
                            name="otp" 
                            class="form-control form-control-lg" 
                            placeholder="Enter 6-digit code"
                            maxlength="6"
                            required>
                    </div>

                    <div class="mb-4 text-start">
                        <label for="new_password" class="form-label">New Password</label>
                        <input 
                            type="password" 
                            id="new_password"
                            name="new_password" 
                            class="form-control form-control-lg" 
                            placeholder="••••••••"
                            required>
                    </div>
                    
                    <div class="mb-4 text-start">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            id="confirm_password"
                            name="confirm_password" 
                            class="form-control form-control-lg" 
                            placeholder="••••••••"
                            required>
                    </div>
                    

                    <button type="submit" class="btn w-100 py-2 mb-4">Reset Password</button>
                    <a href="#" class="link text-decoration-none small" id="resend-code-link">Resend Code</a>
                </form>
                
                <div class="mt-3">
                    <a href="login.php" class="link text-decoration-none small">Back to Login</a>
                </div>
                
                <div id="message-container" class="mt-3"></div>

            </div>

        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/forgot.js"></script>
    <script>console.log('<?= $Otp ?>')</script>
</body>
</html>