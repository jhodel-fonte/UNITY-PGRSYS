document.addEventListener('DOMContentLoaded', function() {
    const profileUpdateForm = document.getElementById('profileUpdateForm');
    const ajaxMessageContainer = document.getElementById('ajax-message-container');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const profilePicInput = document.getElementById('profilePicInput');

    // Function to display messages (remains the same)
    function displayMessage(message, type) {
        ajaxMessageContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        setTimeout(() => {
            const alert = ajaxMessageContainer.querySelector('.alert');
            if (alert) {
                new bootstrap.Alert(alert).close();
            }
        }, 5000);
    }

    // Handle form submission
    if (profileUpdateForm) { // Ensure the form element exists
        profileUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Collects all form data, including file

            console.log("--- FormData Content (before sending) ---");
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            console.log("-----------------------------------------");

            // Clear previous messages

            ajaxMessageContainer.innerHTML = '';

            Swal.fire({
                title: "Loading...",
                html: "Processing your request...",
                allowOutsideClick: false,
                allowEscapeKey: false,  
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = setTimeout(() => {
                        Swal.close();
                        console.log("Loading modal closed by timer.");
                    }, 3000); // <-- Adjust this value (in ms) as needed

                }
            });

            fetch('../../app/controllers/update_profile.php?profile=asdwGVWG2', { // Verify this path rigorously
                method: 'POST',
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        console.error("Server did not return JSON. Raw response:", text);
                        throw new Error(`Server did not return JSON. Check PHP script. Response: ${text.substring(0, 200)}...`);
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    displayMessage(data.message, 'success');
                    
                    if (data.newProfileImage) {
                        profileImagePreview.src = data.newProfileImage + '?t=' + new Date().getTime();
                    }
                    profilePicInput.value = ''; // Clear file input

                    window.location.reload();

                } else {
                    displayMessage(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                displayMessage('An error occurred while communicating with the server: ' + error.message, 'danger');
            });
        });
    }

    // Profile Image Preview functionality (remains the same)
    if (profilePicInput && profileImagePreview) {
        profilePicInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Auto-dismiss initial session messages (remains the same)
    const initialSuccessMessage = document.querySelector('.success-message');
    const initialErrorMessage = document.querySelector('.error-message');
    if (initialSuccessMessage) {
        setTimeout(() => {
            new bootstrap.Alert(initialSuccessMessage).close();
        }, 5000);
    }
    if (initialErrorMessage) {
        setTimeout(() => {
            new bootstrap.Alert(initialErrorMessage).close();
        }, 5000);
    }
});