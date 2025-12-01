<?php
if (!isset($reports) || !is_array($reports)) {
    return;
}

foreach ($reports as $team):
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
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-neon">Edit Response Team</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="team_id" value="<?= $teamId ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Team Name</label>
                            <input type="text" class="form-control" name="name" value="<?= $teamName ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" value="<?= $contact ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= $email ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" <?= $isActive === 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $isActive === 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="<?= $address ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="text" class="form-control" name="latitude" value="<?= $latitude ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" name="longitude" value="<?= $longitude ?>">
                        </div>
                    </div>

                    <hr class="border-secondary my-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-neon mb-0">Team Members</h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="openAddMemberModal('<?= $teamId ?>')">
                            <i class="fa fa-user-plus me-1"></i> Add Member
                        </button>
                    </div>

                    <?php if (!empty($members)): ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped align-middle">
                                <thead>
                                    <tr>
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
                                            <button type="button" class="btn btn-sm btn-outline-warning me-2" onclick="openEditMemberModal('<?= $memberId ?>')">
                                                <i class="fa fa-pen"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMember('<?= $teamId ?>','<?= $memberId ?>')">
                                                <i class="fa fa-user-minus"></i> Remove
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No members assigned to this team yet.</p>
                    <?php endif; ?>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

