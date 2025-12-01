<div class="modal fade" id="securitySettingsModal" tabindex="-1" aria-labelledby="securitySettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="securitySettingsModalLabel"><i class="fas fa-lock me-2"></i> Edit Security Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="securityUpdateForm" method="POST">
                <div class="modal-body">
                    
                    <div id="security-ajax-message-container"></div>

                    <input type="text" name="pgId" value="<?= htmlspecialchars($admin['pgCode']) ?>" hidden>
                    <h6 class="mb-3 mt-4">Change Password</h6>
                    <hr>
                    
                    
                    
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" name="currentPassword" class="form-control" id="currentPassword" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" name="newPassword" class="form-control" id="newPassword" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                        <input type="password" name="confirmNewPassword" class="form-control" id="confirmNewPassword" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

