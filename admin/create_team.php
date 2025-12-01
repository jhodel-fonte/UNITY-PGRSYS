<?php
// ito yung storage nya
$responseTeams = [];

// alert messages lang
$message = "";
$alertClass = "";

// ito yung mag hahandle ng form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $mobile = trim($_POST['mobile_number']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($firstname && $lastname && $username && $mobile && $email && $password) {

        // mag c-create ng record para mapunta sa array
        $newTeam = [
            "firstname" => $firstname,
            "lastname" => $lastname,
            "username" => $username,
            "mobile" => $mobile,
            "email" => $email,
            "password" => $password
        ];

        $responseTeams[] = $newTeam;

        $message = "Response team account created successfully!";
        $alertClass = "alert-success";

    } else {
        $message = "All fields are required.";
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
        <div class="card-customs p-4 shadow-lg border border-secondary rounded-4 bg-dark text-light">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text mb-0 text-light">Create Response Team Account</h3>
                <a href="manage_response_team.php" class="btn btn-primary">⬅ Back</a>
            </div>

            <?php if ($message): ?>
                <div class="alert <?= $alertClass; ?>"><?= $message; ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3 py-2">
                    Create Response Team Account
                </button>
            </form>
        </div>

        <!-- display lang sa baba wag mo na to pansinin AHHAHAHA -->
        <?php if (!empty($responseTeams)): ?>
        <div class="mt-4 card-custom p-3">
            <h4 class="text">Mock Stored Accounts</h4>
            <pre class="text-light"><?php print_r($responseTeams); ?></pre>
        </div>
        <?php endif; ?>

     </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>
