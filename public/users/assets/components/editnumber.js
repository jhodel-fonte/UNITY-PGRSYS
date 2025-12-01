document.addEventListener('DOMContentLoaded', function() {
    // JavaScript for the Edit Mobile Number popup
    document.getElementById('editMobileBtn').addEventListener('click', function() {
        const currentMobile = document.querySelector('input[name="mobileNum"]').value;

        Swal.fire({
            title: 'Edit Mobile Number',
            input: 'text',
            inputLabel: 'Enter new mobile number',
            inputValue: currentMobile,
            inputValidator: (value) => {
                if (!value) {
                    return 'Mobile number cannot be empty!';
                }
                const phoneRegex = /^[0-9+\-\s()]+$/;
                if (!phoneRegex.test(value)) {
                    return 'Please enter a valid mobile number!';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Update',
            preConfirm: (newMobile) => {
                // Request OTP for the new mobile number
                return fetch(`../app/controllers/otp/otp.php?otp=request&number=${encodeURIComponent(newMobile)}`, {
                    method: 'GET',
                })
                .then(response => {
                    if (!response.ok) {
                        // Handle HTTP errors (e.g., 400 for bad requests)
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Check the response message based on your PHP script
                    if (data.message === 'Success') {
                        // Success: Proceed to OTP popup
                        console.log(data.secretOtp);
                        return { mobileNum: newMobile };
                    } else {
                        // Failure: Use the message from the response
                        throw new Error(data.message || 'OTP request failed');
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // OTP requested successfully; show OTP input popup
                showOtpPopup(result.value.mobileNum);
            }
        });
    });
});
