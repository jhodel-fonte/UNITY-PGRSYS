<?php
session_start();

require_once __DIR__ . '../../../app/api/data/dataProcess.php'; 

$user = (isset($_SESSION['userLoginData'])) ? $_SESSION['userLoginData']['data'] : null;

if (!$user || !isset($user['pgCode'])) {
    header("Location: login.php"); 
    exit;
}

$pgCode = $user['pgCode'];

$data_source_url = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=reportbyId&id=" . $pgCode;
$userReports = [];
$userReports = getDataSource($data_source_url);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reports | Unity Padre Garcia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <link rel="stylesheet" href="assets/user.css">

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
                        <th>Classification</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody id="reportTableBody">
<?php 
if (empty($userReports)): ?>
    <tr>
        <td colspan="5" class="text-center text-muted">No reports found.</td>
    </tr>
<?php 
else:
// Loop through the API data ($userReports)
foreach ($userReports as $r): 
    // Determine status for class/filtering
    $status = htmlspecialchars($r['status'] ?? 'N/A');
    
    // Get the category name (assuming 'classification' holds the category ID, but we display the ID for now)
    $categoryDisplay = htmlspecialchars($r['classification'] ?? 'N/A');
    
    // Convert the data array to a JSON string for the 'View' button
    $reportJson = json_encode($r);
?>
    <tr data-status="<?= $status ?>">
        <td><?= htmlspecialchars($r["id"]); ?></td>
        <td><?= $categoryDisplay; ?></td> 
        
        <td>
            <span class="badge 
                <?= $status === "Resolved" ? "bg-success" : ($status === "Ongoing" ? "bg-primary" : "bg-warning text-dark"); ?>">
                <?= $status; ?>
            </span>
        </td>

        <td><?= date("M d, Y", strtotime($r["created_at"] ?? '')); ?></td>

        <td class="text-center">
            <button 
                class="btn btn-sm btn-outline-primary view-btn"
                data-report='<?= htmlspecialchars($reportJson, ENT_QUOTES, 'UTF-8'); ?>'>
                View
            </button>

            <?php if ($status !== "Resolved" && $status !== "Ongoing"): ?>
                <button class="btn btn-sm btn-outline-secondary edit-btn">Edit</button>
                <button class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
            <?php endif; ?>
        </td>
    </tr>
<?php 
endforeach; 
endif;
?>
</tbody>


            </table>

        </div>
    </div>

</main>



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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// --- view.js logic integrated here ---
$(document).ready(function() {
    
    // --- 1. Filter and Search Logic ---
    
    // Filter by status
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        const filterValue = $(this).data('filter');
        
        $('#reportTableBody tr').each(function() {
            const rowStatus = $(this).data('status');
            if (filterValue === 'All' || rowStatus === filterValue) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        
        $('#reportTableBody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            const isVisibleByFilter = $('.filter-btn.active').data('filter') === 'All' || $(this).data('status') === $('.filter-btn.active').data('filter');
            
            if (rowText.includes(searchText) && isVisibleByFilter) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // --- 2. View Modal Logic ---
    
    let map = null;
    let marker = null;
    
    $('.view-btn').on('click', function() {
        const reportData = JSON.parse($(this).attr('data-report'));
        
        // 1. Populate Text Fields
        $('#modalCategory').text(reportData.classification); // Using 'classification' from API data
        $('#modalStatus').text(reportData.status);
        $('#modalDate').text(new Date(reportData.created_at).toLocaleDateString()); // Format date nicely
        $('#modalLocation').text(reportData.location);
        $('#modalDescription').text(reportData.description);
        
        // 2. Handle Image
        const imagePath = (reportData.images && reportData.images.length > 0) ? reportData.images[0].photo : null;
        
        if (imagePath) {
            // FIX: Remove '../' prefixes from the path if they were added for the API, assuming root is needed
            // The path in your JSON sample is: "..\/uploads\/reports\/..."
            const cleanImagePath = imagePath.replace(/\.\.\//g, ''); 
            $('#modalImage').attr('src', cleanImagePath).show();
        } else {
            $('#modalImage').hide();
        }

        // 3. Handle Map
        const lat = parseFloat(reportData.latitude);
        const lng = parseFloat(reportData.longitude);

        const modalElement = document.getElementById('viewModal');
        const viewModal = new bootstrap.Modal(modalElement);
        
        // Show modal first, then initialize map once it's visible
        viewModal.show();
        
        // Event fires when the modal has been made visible to the user
        modalElement.addEventListener('shown.bs.modal', function () {
            
            if (isNaN(lat) || isNaN(lng)) {
                // If location data is invalid, hide map or display message
                $('#modalMap').html('<p class="text-center text-muted">Location data unavailable.</p>');
                if (map) map.remove(); // Remove existing map instance
                map = null;
                return;
            }

            // Map initialization/update logic
            if (map === null) {
                // Initialize map if it doesn't exist
                map = L.map('modalMap').setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                marker = L.marker([lat, lng]).addTo(map);
            } else {
                // Map already exists, just update view and marker
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
            }
            
            // Invalidate size is necessary for Leaflet inside a Bootstrap modal
            map.invalidateSize();
        });
    });
});
// --- End of view.js logic ---
</script>
<script src="assets/user.js"></script> 
</body>
</html>