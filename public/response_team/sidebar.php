<?php
$currentPage = basename($_SERVER['PHP_SELF']);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ .'/../../app/database/profiling.php';

if (isset($_SESSION['userLoginData']) ) {
  //check for updates
  $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['userLoginData']['data']['pgCode']);
}

$profileImage = (isset($_SESSION['userLoginData']['data']['profileImage'])) ? $_SESSION['userLoginData']['data']['profileImage'] : 'default.jpeg';
$profileData = $_SESSION['userLoginData']['data'];

$currPath = str_replace('\\', '/', __DIR__);
$directoryName = basename($currPath);

$userRole = $_SESSION['userLoginData']['data']['role'];

$currRole = (
    isset($_SESSION['userLoginData']['data']['role']) && 
    $_SESSION['userLoginData']['data']['role'] === 'Response Team'
) ? 'response_team' : (
    $_SESSION['userLoginData']['data']['role'] ?? 'default_role'
);

?>

<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" aria-expanded="false">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text text-center mb-4">Response Team</h3>

    <a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
    <a href="asreports.php" class="<?php echo ($currentPage === 'asreports.php') ? 'active' : ''; ?>"><i class="fa-solid fa-file-lines me-2"></i> Assigned Reports</a>
    <a href="reports_history.php" class="<?php echo ($currentPage === 'reports_history.php') ? 'active' : ''; ?>"><i class="fa-solid fa-history me-2"></i> Reports History</a>
    
</div>

<script>console.log(<?= json_encode($profileImage) ?>)</script>

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
            <img src="../uploads/<?= $profileImage ?>" class="profile-img" onclick="toggleProfileMenu()">
            <div class="profile-dropdown" id="profileDropdown">
                <a href="../users/myprofile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                <a href="../app/controllers/logout.php?logout=1"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

