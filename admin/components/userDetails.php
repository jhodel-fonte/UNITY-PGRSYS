<div class="modal fade" id="userModal<?= $u['userId']; ?>" tabindex="-1" aria-labelledby="userModalLabel<?= $u['userId']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel<?= $u['userId']; ?>">
            User Details: <?= htmlspecialchars(($u['firstName'] ?? '') . ' ' . ($u['lastName'] ?? '')); ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <?php 
                    // Handle Profile Image (Use default if null)
                    $profileImg = !empty($u['profileImage']) 
                        ? '../uploads/' . htmlspecialchars($u['profileImage']) 
                        : '../uploads/default.png'; // Or your local default image path
                ?>

                <img src="<?= $profileImg ?>" alt="Profile Image" class="img-fluid rounded-circle border mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                
                <div class="d-grid gap-2">
                    <span class="badge bg-<?= ($u['isProfileComplete'] ?? 0) == 1 ? 'success' : 'secondary' ?> p-2">
                        <?= ($u['isProfileComplete'] ?? 0) == 1 ? 'Profile Complete' : 'Incomplete Profile' ?>
                    </span>
                    
                    <div class="card bg-light p-2 text-start">
                        <small class="text-muted d-block">User ID:</small>
                        <strong>#<?= htmlspecialchars($u['userId'] ?? 'N/A'); ?></strong>
                    </div>
                </div>
                
            </div>

            <div class="col-md-8">
                <h6 class="text-primary border-bottom pb-2"><i class="fas fa-user-circle me-2"></i>Personal Information</h6>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Username</small>
                        <span><?= htmlspecialchars($u['username'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Gender</small>
                        <span><?= htmlspecialchars($u['gender'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Date of Birth</small>
                        <span><?= htmlspecialchars($u['dateOfBirth'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Member Since</small>
                        <span><?= htmlspecialchars($u['date_created'] ?? 'N/A'); ?></span>
                    </div>
                </div>
                <p><strong>Role:</strong> <?= htmlspecialchars($u['role'] ?? 'N/A'); ?></p>

                <h6 class="text-primary border-bottom pb-2 mt-4"><i class="fas fa-address-card me-2"></i>Contact Details</h6>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Email Address</small>
                        <span><?= htmlspecialchars($u['email'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Mobile Number</small>
                        <span><?= htmlspecialchars($u['mobileNum'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Address</small>
                        <span><?= htmlspecialchars($u['address'] ?? 'Address not provided'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($u['description'])): ?>
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary border-bottom pb-2"><i class="fas fa-align-left me-2"></i>Description / Bio</h6>
                <div class="bg-light p-3 rounded">
                    <?= nl2br(htmlspecialchars($u['description'])); ?>
                </div>
            </div>
        </div>

        <?php endif; ?>
  
        <div class="p-3 bg-light border rounded mt-3 d-flex justify-content-between align-items-center">
            <strong>Current Account Status:</strong>
            <span class="badge rounded-pill bg-<?= 
                ($u['status'] === 'Active') ? 'success' : (
                ($u['status'] === 'Rejected') ? 'danger' : 'warning text-dark'
                ) 
            ?> fs-6">
                <?= htmlspecialchars(
                    ($u['status'] === 'NoOtpReg') ? 'Pending' : (
                    $u['status'] ?? 'N/A'
                    )
                ); ?>
            </span>
        </div>
                
        <h6 class="text-primary border-bottom pb-2 mt-4"><i class="fas fa-address-card me-2"></i>Documents/Valid ID</h6>

        <?php if (!empty($u['images']) && is_array($u['images'])) :?>
        <div class="row g-3 mb-3">
            <?php foreach ($u['images'] as $image_data): 
                // CRITICAL FIX: Access the filename via the 'location' key
                $image_filename = $image_data['location'] ?? null;
                $image_type = $image_data['type'] ?? 'N/A';
                $image_date = $image_data['DateCreated'] ?? 'N/A';

                if ($image_filename):
                    $imagePath = '../uploads/' . htmlspecialchars($image_filename); 
            ?>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light p-2 small">
                        <strong>Type:</strong> <?= htmlspecialchars($image_type) ?>
                        <br>
                        <small class="text-muted">Uploaded: <?= htmlspecialchars($image_date) ?></small>
                    </div>
                    <div class="card-body p-0 text-center">
                        <a href="<?= $imagePath ?>" target="_blank" class="d-block" title="Click to view full image">
                            <img src="<?= $imagePath ?>" alt="Document ID" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-header border-0 p-2 d-flex justify-content-end">
                            <h5 class="modal-title visually-hidden" id="<?= $modalId ?>Label">Full Image View</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 text-center">
                            <img src="<?= $imagePath ?>" alt="Document ID Full View" class="img-fluid rounded" style="max-width: 100%; max-height: 90vh;">
                        </div>
                    </div>
                </div>
            </div>

            <?php 
                endif;
            endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-muted">No documents or IDs uploaded.</p>
        <?php endif; ?>

        
      </div>

  
      <div class="modal-footer justify-content-between">
          <div class="d-flex gap-2">
            <?php if($u['status'] === 'Pending' || $u['status'] === 'NoOtpReg'): ?>
                <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="<?= $u['userId'] ?>">Approve</button>
                <button class="btn btn-sm btn-danger action-btn" data-action="reject" data-userid="<?= $u['userId'] ?>">Reject</button>
            <?php elseif($u['userId'] == $adminCurrentUser): ?>
                  <span></span>
            <?php elseif($u['status'] === 'Approved' || $u['status'] === 'Active'): ?>
                <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="<?= $u['userId'] ?>">Delete</button>
            <?php elseif($u['status'] === 'Rejected'): ?>
                <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="<?= $u['userId'] ?>">Delete</button>
            <?php else: ?>
              <span class="text-muted">No Action Available</span>
            <?php endif; ?>
          </div>
          
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>