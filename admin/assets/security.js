document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('securityUpdateForm');
    const messageContainer = document.getElementById('security-ajax-message-container');
    const securityModal = document.getElementById('securitySettingsModal');

    // Helper function to display messages inside the modal
    function displayModalMessage(type, message) {
        messageContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // Clear messages when the modal is closed
    if (securityModal) {
        securityModal.addEventListener('hidden.bs.modal', function() {
            messageContainer.innerHTML = '';
            form.reset(); // Clear form fields
        });
    }

    // Handle form submission via AJAX for password change
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            messageContainer.innerHTML = '';
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Updating...';

            // Simple client-side password match check (server validation is still required)
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmNewPassword').value;

            if (newPass !== confirmPass) {
                displayModalMessage('warning', 'New Password and Confirm Password do not match.');
                submitButton.disabled = false;
                submitButton.textContent = 'Update Password';
                return;
            }

            const formData = new FormData(form);

            // Debugging log (Excellent addition!)
            console.log("--- FormData Content (before sending) ---");
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            console.log("-----------------------------------------");
            
            
            fetch('../app/controllers/update_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    // Server error handling (Excellent addition!)
                    return response.text().then(text => {
                        console.error("Server did not return JSON. Raw response:", text);
                        throw new Error(`Server did not return JSON. Check PHP script. Response: ${text.substring(0, 200)}...`);
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    displayModalMessage('success', data.message);
                    form.reset();
                    
                    // You might consider closing the modal on successful password change
                    // Bootstrap 5 usage:
                    const modalInstance = bootstrap.Modal.getInstance(securityModal);
                    if (modalInstance) {
                         // Only close after a short delay so the user sees the success message
                         setTimeout(() => modalInstance.hide(), 1000); 
                    }
                    
                } else {
                    displayModalMessage('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayModalMessage('danger', 'A connection error occurred. Check browser console for details.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Update Password';
            });
        });
    }
});