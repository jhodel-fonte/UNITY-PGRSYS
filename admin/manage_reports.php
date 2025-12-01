<?php
require_once __DIR__ .'../../app/utils/log.php';
require_once __DIR__ .'../../app/api/data/dataProcess.php';

$data_source_url = "http://localhost/pgrs-g6/request/getData.php?data=report";

$reports = getDataSource($data_source_url);

$status = $_GET['status'] ?? 'All';
if ($status !== 'All' && is_array($reports)) {
    $reports = array_filter($reports, fn($r) => isset($r['status']) && $r['status'] === $status);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports | Padre Garcia Reporting</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <div class="card-custom p-4">
            <h3 class="text text-left mb-4">Manage Reports</h3>

            <!-- Filter buttons -->
            <div class="d-flex justify-content-center mb-3 gap-2 flex-wrap">
                <?php 
                $statuses = ['All','Pending','Approved','Ongoing','Resolved'];
                foreach ($statuses as $s): ?>
                    <a href="?status=<?= $s ?>" class="btn btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Ongoing'=>'info',
                        'Resolved'=>'primary',
                        default=>'dark'
                    } ?> <?= ($status==$s)?'active':'' ?>">
                        <?= $s ?>
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
                                <th>User</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['user_id']) ?></td>
                                <td><?= htmlspecialchars($r['firstName'] . ' ' . $r['lastName']) ?></td>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['report_type']) ?></td>
                                <td>
                                    <span class="badge bg-<?= match($r['status']){
                                        'Approved'=>'success',
                                        'Pending'=>'warning',
                                        'Ongoing'=>'info',
                                        'Resolved'=>'primary',
                                        default=>'secondary'
                                    } ?>"><?= $r['status'] ?></span>
                                </td>
                                <td><?= $r['date_created'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reportModal<?= htmlspecialchars($r['id'] ?? '') ?>">
                                        View Details
                                    </button>
                                    <?php if ($r['status']=='Pending'): ?>
                                        <button onclick="confirmAction('approve', <?= $r['id'] ?>)" class="btn btn-success btn-sm">Approve</button>
                                        <button onclick="confirmAction('reject', <?= $r['id'] ?>)" class="btn btn-danger btn-sm">Reject</button>
                                    <?php else: ?>
                                        <button onclick="confirmAction('delete', <?= $r['id'] ?>)" class="btn btn-outline-danger btn-sm">Delete</button>
                                    <?php endif; ?>
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

<!-- Report Detail Modals -->
<?php include __DIR__ . '/components/reportDetail.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="../admin/assets/teamAction.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>
