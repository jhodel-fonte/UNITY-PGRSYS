<?php
// Sample historical reports data
$reportHistory = [
    [
        "id" => 201,
        "type" => "Fire",
        "location" => "Brgy. Malaya",
        "date" => "Nov 20, 2025",
        "status" => "Resolved",
        "resolvedDate" => "Nov 22, 2025",
        "duration" => "2 days",
        "image" => "fire.jpg",
        "notes" => "Fire extinguished successfully. No casualties reported."
    ],
    [
        "id" => 202,
        "type" => "Accident",
        "location" => "National Highway",
        "date" => "Nov 18, 2025",
        "status" => "Resolved",
        "resolvedDate" => "Nov 19, 2025",
        "duration" => "1 day",
        "image" => "accident.jpg",
        "notes" => "Accident scene cleared. All victims transported to hospital."
    ],
    [
        "id" => 203,
        "type" => "Rescue",
        "location" => "Brgy. Sta. Cruz",
        "date" => "Nov 15, 2025",
        "status" => "Resolved",
        "resolvedDate" => "Nov 15, 2025",
        "duration" => "2 hours",
        "image" => "rescue.jpg",
        "notes" => "Person rescued from flood. Provided with medical assistance."
    ],
    [
        "id" => 204,
        "type" => "Medical Emergency",
        "location" => "Brgy. Kanluran",
        "date" => "Nov 12, 2025",
        "status" => "Resolved",
        "resolvedDate" => "Nov 12, 2025",
        "duration" => "1 hour",
        "image" => "medical.jpg",
        "notes" => "Patient stabilized and transported to medical facility."
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports History | Response Team</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="../response_team/assets/admin.css">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="dash-content">

    <h2 class="text-center dashboard-title">Reports History</h2>

    <!-- SEARCH & FILTER -->
    <div class="row mb-4 mt-2">
        <div class="col-md-12 d-flex justify-content-center gap-3 flex-wrap">
            <input type="text" id="reportSearch" class="form-control" style="max-width: 400px;" placeholder="Search by ID, type, or location...">
            
            <button class="btn btn-primary filter-btn active" data-filter="All">All</button>
            <button class="btn btn-outline-primary filter-btn" data-filter="Resolved">Resolved</button>
            <button class="btn btn-outline-primary filter-btn" data-filter="Completed">Completed</button>
        </div>
    </div>

    <h3 class="chart-title text-center mb-3">Completed Reports Summary</h3>

    <!-- STATS CARDS -->
    <div class="row g-4 mb-4 mt-2 justify-content-center">
        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= count($reportHistory) ?>">0</h1>
                <p>Total Reports</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= count($reportHistory) ?>">0</h1>
                <p>All Resolved</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= rand(85, 99) ?>">0</h1> 
                <p>Completion Rate</p>
            </div>
        </div>
    </div>

    <br>

    <div class="reports-wrapper">

        <!-- RECENT REPORTS TABLE -->
        <div class="recent-reports mt-4" style="width: 100%;">
            <h3 class="text-center mb-3">Historical Reports</h3>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Report ID</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Assigned Date</th>
                            <th>Resolved Date</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                        <?php foreach ($reportHistory as $r): ?>
                        <tr data-status="<?= $r['status']; ?>" data-type="<?= strtolower($r['type']); ?>" data-location="<?= strtolower($r['location']); ?>">
                            <td><?= $r["id"]; ?></td>
                            <td><?= $r["type"]; ?></td>
                            <td><?= $r["location"]; ?></td>
                            <td><?= $r["date"]; ?></td>
                            <td><?= $r["resolvedDate"]; ?></td>
                            <td>
                                <span class="badge bg-success"><?= $r["duration"]; ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-view" data-report='<?= json_encode($r); ?>' data-bs-toggle="modal" data-bs-target="#viewModal">View</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Historical Report Details</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

          <div class="row">
              <div class="col-md-6">
                  <p><strong>Report ID:</strong> <span id="modalId"></span></p>
                  <p><strong>Type:</strong> <span id="modalType"></span></p>
                  <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                  <p><strong>Assigned Date:</strong> <span id="modalDate"></span></p>
                  <p><strong>Resolved Date:</strong> <span id="modalResolvedDate"></span></p>
                  <p><strong>Duration:</strong> <span id="modalDuration"></span></p>
                  <p><strong>Notes:</strong></p>
                  <p id="modalNotes" style="background: #f5f5f5; padding: 10px; border-radius: 8px;"></p>

                  <img id="modalImage" class="img-fluid rounded mt-3 mb-3" alt="" style="display: none;">
              </div>

              <div class="col-md-6">
                  <div style="background: #e8f5e9; padding: 20px; border-radius: 10px; text-align: center;">
                      <i class="fa-solid fa-check-circle" style="font-size: 48px; color: #4caf50; margin-bottom: 10px;"></i>
                      <h5 style="color: #2e7d32; margin-top: 10px;">Report Status</h5>
                      <p style="font-size: 18px; color: #43a047; font-weight: bold;">✓ RESOLVED</p>
                      <hr>
                      <p><strong>Completion Summary:</strong></p>
                      <p id="modalSummary" style="font-size: 14px; color: #555;"></p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../response_team/assets/admin.js"></script>
<script>
// Report search and filter functionality
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('reportSearch');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('#historyTableBody tr');
    let currentFilter = 'All';

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const query = searchInput.value.toLowerCase();
            tableRows.forEach(row => {
                const id = row.children[0]?.textContent.toLowerCase() || '';
                const type = row.children[1]?.textContent.toLowerCase() || '';
                const location = row.children[2]?.textContent.toLowerCase() || '';
                
                const matches = id.includes(query) || type.includes(query) || location.includes(query);
                const statusMatch = currentFilter === 'All' || row.getAttribute('data-status') === currentFilter;
                
                row.style.display = (matches && statusMatch) ? '' : 'none';
            });
        });
    }

    // Filter functionality
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter');

            tableRows.forEach(row => {
                const status = row.getAttribute('data-status');
                const searchQuery = searchInput ? searchInput.value.toLowerCase() : '';
                
                const id = row.children[0]?.textContent.toLowerCase() || '';
                const type = row.children[1]?.textContent.toLowerCase() || '';
                const location = row.children[2]?.textContent.toLowerCase() || '';
                
                const matches = id.includes(searchQuery) || type.includes(searchQuery) || location.includes(searchQuery);
                const statusMatch = currentFilter === 'All' || status === currentFilter;
                
                row.style.display = (matches && statusMatch) ? '' : 'none';
            });
        });
    });

    // Modal view functionality
    const viewBtns = document.querySelectorAll('.btn-view');
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const report = JSON.parse(this.getAttribute('data-report'));
            
            document.getElementById('modalId').textContent = report.id;
            document.getElementById('modalType').textContent = report.type;
            document.getElementById('modalLocation').textContent = report.location;
            document.getElementById('modalDate').textContent = report.date;
            document.getElementById('modalResolvedDate').textContent = report.resolvedDate;
            document.getElementById('modalDuration').textContent = report.duration;
            document.getElementById('modalNotes').textContent = report.notes;
            document.getElementById('modalSummary').textContent = `This ${report.type.toLowerCase()} incident at ${report.location} was assigned on ${report.date} and successfully resolved on ${report.resolvedDate} (${report.duration}).`;
            
            const img = document.getElementById('modalImage');
            img.src = '../uploads/reports/' + report.image;
        });
    });
});
</script>
</body>
</html>
