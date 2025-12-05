<?php
session_start();

$id =  $_SESSION["userLoginData"]['data']['pgCode'];
// var_dump($id);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Report | Unity Padre Garcia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="assets/user.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="user-main">
    <div class="report-card shadow-sm">
        <h1 class="page-title">File a Report</h1>

        <form id="reportForm" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="user_id" value="<?= $id ?>">

            <div class="form-group mb-3">
                <label>Title</label>
                <input type="text" name="name">
            </div>

            <div class="form-group mb-3">
                <label>Category</label>
                <select name="classification" required>
                    <option value="1">Select Type</option>
                    <option value="3">Fire Rescue</option>
                    <option value="4">Search & Rescue</option>
                    <option value="5">Logistics</option>
                    <option value="2">Medical</option>
                    <option value="6">Technical Support</option>
                    <option value="1">Others</option>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label>Classification</label>
                <select name="severity" required>
                    <option value="">Select Classification</option>
                    <option value="Emergency">Emergency (Immediate life threat)</option>
                    <option value="Priority">Priority (Needs quick attention)</option>
                    <option value="Routine">Routine (General concern)</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Describe your report..." required></textarea>
            </div>

            <div class="form-group mb-3">
                <label>Upload Photo (max 10MB per file, optional)</label>
                <input type="file" name="report_images[]" accept="image/*" multiple>
            </div>

            <div class="form-group mb-3">
                <label>Location Name/Landmark</label>
                <input type="text" name="location" id="location" readonly required>
            </div>
            
            <div class="form-group mb-3">
                <label>Full Address</label>
                <input type="text" name="address" id="address" required>
            </div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div id="map" class="map-card"></div>

            <button class="submit-btn" type="submit">Submit Report</button>

        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/reportmap.js"></script>

<script>
$(document).ready(function() {
    // Define the 10MB limit in bytes (10 * 1024 * 1024)
    const MAX_FILE_SIZE = 10485760; 
    const submitButton = $('.submit-btn');

    $('#reportForm').on('submit', function(e) {
        e.preventDefault(); 

        // --- 1. Location Validation ---
        if (!$('#latitude').val() || !$('#longitude').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Location Required',
                text: 'Please select a location on the map.',
            });
            return;
        }
        
        // --- 2. File Size Validation ---
        const fileInput = $('input[name="report_images[]"]')[0];
        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            if (file.size > MAX_FILE_SIZE) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: `Error: The file "${file.name}" is too large. Maximum size is 10MB.`,
                });
                return; // Stop submission
            }
        }
        
        // --- 3. AJAX Submission Setup ---
        var formData = new FormData(this);

        submitButton.text('Submitting...').prop('disabled', true);
        
        // Send the request via AJAX
        $.ajax({
            url: '../../app/controllers/create_report.php',
            type: 'POST',
            data: formData,
            contentType: false, 
            processData: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Report Submitted!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => {
                        $('#reportForm')[0].reset(); 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'An error occurred during submission. Please check your connection and try again.',
                });
                console.error("AJAX Error:", error);
            },
            complete: function() {
                submitButton.text('Submit Report').prop('disabled', false);
            }
        });
    });
});
</script>
</body>
</html>