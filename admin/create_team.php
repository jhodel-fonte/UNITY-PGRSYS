<?php
// ito yung storage nya
$responseTeams = [];

// alert messages lang
$message = "";
$alertClass = "";

// ito yung mag hahandle ng form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- UPDATED: Capturing Team Fields instead of User Fields ---
    $teamName = trim($_POST['name']);
    $contact = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    // Assuming only Team Name and Contact are strictly required for now
    if ($teamName && $contact) {

        // mag c-create ng record para mapunta sa array
        $newTeam = [
            "name" => $teamName,
            "contact_number" => $contact,
            "email" => $email,
            "address" => $address,
            "latitude" => $latitude,
            "longitude" => $longitude
            // Note: I removed 'username', 'firstname', 'lastname', and 'password' as they are user attributes
        ];

        $responseTeams[] = $newTeam;

        $message = "Response team created successfully!";
        $alertClass = "alert-success";

    } else {
        // Updated required fields list
        $message = "Team Name and Contact Number are required.";
        $alertClass = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Response Team | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css" >

</head>

<body>


    <?php include '../admin/admin_sidebar.php'; ?>

    <div class="main-content">
      <div class="container mt-4">
        <!-- THEME CHANGE: Replaced bg-dark/text-light with bg-white/text-dark, and primary border for light theme -->
        <div class="card p-4 shadow-lg border border-primary rounded-4 bg-white text-dark">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <!-- Changed text-light to text-primary -->
                <h3 class="text mb-0 text-primary">Create Response Team Account</h3>
                <a href="manage_response_team.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <?php if ($message): ?>
                <div class="alert <?= $alertClass; ?>"><?= $message; ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Team Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Base Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>

                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Latitude</label>
                        <input type="text" name="latitude" class="form-control">
                    </div>
                 
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Longitude</label>
                        <input type="text" name="longitude" class="form-control">
                    </div>
                </div>
                
                
                <button type="submit" class="btn btn-primary w-100 mt-3 py-2">
                    <i class="fas fa-users-cog me-1"></i> Create Response Team
                </button>
            </form>
        </div>

        <!-- display lang sa baba wag mo na to pansinin AHHAHAHA -->
        <?php if (!empty($responseTeams)): ?>
        <!-- THEME CHANGE: Used standard light card for the mock display -->
        <div class="mt-4 card p-3 shadow-sm border border-secondary rounded-4">
            <h4 class="text-primary">Mock Stored Teams</h4>
            <pre class="bg-light p-3 border rounded text-dark"><?php print_r($responseTeams); ?></pre>
        </div>
        <?php endif; ?>

      </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>