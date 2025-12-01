<?php
// TEMPORARY USER DATA (Replace with DB later)
$user = [
    "first_name" => "Juan",
    "last_name" => "Dela Cruz",
    "mobile" => "09123456789",
    "email" => "juan@example.com",
    "gender" => "Male",
    "dob" => "1995-07-15",
    "address" => "Brgy. Poblacion, Padre Garcia",
    "username" => "juan123"
];

// foreach ($_SESSION as $key => $value) {

//     echo "Key: " . htmlspecialchars($key) . ", Value: " . htmlspecialchars(print_r($value, true)) . "<br><br>";
// }

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ .'../../../app/database/profiling.php';

if (isset($_SESSION['userLoginData']) ) {
    $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['pgcode']);
    $profileImage = (isset($_SESSION['userLoginData']['data']['profileImage'])) ? $_SESSION['userLoginData']['data']['profileImage'] : 'default.jpeg';
    $profileData = $_SESSION['userLoginData']['data'];
} else {
    header('Location: ../public/auth/login.php');
    exit;
}

// if ($_SESSION['userLoginData']['data']['isProfileComplete'] == 0) {
//     header('Location: ../auth/selfie.php');
//     exit;
// } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | Unity Padre Garcia</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.css">
<link rel="stylesheet" href="../user/assets/user.css">
</head>

<body>

<?php include 'sidebar.php'; ?>

<main class="user-main">

    <h1 class="page-title text-center">My Profile</h1>

    <div class="profile-card shadow-sm p-4 rounded bg-white">

        <!-- ==================
             PROFILE SUMMARY
        =================== -->
        <div class="text-center mb-4">
            <img src="../assets/img/funny-profile-pictures-2.jpg" width="120" class="rounded-circle mb-3">
            <h3><?= $user["first_name"] . " " . $user["last_name"] ?></h3>
            <p class="text-muted"><?= $user["email"] ?></p>

            <button class="btn btn-primary" id="editInfoBtn">Edit Information</button>
            <button class="btn btn-outline-danger ms-2" id="changePassBtn">Change Password</button>
        </div>

        <hr>

        <!-- ==================
             DISPLAY INFO
        =================== -->
        <div id="infoDisplay">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Mobile Number:</strong><br>
                    <span id="currentMobileDisplay"><?= $user["mobile"] ?></span>
                    <button class="btn btn-sm btn-outline-info ms-2" id="editMobileOTPBtn" title="Change Mobile Number via OTP">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Gender:</strong><br><?= $user["gender"] ?>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Date of Birth:</strong><br><?= $user["dob"] ?>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Username:</strong><br><?= $user["username"] ?>
                </div>
                <div class="col-12">
                    <strong>Address:</strong><br><?= $user["address"] ?>
                </div>
            </div>
        </div>

        <!-- ==================
             EDIT INFO FORM
        =================== -->
        <form id="editInfoForm" class="d-none mt-3">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>First Name</label>
                    <input type="text" class="form-control" value="<?= $user["first_name"] ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input type="text" class="form-control" value="<?= $user["last_name"] ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?= $user["email"] ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select class="form-control">
                        <option <?= $user["gender"] === "Male" ? "selected" : "" ?>>Male</option>
                        <option <?= $user["gender"] === "Female" ? "selected" : "" ?>>Female</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control" value="<?= $user["dob"] ?>">
                </div>

                <div class="col-12 mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" value="<?= $user["address"] ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Username</label>
                    <input type="text" class="form-control" value="<?= $user["username"] ?>">
                </div>

            </div>

            <button class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
        </form>

        <!-- ==================
             CHANGE PASSWORD FORM
        =================== -->
        <form id="changePassForm" class="d-none mt-3">

            <div class="mb-3">
                <label>Current Password</label>
                <input type="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>New Password</label>
                <input type="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Confirm New Password</label>
                <input type="password" class="form-control">
            </div>

            <button class="btn btn-primary">Update Password</button>
            <button type="button" class="btn btn-secondary" id="cancelPassBtn">Cancel</button>

        </form>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../user/assets/user.js"></script>
<script src="../user/assets/profile.js"></script>

</body>
</html>
