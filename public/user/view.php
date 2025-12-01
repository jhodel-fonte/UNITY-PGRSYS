<?php
// TEMP DATA (Replace later with database)
$myReports = [
        [
        "id" => 1,
        "category" => "Fire",
        "status" => "Pending",
        "description" => "poop near barangay hall.",
        "date" => "Nov 25, 2025",
        "location" => "Barangay Hall",
        "image" => "fire.jpg"
    ],
    [
        "id" => 2,
        "category" => "Others",
        "status" => "Ongoing",
        "description" => "Two vehicles collided.",
        "date" => "Nov 20, 2025",
        "location" => "National Highway",
        "image" => "accident.jpg"
    ],
    [
        "id" => 3,
        "category" => "Electrical Hazzard",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 4,
        "category" => "Rescue",
        "status" => "Resolved",
        "description" => "Fallen tree blocking road.",
        "date" => "Nov 18, 2025",
        "location" => "Brgy. Mabini",
        "image" => "pg.jpg"
    ],
    [
        "id" => 5,
        "category" => "fire",
        "status" => "Pending",
        "description" => "broken street light.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 6,
        "category" => "Others",
        "status" => "Resolved",
        "description" => "There is something on the tree.",
        "date" => "Nov 18, 2025",
        "location" => "Brgy. Mabini",
        "image" => "tree.jpg"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reports | Unity Padre Garcia</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="../user/assets/user.css">

</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="view-main">
        <h1 class="page-title">My Reports</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="btn-group gap-2 flex-wrap">
        <button class="btn btn-outline-dark filter-btn active" data-filter="All">All</button>
        <button class="btn btn-outline-warning filter-btn" data-filter="Pending">Pending</button>
        <button class="btn btn-outline-primary filter-btn" data-filter="Ongoing">Ongoing</button>
        <button class="btn btn-outline-success filter-btn" data-filter="Resolved">Resolved</button>
    </div>

    <input type="text" id="searchInput" class="form-control w-25"
        placeholder="Search reports...">
</div>

        <div class="report-list-card scrollable-table shadow-sm">

            <table class="table table-hover align-middle">
                <thead class="table-dark ">
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody id="reportTableBody">
<?php foreach ($myReports as $r): ?>
    <tr data-status="<?= $r['status'] ?>">
        <td><?= $r["id"]; ?></td>
        <td><?= $r["category"]; ?></td>

        <td>
            <span class="badge 
                <?= $r["status"] === "Resolved" ? "bg-success" : ($r["status"] === "Ongoing" ? "bg-primary" : "bg-warning text-dark"); ?>">
                <?= $r["status"]; ?>
            </span>
        </td>

        <td><?= $r["date"]; ?></td>

        <td class="text-center">
            <button 
                class="btn btn-sm btn-outline-primary view-btn"
                data-report='<?= json_encode($r); ?>'>
                View
            </button>

            <?php if ($r["status"] !== "Resolved" && $r["status"] !== "Ongoing"): ?>
                <button class="btn btn-sm btn-outline-secondary edit-btn">Edit</button>
                <button class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>


            </table>

        </div>
    </div>

</main>



<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Report Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p><strong>Category:</strong> <span id="modalCategory"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <p><strong>Description:</strong></p>
        <p id="modalDescription"></p>

        <img id="modalImage" class="img-fluid rounded mt-3" alt="Report Image">
        <div id="modalMap" style="height: 250px;" class="mt-3 rounded"></div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../user/assets/user.js"></script>
<script src="../user/assets/view.js"></script>
</html>
