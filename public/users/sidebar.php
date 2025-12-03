 <?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['userLoginData']['data']['role'] != 'User') {
    header("Location: ../error.php");
    exit;
}

$name = (isset($_SESSION['userLoginData'])) ? $_SESSION['userLoginData']['data']['firstName'] .' ' .$_SESSION['userLoginData']['data']['lastName'] : 'USER';
$image = (isset($_SESSION['userLoginData'])) ? $_SESSION['userLoginData']['data']['profileImage'] : 'default.png';

if (isset($_SESSION['userLoginData'])  ) {
    require_once __DIR__ .'../../../app/database/profiling.php';
    $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['userLoginData']['data']['pgCode']);
    $status = $_SESSION['userLoginData']['data']['status'];
    $otp = $_SESSION['userLoginData']['data']['is_otp_verified'];

}  else {
    header('Location: ../auth/login.php');
    exit;
}

if ($status == 'NoOtpReg' && ($otp == '0' || $otp == false)){
    header('Location: ../error.php');
    exit;
} else if ($status != 'Active') {
        header('Location: ../error.php?notVerified=1');
    exit;
}

/*
if ($_SESSION['userLoginData']['data']['isProfileComplete'] == 0) {
    header('Location: ../auth/selfie.php');
    exit;
}  */

/* 
$currPath = str_replace('\\', '/', __DIR__);
$directoryName = basename($currPath);

// var_dump();
$userRole = $_SESSION['userLoginData']['data']['role'];

$currRole = (
    isset($_SESSION['userLoginData']['data']['role']) && 
    $_SESSION['userLoginData']['data']['role'] === 'User'
) ? 'users' : (
    $_SESSION['userLoginData']['data']['role'] ?? 'default_role' // Use original role or a safe default
);

if ($directoryName !== $currRole) {
    echo 'q';
    redirectBasedOnRole($_SESSION['userLoginData']['data']['role']);
    exit;
}
 */
?>
 
 
 <!DOCTYPE html>
 <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
 </head>

 
 <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" aria-expanded="false">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text text-center mb-4">Welcome User..<br><?= htmlspecialchars($name) ?></h3>

    <a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
    <a href="report.php" class="<?php echo ($currentPage === 'report.php') ? 'active' : ''; ?>"><i class="fa-solid fa-file-circle-plus me-2"></i> Create Report</a>
    <a href="view.php" class="<?php echo ($currentPage === 'view.php') ? 'active' : ''; ?>"><i class="fa-solid fa-file-lines me-2"></i>View My Report</a>
    <a href="status.php" class="<?php echo ($currentPage === 'status.php') ? 'active' : ''; ?>"><i class="fa-solid fa-bars-progress me-2"></i> Report Status</a>
    
</div>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-right">
        <span class="date-display" id="dateDisplay"></span>

        <div class="notification-bell" onclick="toggleNotifications()">
            <i class="fa-solid fa-bell notification-bell" id="notificationBell" onclick="toggleNotificationMenu()"></i>
            <span class="notification-badge" id="notificationCount">3</span>

            <div class="notification-dropdown" id="notificationDropdown">
                <!-- Notifications will be populated here -->
            </div>
        </div>

        <div class="profile-menu">
            <img src="../../uploads/<?= htmlspecialchars($image) ?>" class="profile-img" onclick="toggleProfileMenu()">
            <div class="profile-dropdown" id="profileDropdown">
                
                <a href="myprofile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                
                <button id="logoutBtn" class="dropdown-button">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
                
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/profile.js"></script>
    <script src="assets/profile.js"></script>

<script>
    // 1. Define the action/handler function
    function handleLogoutConfirmation() {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to logout?",
            icon: "warning",

            showDenyButton: true, 
            showCancelButton: false,
    
            confirmButtonText: "Yes, Logout",
            denyButtonText: "No, Stay",       
            
            confirmButtonColor: '#d33',       
            denyButtonColor: '#3085d6'       
            
            }).then((result) => {
            
            if (result.isConfirmed) {
                window.location.replace("../../app/controllers/logout.php?logout=sdeajhs"); 
                
            }
        });
    }
    const button = document.getElementById('logoutBtn');
    button.addEventListener('click', handleLogoutConfirmation);
</script>
