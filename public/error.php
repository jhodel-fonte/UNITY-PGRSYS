<?php
// Set the HTTP status code to 404
http_response_code(404);

$userLoggedIn = (isset($_SESSION['userLoginData']) && $_SESSION['isValid'] == true && $_SESSION['isOtpVerified'] == true) ? true : 'N/A';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background: url('./assets/pgsrsBG.jpg');
            color: #333;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404 - Page Not Found</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
        <p>It might have been moved, deleted, or you entered the wrong URL.</p>
        <?php if ($userLoggedIn): ?>
            <p><a href="../index.php">Go back to Dashboard</a></p>
        <?php else: ?>
            <p><a href="../index.php">Go back to Home</a></p>
        <?php endif; ?>
        <p>If you think this is an error, please contact support.</p>
    </div>
</body>
</html>
