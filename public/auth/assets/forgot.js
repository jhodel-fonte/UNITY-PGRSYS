document.addEventListener('DOMContentLoaded', () => {
    // DOM Elements
    const mobileForm = document.getElementById('mobile-form');
    const otpForm = document.getElementById('otp-form');
    const instructionText = document.getElementById('form-instruction');
    const mobileInput = document.getElementById('mobile_number');
    const mobileHiddenInput = document.getElementById('mobile_number_hidden');
    const sendCodeBtn = document.getElementById('send-code-btn');
    const resendLink = document.getElementById('resend-code-link');

    // Utility function to show loading state
    function showLoading(message = 'Processing...') {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Function to switch form views
    function switchToOtpForm(mobileNumber) {
        mobileForm.style.display = 'none';
        otpForm.style.display = 'block';
        instructionText.innerHTML = `A verification code has been sent to <strong>${mobileNumber}</strong>. Please check your messages.`;
        mobileHiddenInput.value = mobileNumber;
        Swal.close(); // Close any loading or success messages
    }

    // =======================================================
    // 1. MOBILE NUMBER SUBMISSION (Send Code)
    // =======================================================
    mobileForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const mobileNumber = mobileInput.value.trim();
        if (mobileNumber.length !== 11) {
            Swal.fire('Invalid Input', 'Please enter a valid 11-digit mobile number.', 'warning');
            return;
        }

        // Disable button and show loading
        sendCodeBtn.disabled = true;
        showLoading('Requesting reset code...');

        try {
            const formData = new FormData();
            formData.append('mobileNum', mobileNumber);
            formData.append('action', 'request_otp'); // Action for your backend

            const response = await fetch('../../app/controllers/forgotPasswordController.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Code Sent!',
                    text: data.message || 'Verification code sent successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Switch to the OTP input form
                setTimeout(() => switchToOtpForm(mobileNumber), 1500);

            } else {
                Swal.fire('Error', data.message || 'Failed to send reset code.', 'error');
            }

        } catch (error) {
            console.error('Mobile form submission error:', error);
            Swal.fire('Network Error', 'A network error occurred. Please try again.', 'error');
        } finally {
            sendCodeBtn.disabled = false;
        }
    });

    // =======================================================
    // 2. OTP AND NEW PASSWORD SUBMISSION (Reset Password)
    // =======================================================
    otpForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const otpInput = document.getElementById('otp_input');
        const newPasswordInput = document.getElementById('new_password');

        if (!otpInput.value || !newPasswordInput.value) {
            Swal.fire('Missing Fields', 'Please enter both the verification code and your new password.', 'warning');
            return;
        }

        const submitBtn = otpForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        showLoading('Resetting password...');

        try {
            const formData = new FormData(otpForm);
            formData.append('action', 'reset_password'); // Action for your backend
            
            const response = await fetch('../../app/controllers/forgotPasswordController.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Your password has been reset successfully.',
                    showConfirmButton: false,
                    timer: 2000
                });
                // Redirect user to login page after successful reset
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
                
            } else {
                Swal.fire('Reset Failed', data.message || 'Failed to reset password. Check your code or try again.', 'error');
            }

        } catch (error) {
            console.error('Reset form submission error:', error);
            Swal.fire('Network Error', 'A network error occurred during password reset.', 'error');
        } finally {
            submitBtn.disabled = false;
        }
    });

    // =======================================================
    // 3. RESEND CODE LOGIC
    // =======================================================
    resendLink.addEventListener('click', async (e) => {
        e.preventDefault();

        const mobileNumber = mobileHiddenInput.value;
        if (!mobileNumber) {
            Swal.fire('Error', 'Please enter your mobile number first.', 'error');
            return;
        }

        // Disable link and show loading
        resendLink.classList.add('disabled');
        showLoading('Resending code...');

        try {
            const formData = new FormData();
            formData.append('mobileNum', mobileNumber);
            formData.append('action', 'resend_otp'); // Action for your backend

            const response = await fetch('../../app/controllers/forgotPasswordController.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Code Resent!',
                    text: data.message || 'New verification code has been sent.',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to resend code.', 'error');
            }
        } catch (error) {
            console.error('Resend error:', error);
            Swal.fire('Network Error', 'A network error occurred while resending the code.', 'error');
        } finally {
            resendLink.classList.remove('disabled');
        }
    });

});