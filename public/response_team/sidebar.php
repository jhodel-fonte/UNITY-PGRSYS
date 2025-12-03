 <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" aria-expanded="false">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text text-center mb-4">Welcome User</h3>

    <a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
    <a href="asreports.php" class="<?php echo ($currentPage === 'asreport.php') ? 'active' : ''; ?>"><i class="fa-solid fa-file-lines me-2"></i> Assigned Reports</a>
    
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
            <img src="../assets/img/funny-profile-pictures-2.jpg" class="profile-img" onclick="toggleProfileMenu()">
            <div class="profile-dropdown" id="profileDropdown">
                <a href="myprofile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                <a href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

