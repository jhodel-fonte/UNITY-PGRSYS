<?php
//examples raw data
require_once __DIR__ .'../../app/api/data/dataProcess.php';

$reports = getDataSource("http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=report");

$users = [
    ["id" => 1, "name" => "John Doe"],
    ["id" => 2, "name" => "Jane Smith"],
    ["id" => 3, "name" => "Carlos Reyes"]
];
$userCount = count($users);

$responseTeams = [
    ["id" => 1, "name" => "Firefighter Team"],
    ["id" => 2, "name" => "Police Team"]
];
$teamCount = count($responseTeams);

$finishedReports = [
    ["id" => 1, "status" => "Completed"],
    ["id" => 2, "status" => "Completed"],
    ["id" => 3, "status" => "Completed"],
    ["id" => 4, "status" => "Completed"]
];
$finishedCount = count($finishedReports);

$months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun"];
$totals = [5, 8, 12, 20, 15, 9];

$approvedReports = [
    ["title" => "Fallen Tree", "category" => "Emergency", "location" => "Brgy. Pagasa", "date" => "2025-11-20"],
    ["title" => "Gas Leak", "category" => "Hazard", "location" => "Brgy. Merville", "date" => "2025-11-18"],
    ["title" => "Road Damage", "category" => "Infrastructure", "location" => "Brgy. Mabini", "date" => "2025-11-17"]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Padre Garcia Reporting</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css">
</head>

<body>

<?php include 'admin_sidebar.php'; ?>

<div class="dash-content">

    <h2 class="text-center dashboard-title">Dashboard Overview</h2>

    <div class="row g-4 mb-4 mt-2 justify-content-center">

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= $userCount ?>">0</h1>
                <p>Total Users</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= $teamCount ?>">0</h1>
                <p>Response Team</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <!-- paltan mo na lang to ng total reports -->
                <h1 class="count" data-value="<?= $finishedCount ?>">0</h1> 
                <p>Total Reports</p>
            </div>
        </div>
    </div>

<br>

    <h3 class="chart-title text-center mb-3">Reports Overview</h3>

    <div class="reports-wrapper">

    <!-- MAP -->
   <div class="map-container" id="map"></div>

    <div class="chart-container">
        <!-- CHART -->
        <div class="chart-card">

    <div class="chart-card-header">
        <h4>Monthly Reports</h4>
    </div>

    <div class="chart-card-body">
        <canvas id="monthlyChart"></canvas>
    </div>

</div>

        <!-- RECENT REPORTS TABLE -->
        <div class="recent-reports mt-4">
            <h3 class="text-center mb-3">Recent Approved Reports</h3>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Report Title</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Date Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approvedReports as $report): ?>
                        <tr>
                            <td><?= $report['title'] ?></td>
                            <td><?= $report['category'] ?></td>
                            <td><?= $report['location'] ?></td>
                            <td><?= $report['date'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>


</div>

<script>
let chartMonths = <?= json_encode($months) ?>;
let chartTotals = <?= json_encode($totals) ?>;
</script>

<!-- Load Leaflet BEFORE your admin.js -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="../admin/assets/admin.js"></script>

</body>
</html>
