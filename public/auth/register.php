<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Padre Garcia Reporting System</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/style.css">
</head>
<body>

  <div class="login-bg">
    <div class="overlay"></div>

    <div class="container d-flex justify-content-center align-items-start min-vh-100 py-5">
      <div class="register-card p-5 text-dark shadow-lg">
        <a href="../../index.php" class="close-btn-fix" title="Back to Home">
            &times; 
        </a>
        <div class="text-center">
          <a href="../../index.php">
              <img src="assets/img/logo.png" alt="UNITY PGSRS Logo" class="mb-2" style="width: 80px;">
          </a>
        </div>
        <h2 class="text mb-4 text-center">Create Your Account</h2>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <form id="registerForm" enctype="multipart/form-data" action="../../app/controllers/register.php?register=1" method="POST">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">First Name</label>
              <input type="text" name="firstname" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" name="lastname" class="form-control form-control-lg" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="tel" name="mobile" class="form-control form-control-lg" placeholder="09XXXXXXXXX" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control form-control-lg" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-select form-select-lg" required>
                <option value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Date of Birth</label>
              <input type="date" name="dob" class="form-control form-control-lg" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Street</label>
            <input type="text" name="street" class="form-control form-control-lg" required>
          </div>

          <div class="mb-3" href='../../app/api/data/listAddress.json'>
            <label class="form-label">Barangay</label>
              <select name="barangay" id="barangay" class="form-select form-select-lg" required>
                 
              </select>
          </div>
          <script src="./assets/js/addressInput.js"></script>

          <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" placeholder="Padre Garcia" value="Padre Garcia" class="form-control form-control-lg" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Province</label>
            <input type="text" name="province" placeholder="Batangas" value="Batangas" class="form-control form-control-lg" readonly>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" placeholder="Philippines" value="Philippines" class="form-control form-control-lg" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control form-control-lg" required>
          </div>

          <input type="text" name="role" value="3" hidden>
          <input type="text" name="status" value="5" hidden>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Password</label>
              <input type="password" id="password" name="password" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-lg" required>
            </div>
          </div>

            <div class="mb-3 form-check text-start">
                <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="termsCheck" 
                    name="terms" 
                    required>
                <label class="form-check-label" for="termsCheck">
                    I agree to the <a href="#" class="link text-decoration-none">terms and conditions</a>
                </label>
            </div>

          <button type="submit" class="btn w-100 py-2" id="submitBtn">Register</button>

          <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php" class="link text-decoration-none">Login here</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
  <button onclick="getLocation()">Try It</button>
  <script src="../../app/utils/getGeoLocation.js"></script>
  <script src="assets/js/main.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    (function(){
      const form = document.getElementById('registerForm');
      const submitBtn = document.getElementById('submitBtn');

      function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = String(str);
        return div.innerHTML;
      }

      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        console.log("--- FormData Content (before sending) ---");
        for (let pair of formData.entries()) {
          console.log(pair[0] + ': ' + pair[1]);  
        }
        console.log("-----------------------------------------");

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Registering...';

        try {
          Swal.fire({
            title: 'Register...',
            text: 'Please wait while we creating your account.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          const response = await fetch('../../app/controllers/register.php?register=1', {
            method: 'POST',
            body: formData
          });

          Swal.close();

          const text = await response.text();
          let data;
          try {
            data = JSON.parse(text);
            console.log('Server Response (parsed):', data);
          } catch (err) {
            console.warn('Server Response is not valid JSON. Raw response:', text);
            data = { status: 'error', message: text || 'Empty response from server' };
          }

          // Enable submit button
          submitBtn.disabled = false;
          submitBtn.textContent = 'Register';

          const message = (typeof data.message === 'string') ? data.message.trim() : JSON.stringify(data.message);
          console.log('Server Response - status:', data.status, 'message:', message);

          if (data.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              html: escapeHtml(message),
              timer: 1800,
              showConfirmButton: false
            });
            const id = data.PGID;
            setTimeout(() => { window.location.href = `registerRedirect.php?reg=success&otp=false&id=${id}`; }, 1500);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: escapeHtml(message),
              confirmButtonText: 'OK'
            });
          }
        } catch (error) {
          console.error('Fetch/network error:', error);
          submitBtn.disabled = false;
          submitBtn.textContent = 'Register';
          Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: String(error),
            confirmButtonText: 'OK'
          });
        }
      });
    })();
  </script>
  
</body>
</html>
