<?php
// demo report-status.php
session_start();

$reports = [
    [
        "id" => 101,
        "category" => "Fire",
        "status" => "Approved",    
        "date" => "2025-11-20",
        "location" => "Brgy. Pagasa",
        "image" => "smoke.jpg",
        // gawa-gawa lang 
        "team_lat" => 13.9333,
        "team_lng" => 121.1167,
        "user_lat" => 13.9340,
        "user_lng" => 121.1160
    ],
    [
        "id" => 102,
        "category" => "Electrical Hazzard",
        "status" => "Dispatched",
        "date" => "2025-11-18",
        "location" => "Brgy. Merville",
        "image" => "../assets/img/pg.jpg",
        "team_lat" => 13.9400,
        "team_lng" => 121.1200,
        "user_lat" => 13.9390,
        "user_lng" => 121.1190
    ],
    [
        "id" => 103,
        "category" => "Others",
        "description" => "may tae sa kalsada",
        "status" => "Ongoing",
        "date" => "2025-11-17",
        "location" => "Brgy. Mabini",
        "image" => "pgsrsBG.jpg",
        "team_lat" => 13.9250,
        "team_lng" => 121.1100,
        "user_lat" => 13.9240,
        "user_lng" => 121.1090
    ],
    [
        "id" => 104,
        "category" => "Others",
        "status" => "Resolved",
        "date" => "2025-11-12",
        "location" => "Brgy. Mabini",
        "image" => "pg.jpg",
        "team_lat" => 13.9300,
        "team_lng" => 121.1130,
        "user_lat" => 13.9305,
        "user_lng" => 121.1125
    ],
];

$statusSteps = ["Approved" => 1, "Dispatched" => 2, "Ongoing" => 3, "Resolved" => 4];

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Report Status | Unity PGSRS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="../user/assets/user.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="user-main">
    <h1 class="page-title">My Report Status</h1>

    <div class="status-grid">
        <?php foreach ($reports as $r): 
            $step = $statusSteps[$r["status"]] ?? 1;
        ?>
        <article class="status-card" data-report='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'>
            <div class="card-media">
                <img src="<?= "../assets/img/" . ($r["image"] ?: "placeholder.png") ?>" alt="<?= htmlspecialchars($r['category']) ?>">
            </div>

            <div class="card-body">
                <h4 class="report-category"><?= htmlspecialchars($r['category']) ?></h4>
                <div class="meta">
                    <span class="badge bg-secondary"><?= htmlspecialchars($r['location']) ?></span>
                    <small class="ms-2 text-muted"><?= htmlspecialchars($r['date']) ?></small>
                </div>

                <div class="timeline" data-step="<?= $step ?>">
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill"></div>
                    </div>
                    <div class="steps">
                        <div class="step">
                            <div class="dot">✓</div>
                            <div class="label">Approved</div>
                        </div>
                        <div class="step">
                            <div class="dot">🚚</div>
                            <div class="label">Dispatched</div>
                        </div>
                        <div class="step">
                            <div class="dot">🔧</div>
                            <div class="label">Ongoing</div>
                        </div>
                        <div class="step">
                            <div class="dot">🏁</div>
                            <div class="label">Resolved</div>
                        </div>
                    </div>
                </div>

                <div class="card-actions">
                    <button class="btn btn-sm btn-primary view-status">View</button>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Report Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <img id="modalImage" class="img-fluid rounded mb-3" src="" alt="report image">
                <h5 id="modalTitle"></h5>
                <p id="modalLocation" class="text-muted"></p>
                <p id="modalDate" class="text-muted small"></p>
                <p id="modalDescription"></p>
            </div>

            <div class="col-md-6">
                <div id="modalMap" style="width:100%;height:420px;border-radius:8px;overflow:hidden;"></div>
                <div class="mt-2 d-flex gap-2">
                    <button id="zoomTeam" class="btn btn-outline-primary btn-sm">Zoom to Team</button>
                    <button id="zoomUser" class="btn btn-outline-secondary btn-sm">Zoom to Report</button>
                </div>
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const GEOAPIFY_KEY = "6cbe5b314ed44817b7e1e51d35b6ec27"; // 
const DEMO_REPORTS = <?= json_encode($reports) ?>;
</script>
<script src="../user/assets/user.js"></script>
<script src="../user/assets/status.js"></script>
</body>
</html>
