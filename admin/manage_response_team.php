<?php
require_once __DIR__ .'../../app/utils/log.php';
require_once __DIR__ .'../../app/api/data/dataProcess.php';

$data_source_url = "http://localhost/pgrs-g6/request/getData.php?data=teams";//changes when deployed
$reports = getDataSource($data_source_url);

$status = $_GET['status'] ?? 'All';
if ($status !== 'All' && is_array($reports)) {
    $reports = array_filter(
        $reports,
        fn($r) => isset($r['is_active']) && (string)$r['is_active'] === (string)$status
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Teams | Padre Garcia Reporting</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="./assets/admin.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php require_once 'admin_sidebar.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h3 class="text text-left mb-4">Manage Teams</h3>
                <a href="create_team.php" class="btn btn-primary mt-2 mt-md-0"> Create Response Team</a>
            </div>

            <!-- Filter buttons -->
            <div class="d-flex justify-content-center mb-3 gap-2 flex-wrap">
                <?php 
                $statuses = ['All' => 'All','Active' => '1','Inactive' => '0'];
                foreach ($statuses as $label => $value): ?>
                    <a href="?status=<?= $value ?>" class="btn btn-outline-<?= match($label){
                        'Active'=>'success',
                        'Inactive'=>'info',
                        default=>'dark'
                    } ?> <?= ((string)$status === (string)$value)?'active':'' ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search box -->
            <div class="mb-3 d-flex justify-content-center">
                <input type="text" id="reportSearch" class="form w-50" placeholder="Search reports by user, title, or category...">
            </div>

            <?php if (isset($reports) && isset($reports['success']) && $reports['success'] === false) : ?>
                <p class="text-center"><?= htmlspecialchars($reports['message']) ?></p>
            <?php else: ?>
                <div class="table-responsive scroll-card">
                    <table class="table table-white table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['team_id']) ?></td>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['contact_number']) ?></td>
                                <td>
                                    <span class="badge bg-<?= match($r['is_active']){
                                        1 =>'success',
                                        0 =>'warning',
                                        default=>'secondary'
                                    } ?>"><?= ($r['is_active'] == 1) ? 'Active' : 'Not Active' ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#teamModal<?= htmlspecialchars($r['team_id'] ?? '') ?>">
                                        View Details
                                    </button>
                                    <button class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editTeamModal<?= htmlspecialchars($r['team_id'] ?? '') ?>">
                                        Edit
                                    </button>
                                    <button onclick="confirmTeamAction('delete', <?= htmlspecialchars($r['team_id']) ?>)" class="btn btn-danger btn-sm">Delete</button>
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

<!-- Team Detail Modals -->
<?php foreach ($reports as $team):
    $teamId = htmlspecialchars($team['team_id'] ?? '');
    $teamName = htmlspecialchars($team['name'] ?? 'Unknown Team');
    $contact = htmlspecialchars($team['contact_number'] ?? 'N/A');
    $email = htmlspecialchars($team['email'] ?? 'N/A');
    $address = htmlspecialchars($team['address'] ?? 'N/A');
    $statusLabel = ($team['is_active'] ?? 0) ? 'Active' : 'Inactive';
    $statusBadge = ($team['is_active'] ?? 0) ? 'success' : 'secondary';
    $latitude = $team['latitude'] ?? null;
    $longitude = $team['longitude'] ?? null;
    $members = $team['members'] ?? [];
?>

<div class="modal fade" id="teamModal<?= $teamId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-neon">Team Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Team ID:</strong> <?= $teamId ?></p>
                        <p><strong>Name:</strong> <?= $teamName ?></p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-<?= $statusBadge ?>"><?= $statusLabel ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Contact:</strong> <?= $contact ?></p>
                        <p><strong>Email:</strong> <?= $email ?></p>
                        <p><strong>Address:</strong> <?= $address ?></p>
                    </div>
                </div>

                <?php if ($latitude && $longitude): ?>
                    <div class="rounded overflow-hidden mb-3" style="height: 300px;">
                        <iframe
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            allowfullscreen
                            src="https://www.google.com/maps?q=<?= urlencode($latitude) ?>,<?= urlencode($longitude) ?>&z=14&output=embed">
                        </iframe>
                    </div>
                <?php endif; ?>

                <hr class="border-secondary">
                <h5 class="text-neon mb-3">Team Members</h5>
                <?php if (!empty($members)): ?>
                    <div class="list-group">
                        <?php foreach ($members as $member):
                            $memberName = htmlspecialchars(trim(($member['firstName'] ?? '') . ' ' . ($member['lastName'] ?? '')) ?: 'Unnamed Member');
                            $memberContact = htmlspecialchars($member['contact_number'] ?? $member['email'] ?? 'No contact info');
                        ?>
                        <div class="list-group-item bg-dark text-light border-secondary mb-2">
                            <strong><?= $memberName ?></strong><br>
                            <small><?= $memberContact ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No members assigned to this team.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Edit Team Modals -->
<?php include __DIR__ . '/components/editTeams.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="../admin/assets/admin.js"></script> -->
<script src="../admin/assets/admin.js"></script>
<script src="../admin/assets/teamAction.js"></script>
</body>
</html>
