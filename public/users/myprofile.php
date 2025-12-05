<?php
session_start();

// include_once __DIR__ .''

$admin = (isset($_SESSION['userLoginData'])) ? $_SESSION['userLoginData']['data'] : null;
$name = (isset($_SESSION['userLoginData'])) ? $_SESSION['userLoginData']['data']['firstName'] .' ' .$_SESSION['userLoginData']['data']['lastName'] : 'USER Error';

if (!is_array($admin)) {
    $admin = [];
}

$profileImage = 'default_profile.png'; 
if (!empty($admin['profileImage'])) {
    $profileImage = $admin['profileImage'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | <?= htmlspecialchars($name); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="assets/admin.css">
<link rel="stylesheet" href="assets/security.css">

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <div class="card-customs scroll-cards p-4">

        <div class="profile-header-row">
            <h3 class="mb-3">My Profile</h3>

            <h4 class="mb-1">PG-ID: <?= htmlspecialchars($admin['pgCode']) ?></h4>

            <div class="text-end">
                <button type="button" class="btn btn-security"
                        data-bs-toggle="modal" data-bs-target="#securitySettingsModal">
                    <i class="fas fa-user-lock"></i> Edit Security Settings
                </button>
            </div>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success success-message"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger error-message"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div id="ajax-message-container"></div>


        <form id="profileUpdateForm" method="POST" enctype="multipart/form-data">

            <div class="row">
                

                <div class="col-md-4 text-center border-end">
                    <img src="../../uploads/<?= htmlspecialchars($image) ?>"
                         class="img-fluid rounded-circle mb-3"
                         style="width: 160px; height:160px; object-fit:cover;"
                         id="profileImagePreview">
                    <input type="file" name="profile_pic" class="form-control" id="profilePicInput">
                    <small class="text-muted">Max file size: 2MB. Allowed types: JPG, PNG, GIF.</small>
                </div>

                <div class="col-md-8">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>First Name</label>
                            <input type="text" name="firstName" class="form-control"
                                   value="<?= htmlspecialchars($admin['firstName'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="lastName" class="form-control"
                                   value="<?= htmlspecialchars($admin['lastName'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Mobile Number</label>
                            <div class="input-group">
                                <input type="text" name="mobileNum" class="form-control"
                                       value="<?= htmlspecialchars($admin['mobileNum'] ?? '') ?>" readonly>
                                <button type="button" id="editMobileBtn" class="btn btn-outline-secondary">Edit</button>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                   value="<?= htmlspecialchars($admin['username'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($admin['email'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="Male" <?= ($admin['gender'] ?? '') == "Male" ? "selected" : "" ?>>Male</option>
                                <option value="Female" <?= ($admin['gender'] ?? '') == "Female" ? "selected" : "" ?>>Female</option>
                                <option value="Other" <?= ($admin['gender'] ?? '') == "Other" ? "selected" : "" ?>>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control"
                                   value="<?= htmlspecialchars($admin['dateOfBirth'] ?? '') ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <textarea class="form-control" name="address"><?= htmlspecialchars($admin['address'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Role</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($admin['role'] ?? '') ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($admin['status'] ?? '') ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Created At</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($admin['date_created'] ?? '') ?>" disabled>
                        </div>

<!--                         <div class="col-md-6 mb-3">
                            <label>Approval</label>
                            <input type="text" class="form-control"
                                   value="<?= ($admin['is_approved'] ?? false) ? 'Approved' : 'Pending'; ?>" disabled>
                        </div> -->

                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>

        </div>

        </form>
    </div>
</div>

<?php include_once 'components/editSecurity.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/my_profile.js"></script>
<script src="assets/security.js"></script>
<script src="components/editnumber.js"></script>
<script src="components/otpInput.js"></script>



<script src="assets/admin.js"></script>

</body>
</html>
