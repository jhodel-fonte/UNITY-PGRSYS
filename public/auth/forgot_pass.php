<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - PGSRS</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/style.css">
</head>
<body>

  <div class="forgot-bg">
    <div class="overlay"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

      <div class="forgot-card p-5 text-center shadow-lg">
        <a href="index.php">
            <img src="assets/img/logo.png" alt="UNITY PGSRS Logo" class="mb-2" style="width: 80px;">
        </a>

        <h3 class="fw-bold title mb-3">Forgot Password</h3>
        <p class="text-light small mb-4">Enter your registered mobile number to receive a password reset code.</p>

        <form action="forgot_process.php" method="POST">

          <!-- Mobile Number -->
          <div class="mb-4 text-start">
            <label class="form-label text-ligh">Mobile Number</label>
            <input 
              type="text" 
              name="mobile_number" 
              class="form-control form-control-lg" 
              placeholder="09XXXXXXXXX" 
              required>
          </div>

          <button type="submit" class="btn w-100 py-2">Send Reset Code</button>

          <div class="mt-3">
            <a href="login.php" class="link text-decoration-none">Back to Login</a>
          </div>

        </form>
      </div>

    </div>

  </div>

</body>
</html>