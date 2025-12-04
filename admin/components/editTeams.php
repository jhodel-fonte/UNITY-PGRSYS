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
?>
<div class="modal fade" id="editTeamModal<?= $teamId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Response Team: <?= $teamName ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" class="needs-validation" novalidate>
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
                        <div class="col-md-12">
                            <label class="form-label text-muted">Address</label>
                            <input type="text" class="form-control" name="address" value="<?= $address ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Latitude</label>
                            <input type="text" class="form-control" name="latitude" value="<?= $latitude ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Longitude</label>
                            <input type="text" class="form-control" name="longitude" value="<?= $longitude ?>">
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