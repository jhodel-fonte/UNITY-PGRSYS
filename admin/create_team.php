<?php
// Main PHP file: create_response_team.php

// This file only initializes variables and handles the HTML structure.
// Form submission is now handled by AJAX.
$message = "";
$alertClass = "";
// include("");
// The $responseTeams array is not strictly needed here anymore since the data 
// will be processed and stored by the AJAX endpoint, but we keep the alert 
// variables for manual error display if needed.

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Response Team | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css" >
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body>


    <?php include '../admin/admin_sidebar.php'; ?>

    <div class="main-content">
      <div class="container mt-5">
        
        <div class="card p-4 shadow-lg border border-primary rounded-4 bg-white text-dark">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h3 class="text mb-0 text-primary">Create Response Team Account</h3>
                <a href="manage_response_team.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div id="ajaxAlertContainer">
                <?php if ($message): ?>
                    <div class="alert <?= $alertClass; ?>"><?= $message; ?></div>
                <?php endif; ?>
            </div>

            <form id="createTeamForm">
                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Team Name</label>
                        <input type="text" name="name" id="teamName" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teamClassification" class="form-label text-muted">Classification</label>
                        <select class="form-select" id="teamClassification" name="classification" required>
                            <option selected value="1">Select a Classification</option>
                            <option value="2">Medical</option>
                            <option value="3">Fire Rescue</option>
                            <option value="4">Search & Rescue</option>
                            <option value="5">Logistics</option>
                            <option value="6">Technical Support</option>
                            <option value="1">Other</option>
                        </select>
                    </div>
                </div>
                
                
                <button type="submit" id="submitTeamBtn" class="btn btn-primary w-100 mt-3 py-2">
                    <i class="fas fa-users-cog me-1"></i> Create Response Team
                </button>
            </form>
        </div>

        </div>
    </div>

    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">
                    <i class="fas fa-user-plus me-2 text-primary"></i>Assign Users to Team 
                    <span id="assignTeamIdDisplay" class="badge bg-secondary ms-2"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <p class="text-muted">Select the users below to add them to the team.</p>
                
                <div class="mb-3">
                    <input type="text" id="memberSearchInput" class="form-control" placeholder="Search users by name or ID...">
                </div>
                
                <div id="userLoadingIndicator" class="text-center p-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching available users...</p>
                </div>
                
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-striped" id="availableUserTable">
                        <thead class="sticky-top bg-white shadow-sm">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 30%;">User Name</th>
                                <th style="width: 30%;">Email/Contact</th>
                                <th style="width: 25%;">Role/Status</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="userListBody">
                            </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php //include __DIR__ .'/components/teamDetails.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="../admin/assets/admin.js"></script> -->
<script>

    // Global variable to store the ID of the team being assigned members
    let currentTeamId = null; 

    // Helper function to display alerts
    function displayAlert(message, type) {
        const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;
        $('#ajaxAlertContainer').html(html);
    }
    
    // --- 1. Handle Form Submission via AJAX ---
    $('#createTeamForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const button = $('#submitTeamBtn');
        const originalButtonHtml = button.html();

        // 1. Show loading state
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...');
        $('#ajaxAlertContainer').empty();

        $.ajax({
            url: '../app/controllers/create_teams.php',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayAlert(response.message, 'success');
                    
                    // --- SUCCESS LOGIC: OPEN MODAL ---
                    currentTeamId = response.newlyId; // Get the newly created ID from JSON
                    
                    // Update modal display and fetch users
                    $('#assignTeamIdDisplay').text(currentTeamId);
                    $('#userLoadingIndicator').removeClass('d-none');
                    fetchAvailableUsers(currentTeamId);
                    form[0].reset(); 
                } else {
                    // Display error message from the PHP script
                    displayAlert(response.message || 'Team creation failed due to unknown error.', 'danger');
                }
            },
            error: function() {
                displayAlert('An error occurred. Check the server connection or **ajax/create_team.php** file.', 'danger');
            },
            complete: function() {
                // Restore button state
                button.prop('disabled', false).html(originalButtonHtml);
            }
        });
    });

    // --- 2. AJAX function to fetch users from the server (No change needed) ---
    function fetchAvailableUsers(teamId) {
        $('#userListBody').empty(); 

        $.ajax({
            url: '../app/api/data/getData.php?data=NoTeamUser',
            method: 'GET',
            data: { team_id: teamId }, 
            dataType: 'json',
            success: function(response) {
                $('#userLoadingIndicator').addClass('d-none');
                
                const users = response.data || []; 
                
                if (response.success && users.length > 0) {
                    let userHtml = '';
                    
                    $.each(users, function(index, user) {
                        const userName = (user.firstName || '') + ' ' + (user.lastName || user.username || '');
                        userHtml += '<tr>';
                        userHtml += '<td>' + (index + 1) + '</td>';
                        userHtml += '<td>' + userName.trim() + '</td>';
                        userHtml += '<td>' + (user.email || user.mobileNum || 'N/A') + '</td>'; 
                        userHtml += '<td>' + (user.role || 'N/A') + ' / ' + (user.status || 'N/A') + '</td>';
                        userHtml += '<td><button class="btn btn-sm btn-outline-success assign-user-btn" data-userid="' + (user.userId || user.id) + '">Assign</button></td>'; 
                        userHtml += '</tr>';
                    });
                    $('#userListBody').html(userHtml);
                } else {
                    $('#userListBody').html('<tr><td colspan="5" class="text-center text-muted">No available users found for assignment.</td></tr>');
                }
            },
            error: function() {
                $('#userLoadingIndicator').addClass('d-none');
                $('#userListBody').html('<tr><td colspan="5" class="text-center text-danger">Error fetching users. Please check the API endpoint.</td></tr>');
            }
        });
    }

    // --- 3. Handle assigning a user to the team (No change needed) ---
    $(document).on('click', '.assign-user-btn', function() {
        var button = $(this);
        var userId = button.data('userid');
        
        if (!currentTeamId) {
            alert('Error: Could not determine the current team. Please refresh the page.');
            return;
        }
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Assigning...'); 

        $.ajax({
            url: 'ajax/assign_user_to_team.php', 
            method: 'POST',
            data: { 
                team_id: currentTeamId, 
                user_id: userId 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayAlert(`User ${userId} successfully assigned to Team ${currentTeamId}!`, 'info');
                    button.closest('tr').fadeOut(300, function() {
                        $(this).remove(); 
                    });
                } else {
                    alert('Assignment failed: ' + (response.message || 'Unknown error.'));
                    button.prop('disabled', false).text('Assign');
                }
            },
            error: function() {
                alert('An error occurred during assignment.');
                button.prop('disabled', false).text('Assign');
            }
        });
    });

</script>


</body>
</html>