<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="../response_team/assets/rteam.css">

</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="response-main">

    <h1 class="page-title">Response Team Dashboard</h1>

    <!-- ==========================
         DASHBOARD CARDS
    ============================ -->
    <div class="dashboard-cards">

        <div class="dash-card pending">
            <h2 id="pendingCount">0</h2>
            <p>Pending Tasks</p>
        </div>

        <div class="dash-card progress">
            <h2 id="inProgressCount">0</h2>
            <p>In Progress</p>
        </div>

        <div class="dash-card today">
            <h2 id="completedTodayCount">0</h2>
            <p>Completed Today</p>
        </div>

        <div class="dash-card total">
            <h2 id="totalCompletedCount">0</h2>
            <p>Total Completed</p>
        </div>

    </div>

    <!-- ==========================
         LATEST ASSIGNED REPORTS
    ============================ -->
    <div class="report-section">
        <h2>Latest Assigned Reports</h2>

        <div class="scrollable-table">
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Location</th>
                        <th>Assigned</th>
                    </tr>
                </thead>
                <tbody id="latestReportsBody"></tbody>
            </table>
        </div>
    </div>

    <!-- ==========================
         URGENT / HIGH PRIORITY
    ============================ -->
    <div class="report-section">
        <h2>Urgent / High Priority Reports</h2>

        <div class="scrollable-table">
            <table class="reports-table urgent-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="urgentReportsBody"></tbody>
            </table>
        </div>
    </div>

    <!-- ==========================
         MAP OF REPORT LOCATIONS
    ============================ -->
    <div class="map-section">
        <h2>Report Locations Map</h2>
        <div id="reportMap"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../response_team/assets/dashboard.js"></script>
<script src="../response_team/assets/rteam.js"></script>
</body>
</html>
