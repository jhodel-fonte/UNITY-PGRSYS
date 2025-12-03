<?php
// Temporary array
$assignedReports = [
    [
        "id" => 101,
        "type" => "Fire",
        "location" => "Brgy. Malaya",
        "date" => "Nov 25, 2025",
        "status" => "Pending",
        "lat" => 13.834,
        "lng" => 121.218,
        "image" => "fire.jpg",
        "notes" => "Reported strong flames near residential area."
    ],
    [
        "id" => 102,
        "type" => "Accident",
        "location" => "National Highway",
        "date" => "Nov 25, 2025",
        "status" => "In Progress",
        "lat" => 13.837,
        "lng" => 121.212,
        "image" => "accident.jpg",
        "notes" => "Two motorcycles crashed."
    ],
    [
        "id" => 103,
        "type" => "Rescue",
        "location" => "Brgy. Sta. Cruz",
        "date" => "Nov 24, 2025",
        "status" => "Resolved",
        "lat" => 13.830,
        "lng" => 121.220,
        "image" => "rescue.jpg",
        "notes" => "Cat rescued from drainage."
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assigned Reports | Response Team</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="../response_team/assets/rteam.css">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>

<?php include 'sidebar.php'; ?>

<main class="rteam-main">

    <h1 class="page-title">Assigned Reports</h1>

    <!-- 🔍 FILTER + SEARCH -->
    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <div class="filters">
            <button class="filter-btn active" data-filter="All">All</button>
            <button class="filter-btn" data-filter="Pending">Pending</button>
            <button class="filter-btn" data-filter="In Progress">In Progress</button>
            <button class="filter-btn" data-filter="Resolved">Resolved</button>
        </div>

        <input type="text" id="searchInput" class="search" placeholder="Search reports...">
    </div>

    <!-- 🟦 CARDS GRID -->
    <div class="report-cards" id="reportCards">
        <?php foreach ($assignedReports as $r): ?>
        <div class="report-card"
             data-status="<?= $r['status']; ?>"
             data-type="<?= strtolower($r['type']); ?>"
             data-location="<?= strtolower($r['location']); ?>">

            <div class="card-header">
                <h3><?= $r["type"]; ?></h3>
                <span class="status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>">
                    <?= $r["status"]; ?>
                </span>
            </div>

            <p class="location"><i class="fa-solid fa-location-dot"></i> <?= $r["location"]; ?></p>
            <p class="date">Assigned: <?= $r["date"]; ?></p>

            <div class="card-buttons">
                <button class="btn-view" data-report='<?= json_encode($r); ?>'>View</button>

                <?php if ($r["status"] === "Pending"): ?>
                    <button class="btn-start">Start Response</button>
                <?php endif; ?>

                <?php if ($r["status"] === "In Progress"): ?>
                    <button class="btn-resolve">Mark Resolved</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</main>


<!-- 🔵 VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">

    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Report Details</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

          <div class="row">
              <div class="col-md-6">
                  <p><strong>Type:</strong> <span id="modalType"></span></p>
                  <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                  <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                  <p><strong>Date:</strong> <span id="modalDate"></span></p>
                  <p><strong>Description:</strong></p>
                  <p id="modalNotes"></p>

                  <img id="modalImage" class="img-fluid rounded mt-3 mb-3" alt="">
              </div>

              <div class="col-md-6">
                  <div id="modalMap"></div>
                  <button id="openRouteBtn" class="btn btn-primary mt-3">
                    Open Navigation Map
                  </button>


                  <h5 class="mt-3">Status Timeline</h5>
                  <ul class="timeline" id="timeline"></ul>
              </div>
          </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../response_team/assets/view.js"></script>
<script src="../response_team/assets/rteam.js"></script>
</body>
</html>
