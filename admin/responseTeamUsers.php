<?php

session_start();

require_once __DIR__ . '/../app/utils/log.php'; // Adjusted slashes
require_once __DIR__ . '/../app/api/data/dataProcess.php'; // Adjusted slashes

$adminCurrentUser = (isset($_SESSION['userLoginData']) && $_SESSION['userLoginData']['data']['role'] == 'Admin') ? $_SESSION['userLoginData']['data']['pgCode'] : null;
//remove current signed user to the array

$data_source_url = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=ResponseTeamUsers";
$users = getDataSource($data_source_url);

// Define valid statuses
$valid_statuses = ['All', 'Pending', 'Active', 'Rejected'];
$status_from_url = $_GET['status'] ?? 'All';

$current_status = in_array($status_from_url, $valid_statuses) ? $status_from_url : 'All';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                <?php foreach ($valid_statuses as $s): ?>
                    <a href="?status=<?= $s ?>" 
                       class="btn btn-outline-<?= match($s){
                           'Pending' => 'warning',
                           'Active' => 'success',
                           'Rejected' => 'danger',
                           default => 'dark'
                       } ?> <?= ($s === $current_status) ? 'active' : '' ?>">
                        <?= $s ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if($message): ?>
                <div class="alert alert-<?= $message['type']; ?> alert-dismissible fade show" role="alert">
                    <?= $message['text']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="mb-3 d-flex justify-content-center">
                <input type="text" id="userSearch" class="form w-50" placeholder="Search users by PG-ID, name, email, or mobile number...">
            </div>

            <?php if (isset($users['success']) && $users['success'] == false) : ?>
                <p class="text-center text-danger"><?= htmlspecialchars($users['message']) ?></p>
            <?php else : ?>
                
                <div class="table-responsive scroll-card">
                    <table class="table table-white table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>PG-ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody"> 
                        <?php if(empty($users) || !is_array($users)): ?>
                            <tr><td colspan="7">No users found.</td></tr>
                        <?php else: 
                            // Initial Filter Logic in PHP (to match JS logic)
                            foreach($users as $u): 
                                // Normalize Status
                                $uStatus = ($u['status'] === 'NoOtpReg') ? 'Pending' : $u['status'];
                                
                                // Filter based on current selection
                                if ($current_status !== 'All' && $uStatus !== $current_status) {
                                    continue; 
                                }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($u['userId']) ?></td>
                                <td><?= htmlspecialchars($u['firstName'] . ' ' . $u['lastName']); ?></td>
                                <td><?= (isset($u['mobileNum'])) ? htmlspecialchars($u['mobileNum']) : 'N/A' ; ?></td>
                                <td><?= (isset($u['email'])) ? htmlspecialchars($u['email']) : 'N/A' ; ?></td>
                                
                                <td>
                                    <span class="badge rounded-pill bg-<?= 
                                        $u['status'] === 'Active' ? 'success' : (
                                        $u['status'] === 'Rejected' ? 'danger' : 'warning text-dark'
                                        ) 
                                    ?>">
                                        <?= htmlspecialchars(
                                            ($u['status'] === 'NoOtpReg') ? 'Pending' : (
                                            isset($u['status']) ? $u['status'] : 'N/A'
                                            )
                                        ); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userModal<?= $u['userId']; ?>">
                                        View
                                    </button>
                                </td>

                                <td>
                                    <?php if($u['status'] === 'Pending' || $u['status'] === 'NoOtpReg'): ?>
                                        <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="<?= $u['userId'] ?>">Approve</button>
                                        <button class="btn btn-sm btn-danger action-btn" data-action="reject" data-userid="<?= $u['userId'] ?>">Reject</button>
                                    <?php elseif($u['userId'] == $adminCurrentUser): ?>
                                        <span>Current Account</span>
                                    <?php elseif($u['status'] === 'Approved' || $u['status'] === 'Active'): ?>
                                        <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="<?= $u['userId'] ?>">Delete</button>
                                    <?php elseif($u['status'] === 'Rejected'): ?>

                                        <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="<?= $u['userId'] ?>">Delete</button>
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

<?php 
$modal_users = (is_array($users) && !isset($users['success'])) ? $users : [];

foreach($modal_users as $u): 
    // IMPORTANT: Use 'include' (not include_once) because we need this multiple times
    // Ensure the path 'components/userDetails.php' is correct relative to this file
    if(file_exists('components/userDetails.php')) {
        include 'components/userDetails.php';
    }
endforeach; 
?>

<script>
    <?php 
    $js_users_data = [];
    if (is_array($users) && !isset($users['success'])) {
        $js_users_data = array_values($users);
    }
    ?>
    // Pass PHP data to JS
    const allUsers = <?= json_encode($js_users_data); ?>;
    const initialFilterStatus = '<?= $current_status; ?>'; 
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>