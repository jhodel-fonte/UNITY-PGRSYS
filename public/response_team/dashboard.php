<?php
require_once __DIR__ . '/../../app/utils/log.php'; // Adjusted slashes
require_once __DIR__ . '/../../app/api/data/dataProcess.php'; // Adjusted slashes

$data_source_url = "http://localhost/UNITY-PGRSYS/app/api/data/getData.php?data=teams";
$teams = getDataSource($data_source_url);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response Team Dashboard | Padre Garcia Reporting</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../response_team/assets/admin.css">

</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="dash-content">

    <h2 class="text-center dashboard-title">Response Team Dashboard</h2>

    <div class="row g-4 mb-4 mt-2 justify-content-center">

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="12">0</h1>
                <p>Pending Tasks</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="8">0</h1>
                <p>In Progress</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="24">0</h1> 
                <p>Total Completed</p>
            </div>
        </div>
    </div>

<br>

    <h3 class="chart-title text-center mb-3">Latest Assigned Reports</h3>

    <div class="reports-wrapper">

    <!-- MAP -->
   <div class="map-container" id="map"></div>

    <div class="chart-container">
        <!-- RECENT REPORTS TABLE -->
        <div class="recent-reports mt-4">
            <h3 class="text-center mb-3">Assigned Reports</h3>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Report ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Location</th>
                            <th>Assigned Date</th>
                        </tr>
                    </thead>
                    <tbody id="latestReportsBody">
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>


</div>

<!-- Load Leaflet BEFORE scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../response_team/assets/admin.js"></script>
<script src="../response_team/assets/dashboard.js"></script>

</body>
</html>
