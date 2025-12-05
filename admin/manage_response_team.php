    <?php
    require_once __DIR__ .'../../app/utils/log.php';
    require_once __DIR__ .'../../app/api/data/dataProcess.php';

    $data_source_url = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=teams";//changes when deployed
    $teams = getDataSource($data_source_url); 

    $status = $_GET['status'] ?? 'All';
    if ($status !== 'All' && is_array($teams)) {
        
        if (isset($teams['success']) && $teams['success'] === false) {
        
        } else {
        
            $teams = array_filter(
                $teams,
                fn($r) => isset($r['is_active']) && (string)$r['is_active'] === (string)$status
            );
        }
    }

    $statuses = ['All' => 'All','Active' => '1','Inactive' => '0'];
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Manage Teams | Padre Garcia Reporting</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./assets/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom styles for better table appearance */
        .table-teams tbody tr {
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }
        .table-teams tbody tr:hover {
            background-color: #f0f8ff; /* Light blue on hover for better UX */
        }
        /* Style for the scroll container */
        .scroll-card {
            max-height: 70vh; /* Limit height for better scrolling experience */
            overflow-y: auto;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            padding: 0;
        }
        .main-content .card {
            /* Replaced card-custom with card and added shadow */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            border: none;
        }
        /* Ensure the table itself can scroll horizontally if needed due to many columns */
        .table-responsive-custom {
            overflow-x: auto;
        }
    </style>
    </head>
    <body>

    <?php require_once 'admin_sidebar.php'; ?>
    <div class="main-content">
        <div class="container mt-5">
            <div class="card shadow-lg border-0 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <h3 class="mb-0 text-primary">
                        <i class="fas fa-users-cog me-2"></i> Manage Response Teams
                    </h3>
                    <a href="create_team.php" class="btn btn-primary mt-2 mt-md-0 rounded-pill px-4 shadow-sm">
                        <i class="fas fa-plus me-1"></i> Create Response Team
                    </a>
                </div>

                <!-- Filter buttons -->
                <div class="d-flex justify-content-start mb-4 gap-2 flex-wrap border-bottom pb-3">
                    <span class="text-muted fw-bold me-2 align-self-center d-none d-sm-block">Filter By Status:</span>
                    <?php foreach ($statuses as $label => $value): ?>
                        <a href="?status=<?= $value ?>" class="btn btn-sm btn-outline-<?= match($label){
                            'Active'=>'success',
                            'Inactive'=>'warning',
                            default=>'secondary'
                        } ?> <?= ((string)$status === (string)$value)?'active':'' ?> rounded-pill px-3">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Search box container -->
                <div class="mb-4 d-flex justify-content-end">
                    <div class="input-group" style="max-width: 450px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="teamSearch" class="form-control border-start-0" placeholder="Search by name, contact, email, or address...">
                    </div>
                </div>

                <?php if (isset($teams) && isset($teams['success']) && $teams['success'] === false) : ?>
                    <div class="alert alert-danger text-center mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Error fetching teams: <?= htmlspecialchars($teams['message']) ?>
                    </div>
                <?php elseif (empty($teams) && $status !== 'All'): ?>
                    <div class="alert alert-info text-center mt-3" role="alert">
                        No teams found with the selected status.
                    </div>
                <?php elseif (empty($teams)): ?>
                    <div class="alert alert-info text-center mt-3" role="alert">
                        No response teams have been created yet.
                    </div>
                <?php else: ?>
                    <div class="scroll-card table-responsive-custom">
                        <table class="table table-striped table-hover table-teams align-middle mb-0">
                            <thead class="table-primary sticky-top shadow-sm">
                                <tr>
                                    <th class="text-center" style="width: 5%;">ID</th>
                                    <th style="width: 15%;">Name</th>
                                    <th style="width: 15%;">Contact</th>
                                    <th style="width: 20%;">Email</th>
                                    <th style="width: 15%;">Address</th>
                                    <th class="text-center" style="width: 5%;">Members</th>
                                    <th class="text-center" style="width: 10%;">Status</th>
                                    <th class="text-center" style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($teams as $r): 
                                $teamId = htmlspecialchars($r['team_id'] ?? '');
                                $teamName = htmlspecialchars($r['name'] ?? 'N/A');
                                $contact = htmlspecialchars($r['contact_number'] ?? 'N/A');
                                $email = htmlspecialchars($r['email'] ?? 'N/A');
                                $address = htmlspecialchars($r['address'] ?? 'N/A');
                                $members = $r['members'] ?? [];
                                $memberCount = count($members);
                                $isActive = (int)($r['is_active'] ?? 0);
                                $statusLabel = $isActive ? 'Active' : 'Inactive';
                                $statusColor = $isActive ? 'success' : 'warning';
                            ?>
                                <tr 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#teamModal<?= $teamId ?>"
                                    data-search-terms="<?= strtolower($teamName . ' ' . $contact . ' ' . $email . ' ' . $address . ' ' . $statusLabel) ?>"
                                    >
                                    <td class="text-center text-muted small"><?= $teamId ?></td>
                                    <td><?= $teamName ?></td>
                                    <td><?= $contact ?></td>
                                    <td><span class="text-truncate d-block" style="max-width: 200px;"><?= $email ?></span></td>
                                    <td><span class="text-truncate d-block" style="max-width: 150px;" title="<?= $address ?>"><?= $address ?></span></td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $memberCount ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-<?= $statusColor ?>"><?= $statusLabel ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- View Details button: Click on the row also triggers this -->
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#teamModal<?= $teamId ?>"
                                                    title="View Details"
                                                    onclick="event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTeamModal<?= $teamId ?>"
                                                    title="Edit Team"
                                                    onclick="event.stopPropagation();">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="confirmTeamAction('delete', <?= $teamId ?>); event.stopPropagation();" class="btn btn-sm btn-danger" title="Delete Team">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include __DIR__ .'/components/teamDetails.php'; ?>
                                
    <!-- Edit Team Modals -->
    <?php include __DIR__ . '/components/editTeams.php'; ?>

    <script>
        $(document).ready(function() {
            $('#teamSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                
                $('.table-teams tbody tr').filter(function() {
                    var searchTerms = $(this).data('search-terms') || '';
                    $(this).toggle(searchTerms.indexOf(value) > -1);
                });
            });
        });
    </script>

    <script>
        // if it's not defined in the PHP script block) ---
        const TEAM_ACTION_ENDPOINT = "../app/controllers/team_action.php"; 

        async function confirmTeamAction(action, id) {

            // For delete action, show confirmation dialog
            if (action === 'delete') {
                const result = await Swal.fire({
                    title: "Permanently delete this team?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545", // Red for danger
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it",
                    cancelButtonText: "Cancel"
                });

                if (result.isConfirmed) {
                    
                    // 1. Show Processing Dialog
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Deleting team. Please wait.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    
                    try {
                        // 2. Perform AJAX (Fetch) Call
                        const response = await fetch(TEAM_ACTION_ENDPOINT, {
                            method: 'POST',
                            headers: {
                                // Crucial: Use URL-encoded content type for PHP $_POST access
                                'Content-Type': 'application/x-www-form-urlencoded', 
                            },
                            // Send action and id as URL-encoded body
                            body: `action=${encodeURIComponent(action)}&id=${encodeURIComponent(id)}`
                        });

                        // Check for non-200 HTTP status
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        const data = await response.json();
                        
                        Swal.close(); // Close the processing dialog

                        // 3. Handle Server Response
                        if (data.success) {
                            // Success: Show message and reload the page
                            Swal.fire('Deleted!', data.message || 'Team successfully deleted.', 'success').then(() => {
                                // Reload the page to update the table/list
                                location.reload(); 
                            });
                        } else {
                            // Failure reported by the server (e.g., database error)
                            Swal.fire('Error!', data.message || 'Deletion failed due to an unknown server error.', 'error');
                        }

                    } catch (error) {
                        // 4. Handle Network/Connection Error
                        Swal.close();
                        console.error('Delete Team Fetch Error:', error);
                        Swal.fire('Connection Error!', `Could not delete team. Details: ${error.message}`, 'error');
                    }
                }
                return;
            }

            // Default fallback for unknown actions
            console.warn(`Unknown team action: ${action}`);
        }

        // Make the function globally available if it's not in the IIFE block
        window.confirmTeamAction = confirmTeamAction;
    </script>

    <!-- <script src="../admin/assets/admin.js"></script> -->
    <!-- <script src="../admin/assets/teamAction.js"></script> -->
    </body>
    </html>