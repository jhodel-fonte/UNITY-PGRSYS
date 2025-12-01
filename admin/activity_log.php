<?php
session_start();

// Temporary activity log array
$logs = [
    [
        "id" => 1,
        "user" => "Admin User",
        "action" => "Login",
        "module" => "Authentication",
        "description" => "User logged into the system.",
        "ip" => "192.168.1.10",
        "date" => "2025-11-20 09:15:22"
    ],
    [
        "id" => 2,
        "user" => "Jay Mark Rocero",
        "action" => "Added Report",
        "module" => "Service Reports",
        "description" => "Submitted a new service report under 'Road Maintenance'.",
        "ip" => "192.168.1.15",
        "date" => "2025-11-20 10:02:41"
    ],
    [
        "id" => 3,
        "user" => "Admin User",
        "action" => "Updated User",
        "module" => "User Management",
        "description" => "Edited user details for 'Maria Santos'.",
        "ip" => "192.168.1.20",
        "date" => "2025-11-20 11:00:12"
    ],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Log</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../admin/assets/admin.css">
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <div class="container mt-4">
        <div class="card-custom p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Activity Log</h2>
        </div>

            <div class="table-responsive scroll-card">
            <table class="table table-white table-hover text-center align-middle">
                        <thead>
                           <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $i => $log): ?>
                            <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $log['user'] ?></td>
                            <td><span class="badge bg-primary"><?= $log['action'] ?></span></td>
                            <td><?= $log['module'] ?></td>
                            <td><?= $log['description'] ?></td>
                            <td><?= $log['ip'] ?></td>
                            <td><?= $log['date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
              </div>
           </div>
       </div>
    </div>
</div>
<script src="../admin/assets/admin.js"></script>
</body>
</html>
