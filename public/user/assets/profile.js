// --- Element Selectors ---
const infoDisplay = document.getElementById('infoDisplay');
const editInfoForm = document.getElementById('editInfoForm');
const changePassForm = document.getElementById('changePassForm');

const editInfoBtn = document.getElementById('editInfoBtn');
const changePassBtn = document.getElementById('changePassBtn');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const cancelPassBtn = document.getElementById('cancelPassBtn');

// Since the mobile number input is inside the edit form, 
// we'll get its initial value from the display section or the PHP variable later.

// --- Toggling Functions ---

/**
 * Toggles the visibility between the information display and a form.
 * Hides any other open form.
 * @param {HTMLElement | string} formToShow - The form element to display or 'display' string.
 */
function toggleForms(formToShow) {
    // Hide all
    infoDisplay.classList.add('d-none');
    editInfoForm.classList.add('d-none');
    changePassForm.classList.add('d-none');

    // Show the required elements
    if (formToShow === 'display') {
        infoDisplay.classList.remove('d-none');
        editInfoBtn.classList.remove('d-none');
        changePassBtn.classList.remove('d-none');
    } else {
        formToShow.classList.remove('d-none');
        // Hide the main buttons when a form is open
        editInfoBtn.classList.add('d-none');
        changePassBtn.classList.add('d-none');
    }
}

// --- Event Listeners for Toggling Forms ---

editInfoBtn.addEventListener('click', () => {
    toggleForms(editInfoForm);
});

changePassBtn.addEventListener('click', () => {
    toggleForms(changePassForm);
});

cancelEditBtn.addEventListener('click', () => {
    toggleForms('display');
});

cancelPassBtn.addEventListener('click', () => {
    toggleForms('display');
});


// --- Standard AJAX Submission Helper ---

async function submitAjaxForm(url, formData, successCallback, successMessage) {
    try {
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we update your data.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            Swal.fire('Success!', successMessage, 'success');
            if (successCallback) {
                successCallback(result);
            }
        } else {
            Swal.fire('Error!', result.message || 'An unexpected error occurred.', 'error');
        }

    } catch (error) {
        console.error('AJAX Submission Error:', error);
        Swal.fire('Network Error', 'Could not connect to the server. Please try again.', 'error');
    }
}


// --- 1. Edit Information Form Handler (Excluding Mobile) ---

editInfoForm.addEventListener('submit', function(e) {
    e.preventDefault();

    // Collect data from the form inputs
    const formData = new FormData();
    const inputs = this.querySelectorAll('input, select');
    
    // NOTE: Ensure your HTML inputs have 'name' attributes matching these keys
    const fieldNames = [
        'first_name', 'last_name', 'mobile', 'email', 
        'gender', 'dob', 'address', 'username'
    ];

    inputs.forEach((input, index) => {
        // We'll use the 'name' attribute if available, otherwise fallback to the index list (less reliable)
        const name = input.name || fieldNames[index];
        formData.append(name, input.value); 
    });

    submitAjaxForm(
        'ajax/update_profile.php', 
        formData,
        (result) => {
            // Success Callback: Reload the page to display new PHP data
            window.location.reload(); 
        },
        'Your profile information has been successfully updated!'
    );
});


// --- 2. Change Password Form Handler ---

changePassForm.addEventListener('submit', function(e) {
    e.preventDefault();

    // Get input values (assuming name attributes are used for current, new, confirm)
    const currentPass = this.querySelector('input[name="current_password"]').value;
    const newPass = this.querySelector('input[name="new_password"]').value;
    const confirmPass = this.querySelector('input[name="confirm_password"]').value;

    if (newPass !== confirmPass) {
        Swal.fire('Error', 'New password and confirmation password do not match.', 'warning');
        return;
    }

    if (!currentPass || !newPass || !confirmPass) {
        Swal.fire('Error', 'Please fill out all password fields.', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('current_password', currentPass);
    formData.append('new_password', newPass);

    submitAjaxForm(
        'ajax/update_password.php', 
        formData,
        (result) => {
            // Success Callback: Clear the form and hide it
            changePassForm.reset(); 
            toggleForms('display');
        },
        'Your password has been successfully updated!'
    );
});


// --- 3. Mobile Number Update (OTP Flow) ---

// Find the mobile number input inside the edit form to replace it after update
const mobileEditInput = editInfoForm.querySelector('input[name="mobile"]');

// This requires a new button, ideally near the mobile number display/input.
const editMobileBtn = document.getElementById('editMobileOTPBtn'); 

if (editMobileBtn) {
    editMobileBtn.addEventListener('click', function() {
        // Get the current mobile number from the display section's inner HTML
        // You might need to adjust the selector based on how you want to present this button.
        const currentMobileElement = infoDisplay.querySelector('.col-md-6:nth-child(1)');
        const currentMobile = currentMobileElement ? currentMobileElement.textContent.trim().split(':')[1].trim() : '';

        if (!currentMobile) {
             Swal.fire('Error', 'Could not determine current mobile number.', 'error');
             return;
        }

        Swal.fire({
            title: 'Edit Mobile Number',
            html: 
                `<p>Current Number: <strong>${currentMobile}</strong></p>` +
                '<input id="swal-input-mobile" class="swal2-input" type="text" placeholder="Enter new mobile number">',
            focusConfirm: false,
            preConfirm: () => {
                const newMobile = document.getElementById('swal-input-mobile').value;

                // --- Validation Check ---
                if (!newMobile) {
                    Swal.showValidationMessage('Mobile number cannot be empty!');
                    return false;
                }
                const phoneRegex = /^[0-9+\-\s()]{7,20}$/; 
                if (!phoneRegex.test(newMobile)) {
                    Swal.showValidationMessage('Please enter a valid mobile number!');
                    return false;
                }
                if (newMobile === currentMobile) {
                    Swal.showValidationMessage('The new number is the same as the current number.');
                    return false;
                }

                // --- AJAX: Request OTP for the new mobile number ---
                Swal.showLoading();
                return fetch(`../app/controllers/otp/otp.php?otp=request&number=${encodeURIComponent(newMobile)}`, {
                    method: 'GET',
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message === 'Success') {
                        return { mobileNum: newMobile };
                    } else {
                        throw new Error(data.message || 'OTP request failed');
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                });
            },
            showCancelButton: true,
            confirmButtonText: 'Request OTP',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                showOtpPopup(result.value.mobileNum);
            }
        });
    });
}

// Function to show OTP input popup after mobile number update
function showOtpPopup(newMobile) {
    Swal.fire({
        title: 'Verify OTP',
        html: 
            `<p>Enter the OTP sent to <strong>${newMobile}</strong>.</p>` +
            '<input id="swal-input-otp" class="swal2-input" type="text" placeholder="Enter 6-digit OTP">',
        focusConfirm: false,
        preConfirm: () => {
            const otp = document.getElementById('swal-input-otp').value;

            // --- Validation Check ---
            if (!otp) {
                Swal.showValidationMessage('OTP cannot be empty!');
                return false;
            }
            const otpRegex = /^[0-9]{6}$/;
            if (!otpRegex.test(otp)) {
                Swal.showValidationMessage('Please enter a valid 6-digit OTP!');
                return false;
            }

            // --- AJAX: Verify OTP ---
            Swal.showLoading();
            return fetch('../app/controllers/otp/otp.php?otp=verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    otp: otp,
                    mobileNum: newMobile // Send the new mobile number
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.message === 'Success Otp Verification') {
                    // Success: Proceed to the final update step
                    return updateProfileMobileNumber(newMobile);
                } else {
                    throw new Error(data.message || 'OTP verification failed');
                }
            })
            .catch(error => {
                Swal.showValidationMessage(`Verification failed: ${error.message}`);
            });
        },
        showCancelButton: true,
        confirmButtonText: 'Verify OTP',
    });
}

// Final Step: AJAX to Update User's Mobile Number in the Database
function updateProfileMobileNumber(newMobile) {
    Swal.fire({
        title: 'Updating Profile...',
        text: 'Finalizing the mobile number change.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // IMPORTANT: This endpoint must perform the DB update for the User
    return fetch('ajax/update_mobile_number.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
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
        if (data.success) { 
            // Update the display elements and the edit form input
            if (mobileEditInput) {
                mobileEditInput.value = newMobile;
            }
            // A simple page reload is the most reliable way to update all PHP-generated display fields
            window.location.reload(); 
            // return data; // This part is handled by the reload
        } else {
            Swal.fire('Update Failed', data.message || 'The database update could not be completed.', 'error');
        }
    })
    .catch(error => {
        console.error('Final Update Error:', error);
        Swal.fire('Error', 'A network error occurred during the final update.', 'error');
    });
}

// Initialize the display state when the page loads
document.addEventListener('DOMContentLoaded', () => {
    // Ensure the display is visible and forms are hidden initially
    toggleForms('display');
});