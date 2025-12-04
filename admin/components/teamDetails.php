<?php foreach ($teams as $team):
    $teamId = htmlspecialchars($team['team_id'] ?? '');
    $teamName = htmlspecialchars($team['name'] ?? 'Unknown Team');
    $contact = htmlspecialchars($team['contact_number'] ?? 'N/A');
    $email = htmlspecialchars($team['email'] ?? 'N/A');
    $address = htmlspecialchars($team['address'] ?? 'N/A');
    $statusLabel = ($team['is_active'] ?? 0) ? 'Active' : 'Inactive';
    $statusBadge = ($team['is_active'] ?? 0) ? 'success' : 'secondary';
    $latitude = $team['latitude'] ?? null;
    $longitude = $team['longitude'] ?? null;
    $members = $team['members'] ?? [];
?>

<div class="modal fade" id="teamModal<?= $teamId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamModalLabel<?= $teamId ?>">
                    Team Details: <?= $teamName ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <h6 class="text-primary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>General Information</h6>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Team ID</small>
                        <strong>#<?= $teamId ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Name</small>
                        <span><?= $teamName ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Contact Number</small>
                        <span><?= $contact ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Email Address</small>
                        <span><?= $email ?></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Address</small>
                        <span><?= $address ?></span>
                    </div>
                </div>

                <div class="p-3 bg-light border rounded mt-3 d-flex justify-content-between align-items-center">
                    <strong>Current Team Status:</strong>
                    <span class="badge rounded-pill bg-<?= $statusBadge ?> fs-6">
                        <?= $statusLabel ?>
                    </span>
                </div>
                
                <?php if ($latitude && $longitude): ?>
                <h6 class="text-primary border-bottom pb-2 mt-4"><i class="fas fa-map-marker-alt me-2"></i>Location</h6>
                <div class="rounded overflow-hidden mb-3 border" style="height: 300px;">
                    <iframe
                        width="100%"
                        height="100%"
                        style="border:0;"
                        loading="lazy"
                        allowfullscreen
                        src="https://maps.google.com/maps?q=<?= urlencode($latitude) ?>,<?= urlencode($longitude) ?>&z=14&output=embed">
                    </iframe>
                </div>
                <?php endif; ?>

                <!-- START: COLLAPSIBLE MEMBERS SECTION -->
                <div class="card mt-4 border-0">
                    <div class="card-header bg-white border-bottom p-0 d-flex justify-content-between align-items-center" id="headingMembers<?= $teamId ?>">
                        <h6 class="text-primary mb-0 py-2"><i class="fas fa-users me-2"></i>Team Members (<?= count($members) ?>)</h6>
                        
                        <!-- Toggle Button -->
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMembers<?= $teamId ?>" aria-expanded="true" aria-controls="collapseMembers<?= $teamId ?>">
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </button>
                    </div>

                    <!-- Collapsible Content -->
                    <div id="collapseMembers<?= $teamId ?>" class="collapse" aria-labelledby="headingMembers<?= $teamId ?>">
                        <div class="card-body p-0 pt-3">
                            <?php if (!empty($members)): ?>
                                <div class="list-group">
                                    <?php foreach ($members as $member):
                                        $memberName = htmlspecialchars(trim(($member['firstName'] ?? '') . ' ' . ($member['lastName'] ?? '')) ?: 'Unnamed Member');
                                        $memberContact = htmlspecialchars($member['contact_number'] ?? $member['email'] ?? 'No contact info');
                                    ?>
                                    <div class="list-group-item list-group-item-action bg-light mb-2 border rounded shadow-sm p-3">
                                        <strong><i class="fas fa-user me-2 text-primary"></i><?= $memberName ?></strong><br>
                                        <small class="text-muted"><i class="fas fa-phone-alt me-2"></i><?= $memberContact ?></small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No members assigned to this team.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- END: COLLAPSIBLE MEMBERS SECTION -->

            </div>
            <div class="modal-footer justify-content-between">
                <button 
                    class="btn btn-sm btn-success assign-btn" 
                    data-bs-toggle="modal" 
                    data-bs-target="#addMemberModal"
                    data-teamid="<?= $teamId ?>"
                >
                    <i class="fas fa-user-plus me-1"></i> Add Member
                </button>
                
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

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


<script>
$(document).ready(function() {
    
    var currentTeamId = null;

    // JavaScript for rotating the chevron icon when collapsing/expanding
    $('#teamModal<?= $teamId ?>').on('shown.bs.collapse hidden.bs.collapse', function (e) {
        var icon = $(e.target).prev().find('.toggle-icon');
        if (e.type == 'shown') {
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });

    $('#addMemberModal').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        currentTeamId = button.data('teamid');

        $('#assignTeamIdDisplay').text('Team ID: #' + currentTeamId);
        $('#userListBody').empty();
        $('#memberSearchInput').val('');
        $('#userLoadingIndicator').removeClass('d-none');
        
        fetchAvailableUsers(currentTeamId);
    });

    // --- B. AJAX function to fetch users from the server ---
    function fetchAvailableUsers(teamId) {
        $.ajax({
            url: '../app/api/data/getData.php?data=ResponseTeamUsers',
            method: 'GET',
            data: { team_id: teamId }, // Pass the team ID to the server
            dataType: 'json',
            success: function(response) {
                $('#userLoadingIndicator').addClass('d-none');
                
                // FIX 1: Change response.users to response.data to match JSON structure
                const users = response.data || []; // Use 'data' key, default to empty array
                
                // FIX 2: Check the length of the 'users' array (which is now response.data)
                if (response.success && users.length > 0) {
                    let userHtml = '';
                    // FIX 3: Iterate over the corrected 'users' variable
                    $.each(users, function(index, user) {
                        // Construct the table row dynamically
                        userHtml += '<tr>';
                        userHtml += '<td>' + (index + 1) + '</td>';
                        // FIX 4: Use acc.username as a fallback if firstName/lastName is missing, 
                        // and use mobileNum as a better fallback for contact info.
                        userHtml += '<td>' + (user.firstName || '') + ' ' + (user.lastName || user.username) + '</td>';
                        userHtml += '<td>' + (user.email || user.mobileNum || 'N/A') + '</td>'; 
                        userHtml += '<td>' + (user.role || 'N/A') + ' / ' + (user.status || 'N/A') + '</td>';
                        userHtml += '<td><button class="btn btn-sm btn-outline-success assign-user-btn" data-userid="' + user.userId + '">Assign</button></td>';
                        userHtml += '</tr>';
                    });
                    $('#userListBody').html(userHtml);
                } else {
                    $('#userListBody').html('<tr><td colspan="5" class="text-center text-muted">No available users found for assignment.</td></tr>');
                }
            },
            error: function() {
                $('#userLoadingIndicator').addClass('d-none');
                $('#userListBody').html('<tr><td colspan="5" class="text-center text-danger">Error fetching users. Please try again.</td></tr>');
            }
        });
    }

    // --- C. Handle assigning a user to the team ---
    $(document).on('click', '.assign-user-btn', function() {
        var button = $(this);
        var userId = button.data('userid');
        
        if (!currentTeamId) {
            alert('Error: Could not determine the current team.');
            return;
        }
        
        // Disable button and show loader feedback
        button.prop('disabled', true).text('Assigning...'); 

        $.ajax({
            url: 'ajax/assign_user_to_team.php', // *** NOTE: CREATE THIS FILE ***
            method: 'POST',
            data: { 
                team_id: currentTeamId, 
                user_id: userId 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('User successfully assigned to the team!');
                    // OPTIONAL: Remove the row from the table or update its status
                    button.closest('tr').remove(); 
                } else {
                    alert('Assignment failed: ' + response.message);
                    button.prop('disabled', false).text('Assign');
                }
            },
            error: function() {
                alert('An error occurred during assignment.');
                button.prop('disabled', false).text('Assign');
            }
        });
    });

    // --- D. Basic Search/Filter (jQuery Example) ---
    $('#memberSearchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#userListBody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>