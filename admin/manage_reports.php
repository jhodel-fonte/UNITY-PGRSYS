<?php
// FIX 1: Corrected include paths to use the directory separator '/'
require_once __DIR__ . '../../app/utils/log.php';
require_once __DIR__ . '../../app/api/data/dataProcess.php';

$data_source_url = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=report";

// Get reports data
$reports = getDataSource($data_source_url);

$status = $_GET['status'] ?? 'All';
if ($status !== 'All' && is_array($reports)) {
    
    if (isset($reports['success']) && $reports['success'] === false) {
    } else {
        $reports = array_filter($reports, fn($r) => isset($r['status']) && $r['status'] === $status);
    }
}

// Preserve API error message if it exists
$api_error = (isset($api_response['success']) && $api_response['success'] === false) ? $api_response : null;
$status = $_GET['status'] ?? 'All';
$statuses = ['All','Pending','Approved','Ongoing','Resolved'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports | Padre Garcia Reporting</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css"> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Custom styles for better table appearance */
    .table-reports tbody tr {
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }
    .table-reports tbody tr:hover {
        background-color: #f0f8ff; /* Light blue on hover for better UX */
    }
    /* Ensure the search bar takes full width of its container */
    #reportSearch {
        width: 100%;
        max-width: 500px; 
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .scroll-card {
        max-height: 70vh; /* Limit height for better scrolling experience */
        overflow-y: auto;
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0;
    }
</style>
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <div class="card shadow-lg border-0 p-4">
            <h3 class="mb-4 text-primary">
                <i class="fas fa-list-alt me-2"></i> Manage Reports
            </h3>

            <div class="d-flex justify-content-start mb-4 gap-2 flex-wrap border-bottom pb-3">
                <span class="text-muted fw-bold me-2 align-self-center d-none d-sm-block">Filter By Status:</span>
                <?php foreach ($statuses as $s): ?>
                    <a href="?status=<?= $s ?>" class="btn btn-sm btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Ongoing'=>'info',
                        'Resolved'=>'primary',
                        default=>'secondary' // Use secondary for 'All'
                    } ?> <?= ($status==$s)?'active':'' ?> rounded-pill px-3">
                        <?= $s ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="mb-4 d-flex justify-content-end">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="reportSearch" class="form-control border-start-0" placeholder="Search by user, title, or category...">
                </div>
            </div>

            <?php 
            // FIX 3: Check for API error
            if ($api_error) : ?>
                <div class="alert alert-danger text-center mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> Error fetching reports: <?= htmlspecialchars($api_error['message']) ?>
                </div>
            <?php 
            // FIX 4: Check if $reports array is empty
            elseif (empty($reports) && $status !== 'All'): ?>
                <div class="alert alert-info text-center mt-3" role="alert">
                    No reports found with status: <strong><?= $status ?></strong>.
                </div>
            <?php elseif (empty($reports)): ?>
                <div class="alert alert-info text-center mt-3" role="alert">
                    No reports have been submitted yet.
                </div>
            <?php else: ?>
                <div class="scroll-card">
                    <table class="table table-striped table-hover table-reports align-middle mb-0">
                        <thead class="table-primary sticky-top shadow-sm">
                            <tr>
                                <th class="text-center" style="width: 5%;">#</th>
                                <th style="width: 15%;">User</th> <th style="width: 25%;">Title</th>
                                <th style="width: 15%;">Category</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th style="width: 10%;">Date</th>
                                <th class="text-center" style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $i = 1;
                        // $reports array now contains only the report data
                        foreach ($reports as $r): 
                            $reportId = htmlspecialchars($r['id'] ?? '');
                            // FIX 6: Correctly calculate user full name, falling back to report 'name' or 'Anonymous'
                            $userFullName = htmlspecialchars(trim(($r['firstName'] ?? '') . ' ' . ($r['lastName'] ?? '')) ?: $r['name'] ?? 'Anonymous');
                            $reportTitle = htmlspecialchars($r['name'] ?? 'Untitled');
                            $reportCategory = htmlspecialchars($r['report_type'] ?? 'N/A');
                            $reportStatus = htmlspecialchars($r['status'] ?? 'Unknown');
                        ?>
                            <tr 
                                data-bs-toggle="modal" 
                                data-bs-target="#reportModal<?= $reportId ?>"
                                data-search-terms="<?= strtolower($userFullName . ' ' . $reportTitle . ' ' . $reportCategory . ' ' . $reportStatus . ' ' . ($r['address'] ?? '') . ' ' . ($r['location'] ?? '')) ?>"
                                >
                                <td class="text-center text-muted small"><?= $i++ ?></td>
                                <td><?= $userFullName ?></td> <td><?= $reportTitle ?></td>
                                <td><?= $reportCategory ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-<?= match($reportStatus){
                                        'Approved'=>'success',
                                        'Pending'=>'warning',
                                        'Ongoing'=>'info',
                                        'Resolved'=>'primary',
                                        default=>'secondary'
                                    } ?>"><?= $reportStatus ?></span>
                                </td>
                                <td><?= htmlspecialchars($r['created_at'] ?? 'N/A') ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reportModal<?= $reportId ?>" 
                                                    title="View Details"
                                                    onclick="event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if ($reportStatus=='Pending'): ?>
                                            <button onclick="confirmAction('approve', <?= $r['id'] ?>); event.stopPropagation();" class="btn btn-success btn-sm" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="confirmAction('reject', <?= $r['id'] ?>); event.stopPropagation();" class="btn btn-danger btn-sm" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button onclick="confirmAction('delete', <?= $r['id'] ?>); event.stopPropagation();" class="btn btn-outline-danger btn-sm" title="Delete Report">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/components/reportDetail.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Simplified client-side search logic
    document.getElementById('reportSearch').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table-reports tbody tr');

        rows.forEach(row => {
            const searchTerms = row.getAttribute('data-search-terms');
            if (searchTerms && searchTerms.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Define a placeholder for confirmAction if it's not defined in the included JS
    if (typeof confirmAction === 'undefined') {
        window.confirmAction = function(action, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${action} report #${id}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${action} it!`
            }).then((result) => {
                if (result.isConfirmed) {
                    // Placeholder for actual AJAX/form submission to handle action
                    console.log(`${action} action confirmed for ID: ${id}`);
                    // window.location.href = `action.php?id=${id}&action=${action}`; // Example
                }
            });
        }
    }
</script>

<script src="../admin/assets/teamAction.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>