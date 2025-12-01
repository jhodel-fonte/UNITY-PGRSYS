// Function to show OTP input popup after mobile number update
function showOtpPopup(newMobile) {
    Swal.fire({
        title: 'Verify OTP',
        text: 'Enter the OTP sent to your new mobile number.',
        input: 'text',
        inputPlaceholder: 'Enter 6-digit OTP',
        inputValidator: (value) => {
            if (!value) {
                return 'OTP cannot be empty!';
            }
            const otpRegex = /^[0-9]{6}$/;  // Assumes 6-digit OTP; adjust as needed
            if (!otpRegex.test(value)) {
                return 'Please enter a valid 6-digit OTP!';
            }
        },
        showCancelButton: true,
        confirmButtonText: 'Verify',
        preConfirm: (otp) => {
            Swal.showLoading();
            return fetch('../app/controllers/otp/otp.php?otp=verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    otp: otp,
                    mobileNum: newMobile
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Fix: Check for 'message' key instead of 'success' (based on your PHP response)
                if (data.message === 'Success Otp Verification') {
                    // Success: Return data to proceed
                    return data;
                } else {
                    // Failure: Throw error with the message from PHP
                    throw new Error(data.message || 'OTP verification failed');
                }
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error.message}`);
            });
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // OTP verified; update the mobile number field
            document.querySelector('input[name="mobileNum"]').value = newMobile;
            Swal.fire('Success!', 'Your mobile number has been updated.', 'success');
        }
    });
}
