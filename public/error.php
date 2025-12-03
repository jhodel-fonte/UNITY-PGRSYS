<?php

// Default message for pending verification
$title = 'Error403';
$message = 'Forbidden';

if (isset($_GET['notVerified']) && $_GET['notVerified'] == '1') {
    $title = 'Account Approval';
    $message = 'Your registration is complete! We are now reviewing your details. Please wait for an administrator to activate your account.';
}

// FIX: Corrected PHP syntax error by removing the leading dot operator.
// ASSUMPTION: The user should be redirected to the login page after registration/verification pending status.
$login_page = '../app/controllers/logout.php?logout=1'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Pending</title>

    <style>
        /* 1. Page Background (Modal Backdrop) */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            
            background-image: url(assets/pgsrsBG.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6); /* Slightly darker dimming */
            -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px); /* Increased blur for better focus */
            z-index: 1;
        }

        /* 2. Modal Style */
        .modal-card {
            z-index: 10; /* Above the overlay */
            background: #ffffff;
            color: #333;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(1);
            transition: transform 0.3s ease-out;
            animation: bounceIn 0.6s ease-out;
        }

        .modal-card h2 {
            font-size: 1.8rem;
            color: #1a73e8; /* Blue accent color */
            margin-bottom: 15px;
            font-weight: 600;
        }

        .modal-card p {
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        /* 3. Button Style */
        .btn-main {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-main:hover {
            background-color: #0c60d3;
            transform: translateY(-2px);
        }

        /* Animation */
        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="modal-card">
        <h2><?= htmlspecialchars($title) ?></h2>
        <i class="fas fa-hourglass-half" style="font-size: 3rem; color: #f4b400; margin-bottom: 20px;"></i>
        
        <p>
            <?= htmlspecialchars($message); ?>
        </p>

        <a href="<?= htmlspecialchars($login_page); ?>" class="btn-main">
            Go to Login Page
        </a>
    </div>
    
    <!-- Using Font Awesome for the icon, ensure it's loaded -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</body>
</html>