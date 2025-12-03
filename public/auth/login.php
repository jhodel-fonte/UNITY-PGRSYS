<?php
session_start();

include_once __DIR__ .'../../../app/utils/common.php';
include_once __DIR__ .'../../../app/database/profiling.php';
//update data id have

if (isset($_SESSION['pgcode'])) {
  $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['pgcode']);

}

// var_dump($_SESSION['userLoginData']);
// Redirect authenticated users
$_SESSION['isOtpVerified'] = false;

if (isset($_SESSION['userLoginData'])  ) {
  $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['userLoginData']['data']['pgCode']);
  switch ($_SESSION['userLoginData']['data']['role']) {
    case 'Admin':
        header("Location: ../../admin/dashboard.php");
        break;
    case 'User':
        header("Location: ../users/dashboard.php");
        break;
    case 'ResponseTeam':
        header("Location: ../../public/response_team/index.php");
        break;
    default:
        header("Location: ../../error.php");
        break;
    }
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Padre Garcia Reporting System - Login</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <div class="login-bg">
    <div class="overlay"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">



      <div class="login-card p-5 text-center shadow-lg">
        <a href="../../index.php" class="close-btn-fix" title="Back to Home">
              &times; 
        </a>

        <a href="../../index.php"><img src="assets/img/logo.png" alt="UNITY PGSRS Logo" class="mb-2" style="width: 80px;"></a>
        <h3 class="fw-bold text">Padre Garcia Service Report System</h3>

        <form id="loginForm" method="POST">  <!-- action="../../app/controllers/identityVerify.php" Added id and method -->

          <!-- Username -->
          <div class="mb-3 text-start">
            <label class="form-label text-dark">Username</label>
            <input type="text" name="username" class="form-control form-control-lg" required>
          </div>

          <!-- Password -->
          <div class="mb-4 text-start">
            <label class="form-label text-dark">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" required>
          </div>

          <!-- Login Button -->
          <button type="submit" class="btn w-100 py-2">Login</button>

          <!-- Links -->
          <div class="mt-3">
            <a href="forgot_pass.php" class="link text-decoration-none me-3">Forgot Password?</a>
            <a href="register.php" class="link text-decoration-none">Create Account</a>
          </div>

        </form>

        <!-- Added error message div -->
        <div id="error-message" class="mt-3 text-danger"></div>
      </div>

    </div>
  </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('loginForm');
            const errorMessageDiv = document.getElementById('error-message');

            if (!loginForm) {
                console.error('Login form not found!');
                return;
            }

            loginForm.addEventListener('submit', async (event) => {
                event.preventDefault(); // Prevent default GET submission

                try {
                    Swal.fire({
                        title: 'Logging In...',
                        text: 'Please wait while we verify your credentials.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch('../../app/controllers/identityVerify.php', {
                        method: 'POST',
                        body: new FormData(loginForm)
                    });

                    Swal.close();

                    if (!response.ok) {
                        throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
                    }

                    const data = await response.json();

                    if (data.response == 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login',
                            text: `Login Error: ${data.message}`
                        });
                    } else if (data.response === 'success') {
                        window.location.href = "../redirecting.php?login=1";
                    } else {
                        throw new Error('Unexpected server response');
                    }

                } catch (error) {
                    console.error('Fetch error occurred');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'A network or server error occurred. Please try again.',
                    });
                }
            });
        });
    </script>
    
</body>
</html>
