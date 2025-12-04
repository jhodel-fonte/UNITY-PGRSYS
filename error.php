<?php

$errorCode = $_GET['code'] ?? '404';
$errorTitle = '';
$errorMessage = '';
$redirectLink = 'app/controllers/logout.php'; 

switch ($errorCode) {
    case '404':
        $errorTitle = 'Page Not Found';
        $errorMessage = 'Sorry, the page you are looking for does not exist or has been moved.';
        break;
    case '403':
        $errorTitle = 'Access Forbidden';
        $errorMessage = 'You do not have permission to view this resource.';
        break;
    case '500':
        $errorTitle = 'Internal Server Error';
        $errorMessage = 'Something went wrong on our server. Please try again later.';
        break;
    default:
        $errorTitle = 'An Unexpected Error Occurred';
        $errorMessage = 'We ran into a problem. Please use the button below to return to safety.';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $errorCode; ?> | Padre Garcia Reporting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #4e89ff;
            --dark-bg: #1a1a1a;
            --card-dark: #202225;
            --text-light: #f5f6fa;
        }
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .error-container {
            max-width: 500px;
            padding: 30px;
            background: var(--card-dark);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.5s ease-out;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            color: var(--primary-blue);
            line-height: 1;
            margin-bottom: 10px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .error-message {
            font-size: 1.1rem;
            color: #ccc;
            margin-bottom: 30px;
        }
        .btn-home {
            background: var(--primary-blue) !important;
            border: none;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-home:hover {
            background: #366dec !important;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Mobile adjustment */
        @media (max-width: 576px) {
            .error-code {
                font-size: 6rem;
            }
            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="error-code"><?= $errorCode; ?></div>
        <div class="error-title"><?= $errorTitle; ?></div>
        <p class="error-message">
            <?= $errorMessage; ?>
        </p>
        <a href="<?= $redirectLink; ?>" class="btn btn-primary btn-home">
            <i class="fas fa-arrow-left me-2"></i> Go to Main Page
        </a>
    </div>

</body>
</html>