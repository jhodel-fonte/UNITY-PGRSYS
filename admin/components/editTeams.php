<?php
if (!isset($teams) || !is_array($teams)) {
    return;
}

foreach ($teams as $team):
    $teamId = htmlspecialchars($team['team_id'] ?? '');
    $teamName = htmlspecialchars($team['name'] ?? '');
    $contact = htmlspecialchars($team['contact_number'] ?? '');
    $email = htmlspecialchars($team['email'] ?? '');
    $address = htmlspecialchars($team['address'] ?? '');
    $latitude = htmlspecialchars($team['latitude'] ?? '');
    $longitude = htmlspecialchars($team['longitude'] ?? '');
    $isActive = (int)($team['is_active'] ?? 0);
    $members = $team['members'] ?? [];
    $classification = $team['classification'] ??'';
?>
<div class="modal fade" id="editTeamModal<?= $teamId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Response Team: <?= $teamName ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm<?= $teamId ?>" class="update-team-form needs-validation" novalidate>
                    <input type="hidden" name="team_id" value="<?= $teamId ?>">

                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-edit me-2"></i>General Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Team Name</label>
                            <input type="text" class="form-control" name="name" value="<?= $teamName ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" value="<?= $contact ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= $email ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" <?= $isActive === 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $isActive === 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Address</label>
                            <input type="text" class="form-control" name="address" value="<?= $address ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="teamClassification<?= $teamId ?>" class="form-label text-muted">Classification</label>
                            <select class="form-select" id="teamClassification<?= $teamId ?>" name="classification" required>
                                <option value="" disabled <?= $classification === 0 ? 'selected' : '' ?>>Select a Classification</option>
                                <option value="2" <?= $classification === 2 ? 'selected' : '' ?>>Medical</option>
                                <option value="3" <?= $classification === 3 ? 'selected' : '' ?>>Fire Rescue</option>
                                <option value="4" <?= $classification === 4 ? 'selected' : '' ?>>Search & Rescue</option>
                                <option value="5" <?= $classification === 5 ? 'selected' : '' ?>>Logistics</option>
                                <option value="6" <?= $classification === 6 ? 'selected' : '' ?>>Technical Support</option>
                                <option value="1" <?= $classification === 1 ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    
                    </div>

                    <!-- Collapsible Team Members Section Header -->
                    <div class="d-flex justify-content-between align-items-center mt-4 border-bottom pb-2">
                        <!-- Toggle Link: Clickable H6 to collapse the members list -->
                        <a class="text-primary text-decoration-none"
                            data-bs-toggle="collapse"
                            href="#membersCollapse<?= $teamId ?>"
                            role="button"
                            aria-expanded="true"
                            aria-controls="membersCollapse<?= $teamId ?>">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Team Members</h6>
                        </a>
                        <!-- Add Member button, kept outside the collapsible content for constant visibility -->
                        <button type="button" class="btn btn-sm btn-success" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addMemberModal"
                            data-teamid="<?= $teamId ?>">
                            <i class="fas fa-user-plus me-1"></i> Add Member
                        </button>
                    </div>

                    <!-- Collapsible Content (The actual member list) -->
                    <div class="collapse show pt-3" id="membersCollapse<?= $teamId ?>">
                        <?php if (!empty($members)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($members as $member):
                                        $memberId = htmlspecialchars($member['userId'] ?? '');
                                        $memberName = htmlspecialchars(trim(($member['firstName'] ?? '') . ' ' . ($member['lastName'] ?? '')) ?: 'Unnamed Member');
                                        $memberContact = htmlspecialchars($member['contact_number'] ?? $member['email'] ?? 'No contact info');
                                    ?>
                                        <tr>
                                            <td><?= $memberName ?></td>
                                            <td><?= $memberContact ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="openEditMemberModal('<?= $memberId ?>')">
                                                    <i class="fas fa-pen"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMember('<?= $teamId ?>','<?= $memberId ?>')">
                                                    <i class="fas fa-user-minus"></i> Remove
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No members assigned to this team yet. Use the 'Add Member' button to assign users.</p>
                        <?php endif; ?>
                    </div>
                    <!-- End Collapsible Content -->

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
    $(document).ready(function() {
    $('.update-team-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var formData = form.serialize(); // Get all form data
        var teamId = form.find('input[name="team_id"]').val(); 

        var saveButton = form.find('button[type="submit"]');
        saveButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...');

        $.ajax({
            url: '../app/controllers/update_teams.php', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            
            success: function(response) {
                
                console.log("Success:", response);
                
                
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Team updated successfully!',
                    icon: 'success'
                }).then(() => {
                    // Refresh the page or update the specific row data
                    location.reload(); 
                });
                
                // Close the modal
                $('#editTeamModal' + teamId).modal('hide');
            },
            
            error: function(jqXHR, textStatus, errorThrown) {
                // The PHP endpoint should return a JSON error response
                var errorResponse = jqXHR.responseJSON;
                var errorMessage = errorResponse ? errorResponse.message : 'An unknown network error occurred.';
                
                // Show an error notification using SweetAlert2
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error'
                });
                
                console.error("Error Response:", errorResponse);
            },
            
            complete: function() {
                // Restore the save button state
                saveButton.prop('disabled', false).html('Save Changes');
            }
        });
    });
});
</script>

