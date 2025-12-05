<?php

session_start();

require_once __DIR__ . '/../app/utils/log.php'; // Adjusted slashes
require_once __DIR__ . '/../app/api/data/dataProcess.php'; // Adjusted slashes

$adminCurrentUser = (isset($_SESSION['userLoginData']) && $_SESSION['userLoginData']['data']['role'] == 'Admin') ? $_SESSION['userLoginData']['data']['pgCode'] : null;

$data_source_url = "http://localhost/UNTY-PGRSYS/app/api/data/getData.php?data=members";//get data
$users_data = getDataSource($data_source_url);

// Define valid statuses
$valid_statuses = ['All', 'Pending', 'Active', 'Rejected'];
$status_from_url = $_GET['status'] ?? 'All';

$current_status = in_array($status_from_url, $valid_statuses) ? $status_from_url : 'All';

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

$users_filtered_php = [];
if (isset($users_data) && is_array($users_data) && !isset($users_data['success'])) {
    foreach ($users_data as $u) {
        $uStatus = ($u['status'] === 'NoOtpReg') ? 'Pending' : $u['status'];
        if ($current_status === 'All' || $uStatus === $current_status) {
            $users_filtered_php[] = $u;
        }
    }
}
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

            <div class="row mb-3 align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <input type="text" id="userSearch" class="form w-100" placeholder="Search users by PG-ID, name, email, or mobile number...">
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="input-group d-inline-flex w-auto">
                        <label class="input-group-text" for="sortSelect"><i class="fas fa-sort me-2"></i> Sort By</label>
                        <select class="form-select" id="sortSelect" onchange="handleSortAndFilter()">
                            <option value="newest">Newest First (Default)</option>
                            <option value="oldest">Oldest First</option>
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php if (isset($users_data['success']) && $users_data['success'] == false) : ?>
                <p class="text-center text-danger"><?= htmlspecialchars($users_data['message']) ?></p>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody"> 
                        <?php if(empty($users_filtered_php)): ?>
                            <tr><td colspan="7">No users found in the **<?= $current_status ?>** status.</td></tr>
                        <?php else: 
                            // Initial rendering is done in PHP (already filtered by status)
                            foreach($users_filtered_php as $u): 
                                $uStatus = ($u['status'] === 'NoOtpReg') ? 'Pending' : $u['status'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($u['userId']) ?></td>
                                <td><?= htmlspecialchars($u['firstName'] . ' ' . $u['lastName']); ?></td>
                                <td><?= (isset($u['mobileNum'])) ? htmlspecialchars($u['mobileNum']) : 'N/A' ; ?></td>
                                <td><?= (isset($u['email'])) ? htmlspecialchars($u['email']) : 'N/A' ; ?></td>
                                
                                <td>
                                    <span class="badge rounded-pill bg-<?= 
                                        $uStatus === 'Active' ? 'success' : (
                                        $uStatus === 'Rejected' ? 'danger' : 'warning text-dark'
                                        ) 
                                    ?>">
                                        <?= htmlspecialchars($uStatus); ?>
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
                                    <?php if($uStatus == 'Pending'): ?>
                                        <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="<?= $u['userId'] ?>">Approve</button>
                                        <button class="btn btn-sm btn-danger action-btn" data-action="reject" data-userid="<?= $u['userId'] ?>">Reject</button>
                                    <?php elseif($uStatus == 'Rejected'): ?>
                                        <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="<?= $u['userId'] ?>">Delete</button>
                                    <?php elseif($uStatus == 'Active'): ?>
                                        <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="<?= $u['userId'] ?>">Delete</button>
                                    <?php elseif($u['userId'] == $adminCurrentUser): ?>
                                        <span>Current Account</span>
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
$modal_users = (is_array($users_data) && !isset($users_data['success'])) ? $users_data : [];

foreach($modal_users as $u): 
    if(file_exists('components/userDetails.php')) {
        include 'components/userDetails.php';
    }
endforeach; 
?>

<script>
    <?php 
    $js_users_data = [];
    if (is_array($users_data) && !isset($users_data['success'])) {
        $js_users_data = array_values($users_data);
    }
    ?>

    const allUsers = <?= json_encode($js_users_data); ?>;
    const initialFilterStatus = '<?= $current_status; ?>'; 
</script>


<script>

function createUserRowHtml(u, adminCurrentUser) {
    const uStatus = (u.status === 'NoOtpReg') ? 'Pending' : u.status;
    
    let badgeClass = 'warning text-dark';
    if (uStatus === 'Active') {
        badgeClass = 'success';
    } else if (uStatus === 'Rejected') {
        badgeClass = 'danger';
    }

    // Action Buttons Logic
    let actionButtons = '';
    
    // 1. Check for current Admin account (no actions)
    if (u.userId == adminCurrentUser) {
         actionButtons = '<span>Current Account</span>';
    } 
    // 2. Pending accounts show Approve and Reject
    else if (uStatus === 'Pending') {
        actionButtons = `
            <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="${u.userId}">Approve</button>
            <button class="btn btn-sm btn-danger action-btn" data-action="reject" data-userid="${u.userId}">Reject</button>
        `;
    } 
    // 3. Rejected accounts show ONLY Delete (explicitly addressing the request)
    else if (uStatus === 'Rejected') {
        actionButtons = `
            <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="${u.userId}">Delete</button>
        `;
    } 
    // 4. Active accounts show ONLY Delete
    else if (uStatus === 'Active') {
        actionButtons = `
            <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="${u.userId}">Delete</button>
        `;
    } 
    // 5. Default/Other
    else {
         actionButtons = '<span>No Action</span>';
    }


    // Build the full row HTML
    return `
        <tr>
            <td>${u.userId}</td>
            <td>${u.firstName} ${u.lastName}</td>
            <td>${u.mobileNum || 'N/A'}</td>
            <td>${u.email || 'N/A'}</td>
            <td>
                <span class="badge rounded-pill bg-${badgeClass}">${uStatus}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-info"
                    data-bs-toggle="modal"
                    data-bs-target="#userModal${u.userId}">
                    View
                </button>
            </td>
            <td>${actionButtons}</td>
        </tr>
    `;
}

// 2. Sorting Function (remains the same)
function sortUsers(users, sortBy) {
    const sortedUsers = [...users]; // Clone the array

    sortedUsers.sort((a, b) => {
        let comparison = 0;

        if (sortBy === 'newest' || sortBy === 'oldest') {
            const dateA = new Date(a.dateCreated).getTime();
            const dateB = new Date(b.dateCreated).getTime();
            comparison = dateA - dateB;

            if (sortBy === 'newest') {
                comparison *= -1;
            }
        } else if (sortBy === 'name_asc' || sortBy === 'name_desc') {
            const nameA = `${a.lastName || ''} ${a.firstName || ''}`.toLowerCase();
            const nameB = `${b.lastName || ''} ${b.firstName || ''}`.toLowerCase();

            if (nameA < nameB) comparison = -1;
            else if (nameA > nameB) comparison = 1;

            if (sortBy === 'name_desc') {
                comparison *= -1;
            }
        }
        return comparison;
    });

    return sortedUsers;
}

// 3. Filtering and Rendering Function (main orchestrator) (remains the same)
function handleSortAndFilter() {
    const tableBody = document.getElementById('userTableBody');
    const searchInput = document.getElementById('userSearch');
    const sortSelect = document.getElementById('sortSelect');
    
    if (!tableBody || !searchInput || !sortSelect || typeof allUsers === 'undefined') {
        return;
    }

    const searchValue = searchInput.value.toLowerCase().trim();
    const sortBy = sortSelect.value;
    const currentURLStatus = typeof initialFilterStatus !== 'undefined' ? initialFilterStatus : 'All';

    // 1. Apply Filtering (Search and Status)
    let filteredUsers = allUsers.filter(u => {
        const uStatus = (u.status === 'NoOtpReg') ? 'Pending' : u.status;
        
        if (currentURLStatus !== 'All' && uStatus !== currentURLStatus) {
            return false;
        }

        if (searchValue) {
            const searchTerms = `${u.userId} ${u.firstName} ${u.lastName} ${u.email} ${u.mobileNum}`.toLowerCase();
            return searchTerms.includes(searchValue);
        }

        return true;
    });

    // 2. Apply Sorting
    const finalUsers = sortUsers(filteredUsers, sortBy);

    // 3. Render Table
    let htmlContent = '';
    if (finalUsers.length === 0) {
        htmlContent = `<tr><td colspan="7">No users found matching the current criteria.</td></tr>`;
    } else {
        const adminUser = <?= json_encode($adminCurrentUser ?? null); ?>;
        finalUsers.forEach(u => {
            htmlContent += createUserRowHtml(u, adminUser);
        });
    }
    
    tableBody.innerHTML = htmlContent;
}

// --- INITIALIZATION ---

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const sortSelect = document.getElementById('sortSelect');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', handleSortAndFilter);
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', handleSortAndFilter);
        
        sortSelect.value = 'newest';
    }
});
</script>

<script src="assets/manageUser.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="../admin/assets/admin.js"></script> -->