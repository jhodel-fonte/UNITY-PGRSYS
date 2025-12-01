<?php

require_once __DIR__ .'../../app/utils/log.php';
require_once __DIR__ .'../../app/api/data/dataProcess.php';

$data_source_url = "http://localhost/pgrs-g6/request/getData.php?data=members";

$users = getDataSource($data_source_url);
// var_dump($users);
// exit;

$status = $_GET['status'] ?? 'All';
if ($status !== 'All') {
    $users = array_filter($users, fn($r) => $r['status'] === $status);
}

// ----------------------------
// FLASH MESSAGE SIMULATION
// ----------------------------
session_start();
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Padre Garcia Reporting</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css">
</head>

<body>

<?php include '../admin/admin_sidebar.php'; ?>



<div class="main-content">
    <div class="container mt-4">

        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h3 class="text">Manage User Requests</h3>
            </div>

            <div class="d-flex justify-content-center mb-3 gap-2 flex-wrap">
                <?php 
                $statuses = ['All','Pending','Approved','Rejected'];
                foreach ($statuses as $s): ?>
                    <a href="?status=<?= $s ?>" class="btn btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Rejected'=>'danger',
                        default=>'dark'
                    } ?> <?= ($status==$s)?'active':'' ?>">
                        <?= $s ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- FLASH MESSAGE -->
            <?php if($message): ?>
                <div class="alert alert-<?= $message['type']; ?> alert-dismissible fade show" role="alert">
                    <?= $message['text']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

             <!-- Search box -->
            <div class="mb-3 d-flex justify-content-center">
                <input type="text" id="userSearch" class="form w-50" placeholder="Search reports by user name, email, or mobile number...">
            </div>

        <?php if (isset($users['success']) && $users['success'] == false) : ?>
        <p><?= $users['message'] ?></p>
        <?php else : ?>
            
            <div class="table-responsive scroll-card">
            <table class="table table-white table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($users)): ?>
                    <tr><td colspan="7">No users found.</td></tr>
                <?php else: foreach($users as $index => $u): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($u['firstName'] . ' ' . $u['lastName']); ?></td>
                        <td><?= htmlspecialchars($u['mobileNum']); ?></td>
                        <td><?= htmlspecialchars($u['email']); ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $u['status'] === 'Active' ? 'success' : ($u['status'] === 'Rejected' ? 'danger' : 'warning text-dark') ?>"> <?= (isset($u['status'])) ? htmlspecialchars($u['status']) : 'N/A' ?>
                            </span>
                        </td>

                        <!-- VIEW BUTTON -->
                        <td>
                            <button class="btn btn-sm btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#userModal<?= $u['userId']; ?>">
                                View
                            </button>
                        </td>

                        <td>
                            <?php if($u['status'] === 'Pending'): ?>
                                <button class="btn btn-sm btn-success">Approve</button>
                                <button class="btn btn-sm btn-danger">Rejected</button>
                            <?php elseif($u['status'] === 'Approved'): ?>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            <?php elseif($u['status'] === 'Rejected'): ?>
                                <button class="btn btn-sm btn-success">Approve</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            <?php else: ?>
                                <span>No Action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- USER MODALS -->
<?php foreach($users as $u): ?>
<div class="modal fade" id="userModal<?= $u['userId']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">

            <div class="modal-header">
                <h5 class="modal-title">User Details - <?= htmlspecialchars($u['firstName'] . ' ' . $u['lastName']); ?></h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <?php if ($u['profileImage']): ?>
                            <img src="../uploads/<?= (isset($u['profileImage']) || $u['profileImage'] == Null) ? htmlspecialchars($u['profileImage']) : 'default.jpg'; ?>"
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 200px;">
                        <?php else: ?>
                            <p>No Profile Image</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-8">
                        <p><strong>Name:</strong> <?= htmlspecialchars($u['firstName']).' '.htmlspecialchars($u['lastName']); ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($u['email']); ?></p>
                        <p><strong>Mobile:</strong> <?= htmlspecialchars($u['mobileNum']); ?></p>
                        <p><strong>Gender:</strong> <?= htmlspecialchars($u['gender']); ?></p>
                        <p><strong>Address:</strong> <?= (isset($u['address']) || $u['address'] != Null) ? htmlspecialchars($u['address']) : 'N/A'; ?></p>
                        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($u['dateOfBirth']); ?></p>
                    </div>
                </div>

                <hr>
                
                <h5>ID Documents</h5>
                <?php 
                    $userImages = $u['images'] ?? [];
                    if (!empty($userImages) || $userImages != NULL): ?>
                    <div class="row mb-3">
                        <?php foreach ($userImages as $img):
                            $imagePath = $img['image_path'] ?? $img['location'] ?? $img['photo'] ?? $img['path'] ?? $img['filename'] ?? null;
                            if (!$imagePath) {
                                continue;
                            }
                            
                            if (strpos($imagePath, 'uploads/') !== false || strpos($imagePath, '/') === 0) {
                                $fullImagePath = $imagePath;
                            } else {
                                $fullImagePath = '../uploads/' . $imagePath;
                            }
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <img src="<?= htmlspecialchars($fullImagePath) ?>"
                                         class="img-fluid rounded shadow"
                                         style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                         alt="ID Document"
                                         onclick="window.open(this.src, '_blank')"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <p class="text-muted text-center" style="display:none;">Image not available</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center mb-3">
                        <p class="text-muted">No ID Documents Uploaded</p>
                    </div>
                <?php endif; ?>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>
