<?php
$currentPage = basename($_SERVER['PHP_SELF']);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ .'../../app/database/profiling.php';

if (isset($_SESSION['userLoginData']) ) {
  $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['userLoginData']['data']['pgCode']);
}

$profileImage = (isset($_SESSION['userLoginData']['data']['profileImage'])) ? $_SESSION['userLoginData']['data']['profileImage'] : 'default.jpeg';
$profileData = $_SESSION['userLoginData']['data'];

$currPath = str_replace('\\', '/', __DIR__);
$directoryName = basename($currPath);

$userRole = $_SESSION['userLoginData']['data']['role'];

if ($userRole != 'Admin') {
    header("Location: ../error.php");
}

?>

<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" aria-expanded="false">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text text-center mb-4">Admin Panel</h3>

    <a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
    <a href="manage_users.php" class="<?php echo ($currentPage === 'manage_users.php') ? 'active' : ''; ?>"><i class="fa-solid fa-user me-2"></i> Manage Users</a>
    <a href="manage_response_team.php" class="<?php echo ($currentPage === 'manage_response_team.php') ? 'active' : ''; ?>"><i class="fa-solid fa-users me-2"></i>Response Teams</a>
    <a href="responseTeamUsers.php" class="<?php echo ($currentPage === 'responseTeamUsers.php') ? 'active' : ''; ?>"><i class="fa-solid fa-users me-2"></i>Team Members</a>
    <a href="manage_reports.php" class="<?php echo ($currentPage === 'manage_reports.php') ? 'active' : ''; ?>"><i class="fa-solid fa-file-alt me-2"></i> Manage Reports</a>
    <!-- <a href="activity_log.php" class="<?= ($currentPage === 'activity_log.php') ? 'active' : ''; ?>"><i class="fa-solid fa-list-check me-2"></i> Activity Log</a> -->
    
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
                <a href="my_profile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                <a href="../app/controllers/logout.php?logout=1"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

