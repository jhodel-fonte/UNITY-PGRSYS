<?php
require_once __DIR__ . '../../app/api/data/dataProcess.php';


$reports = getDataSource("http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=report");

$data_source_url_members = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=members";
$users_data = getDataSource($data_source_url_members);

$data_source_url_teams = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=teams";
$teams_data = getDataSource($data_source_url_teams);

$userCount = 0;
$teamCount = 0;
$totalReports = 0;
$months = [];
$totals = [];
$recentApprovedReports = [];

if (!is_array($reports) || (isset($reports['success']) && $reports['success'] === false)) {

    $reports = []; 
} else {

    $totalReports = count($reports);

    $uniqueUserIds = [];
    foreach ($reports as $report) {
        if (isset($report['user_id'])) {
            $uniqueUserIds[$report['user_id']] = true;
        }
    }
    $userCount = count($uniqueUserIds);


    if (is_array($teams_data) && (!isset($teams_data['success']) || $teams_data['success'] === true)) {

        if (isset($teams_data['data']) && is_array($teams_data['data'])) {
            $teamCount = count($teams_data['data']);
        }

        else if (!empty($teams_data)) {
            $teamCount = count($teams_data);
        }
    }

    $monthlyCounts = [];
    foreach ($reports as $report) {
        if (isset($report['created_at'])) {
            $monthKey = date('Y-m', strtotime($report['created_at']));
            $monthlyCounts[$monthKey] = ($monthlyCounts[$monthKey] ?? 0) + 1;
        }
    }
    ksort($monthlyCounts);

    $months = array_map(function ($key) {
        return date('M Y', strtotime($key . '-01'));
    }, array_keys($monthlyCounts));
    $totals = array_values($monthlyCounts);

    $approvedReports = array_filter($reports, fn($r) => ($r['status'] ?? '') === 'Approved');

    // Sort by date (newest first)
    usort($approvedReports, function ($a, $b) {
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });

    // Take the top 5 recent approved reports
    $recentApprovedReports = array_slice($approvedReports, 0, 5);
}

// Map Data Preparation: Prepare data for map markers (Lat/Lng)
// This data will be picked up by the map initialization script in admin.js
$mapReports = array_filter($reports, fn($r) => isset($r['latitude'], $r['longitude']) && is_numeric($r['latitude']));
$mapMarkers = array_map(fn($r) => [
    'lat' => $r['latitude'],
    'lng' => $r['longitude'],
    'title' => htmlspecialchars($r['name'] ?? 'Report ' . $r['id'])
], $mapReports);

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
                    <h1 class="count" data-value="<?= $teamCount ?>"><?= $teamCount ?></h1>
                    <p>Response Team</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="admin-card">
                    <h1 class="count" data-value="<?= $totalReports ?>">0</h1>
                    <p>Total Reports</p>
                </div>
            </div>
        </div>

        <br>

        <h3 class="chart-title text-center mb-3">Reports Overview</h3>

        <div class="reports-wrapper">

            <div class="map-container" id="map"></div>

            <div class="chart-container">
                <div class="chart-card">

                    <div class="chart-card-header">
                        <h4>Monthly Reports</h4>
                    </div>

                    <div class="chart-card-body">
                        <canvas id="monthlyChart"></canvas>
                    </div>

                </div>

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
                                <?php if (empty($recentApprovedReports)): ?>
                                    <tr>
                                        <td colspan="4" class="text-muted">No recent approved reports found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentApprovedReports as $report): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($report['name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($report['report_type'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($report['location'] ?? $report['address'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($report['created_at'] ?? 'now'))) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>


    </div>

    <script>
        // Pass the calculated data to JavaScript for the chart
        let chartMonths = <?= json_encode($months) ?>;
        let chartTotals = <?= json_encode($totals) ?>;

        // Pass the map marker data to JavaScript
        let mapMarkers = <?= json_encode($mapMarkers) ?>;
    </script>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="../admin/assets/admin.js"></script>

</body>

</html>