<?php
session_start();

// DEMO REPORTS
$reports = [
    [
        "id" => 1,
        "user_id" => 1,
        "status" => "Pending",
        "lat" => 13.87942,
        "lng" => 121.21598
    ],
    [
        "id" => 2,
        "user_id" => 1,
        "status" => "Resolved",
        "lat" => 13.87890,
        "lng" => 121.21711
    ],
    [
        "id" => 3,
        "user_id" => 1,
        "status" => "Pending",
        "lat" => 13.87985,
        "lng" => 121.21470
    ],
    [
        "id" => 4,
        "user_id" => 1,
        "status" => "Resolved",
        "lat" => 13.88020,
        "lng" => 121.21640
    ],
    [
        "id" => 4,
        "user_id" => 1,
        "status" => "Resolved",
        "lat" => 13.88020,
        "lng" => 121.21640
        ],
    [
        "id" => 4,
        "user_id" => 1,
        "status" => "Resolved",
        "lat" => 13.88020,
        "lng" => 121.21640
    ]
];

$total = 0;
$pending = 0;
$resolved = 0;

foreach ($reports as $r) {
    $total++;
    if ($r["status"] === "Pending") $pending++;
    if ($r["status"] === "Resolved") $resolved++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Unity PGSRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="assets/user.css">
</head>

<body>
<?php include 'sidebar.php'; ?>

<div class="dash-content">
    <div class="container">

        <h2 class="text-center dashboard-title">Dashboard Overview</h2>

        <!-- TOP CARDS -->
        <div class="row g-4 mb-4 mt-2 justify-content-center">

            <div class="col-md-3">
                <div class="admin-card">
                    <h1 class="count"><?= $total ?></h1>
                    <p>Total Reports</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="admin-card">
                    <h1 class="count"><?= $pending ?></h1>
                    <p>Pending Reports</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="admin-card">
                    <h1 class="count"><?= $resolved ?></h1>
                    <p>Resolved Reports</p>
                </div>
            </div>
        </div>

        <!-- Map Section -->
<div class="map-section mt-4">
    <h3 class="text-center mb-3">Report Map</h3>

    <div id="userMap"></div>
</div>



<!-- 🔵 MIDDLE ROW — RECENT REPORTS TABLE (NOW SCROLLABLE) -->
<div class="recent-reports p-4 rounded shadow-sm scroll-card mb-4">
    <h3 class="mb-3">Reports</h3>

    <div class="scrollable-list">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Report ID</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach (array_slice($reports, 0, 10) as $r): ?>
                <tr>
                    <td><?= $r["id"]; ?></td>
                    <td><?= $r["status"]; ?></td>
                    <td><?= date("M d, Y"); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- 🔵 BOTTOM ROW -->
<div class="row mb-5">

    <!-- LEFT — ACTIVITY FEED (NOW SCROLLABLE) -->
    <div class="col-md-6 mb-4">
        <div class="activity-feed p-4 rounded shadow-sm scroll-card">
            <h3 class="mb-3">Activity Feed</h3>

            <ul class="list-group scrollable-list">
                <li class="list-group-item">You submitted a new report.</li>
                <li class="list-group-item">Admin reviewed your report.</li>
                <li class="list-group-item">Your report status was updated.</li>
                <li class="list-group-item">New system announcement posted.</li>
                <li class="list-group-item">Testing.</li>
                <li class="list-group-item">Abay maligo utoy.</li>
            </ul>
        </div>
    </div>

    <!-- RIGHT — STATUS CHART -->
    <div class="col-md-6 mb-4">
        <div class="status-chart p-4 rounded shadow-sm scroll-card">
            <h3 class="mb-3">Report Chart</h3>

            <div class="chart-placeholder d-flex justify-content-center align-items-center"
                 style="height: 250px; background: #f5f5f5; border-radius: 10px;">
                <p class="text-muted">Chart goes here</p>
            </div>
        </div>
    </div>

</div>

</div>
</div>

<script>
    window.demoReports = <?= json_encode($reports); ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/user.js"></script>
<script src="assets/map.js"></script>
</body>
</html>
