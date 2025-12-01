<?php
session_start();
$otpSecret = $_SESSION['secretOtp'] ."Remove This One in deployment";
require_once __DIR__ .'../../../app/database/profiling.php';
foreach ($_SESSION as $key => $value) {
  echo "Key: " . htmlspecialchars($key) . ", Value: " . htmlspecialchars(print_r($value, true)) . "<br><br>";
}

if (isset($_SESSION['pgcode'])) {
  $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['pgcode']);

}

// $set = ((isset($_GET['auth']) && $_GET['auth'] == 'register')) ? 'Verify Your Mobile Number' : 'Verify Your Identity';

// exit;
// var_dump($_SESSION['otp_sent_time']);
// $readable_time = date('Y-m-d H:i:s', $_SESSION['otp_sent_time'] ?? 0); 
// echo "OTP Time Sent (Local): " . $readable_time;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verification Code</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="assets/verification.css" />
</head>
<body>

  <div class="verify-container">

    <h3 class = "text text-dark">Verify Your Identity</h3>
    <?php
    echo "We Have Sent an OTP to " .htmlspecialchars($_SESSION['number']);
    ?>
    <p>Enter the 6-digit code sent to your phone number.</p>

    <form id="verifyForm" method="POST" action="../../app/controllers/otpVerification.php">
      <div class="otp-input">
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
      </div>
      <!-- <input type="number" name="otp"> -->
      <button type="submit">Verify</button>
    </form>

    <div class="resend">
      Didn't receive the code? <a href="../app/controllers/resendOtp.php?otp=1" id="resendBtn" class="resendButton" disabled>Resend Code</a>
      <p>Time remaining to wait: <span id="countdown"></span></p>
    </div>

    <?php
      if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['reg']) && $_GET['reg'] == '1') {
        echo "<p>Verify Your Identity</p>";
      } else {
          echo "<div class='Log-Out'>
                <a href='../../app/controllers/logout.php?logout=1'>Logout</a>
              </div>";
      }
    
    ?>
  
    
  </div>

  <script>console.log(<?= json_encode($otpSecret) ?>)</script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script> //time countdown script
    const expirationTimestamp = <?php echo $_SESSION['otp_sent_time'] ?? 0; ?>; //get time expiration
    
    document.addEventListener('DOMContentLoaded', () => {
        let timerInterval = null; 

        const countdownElement = document.getElementById('countdown');
        const expirationTimeMs = expirationTimestamp * 1000; 
        const resendButton = document.getElementById('resendBtn');

        //stop if no set or error
        if (expirationTimeMs <= 0) {
            countdownElement.textContent = "Cooldown not active.";
            return;
        }

        resendButton.setAttribute('disabled', 'disabled');

        function updateCountdown() {
            const nowMs = new Date().getTime(); 
            
            //time difference remaining
            const distanceMs = expirationTimeMs - nowMs; 

            if (distanceMs <= 0) {
                clearInterval(timerInterval); 
                countdownElement.textContent = "You can resend the OTP now.";
                resendButton.removeAttribute('disabled');
                return;
            }

            //milliseconds to seconds
            const seconds = Math.floor(distanceMs / 1000);
            
            // display tine
            countdownElement.textContent = `${seconds} seconds`;
        }

        updateCountdown();
        timerInterval = setInterval(updateCountdown, 1000);
    });

    //otp input
    (function () {
      const form = document.getElementById('verifyForm');
      const inputs = document.querySelectorAll('.otp-input input');
      const resendBtn = document.getElementById('resendBtn');

      function showMessage(text, isError = false) {
        let el = document.getElementById('otpMessage');
        if (!el) {
          el = document.createElement('div');
          el.id = 'otpMessage';
          el.style.marginTop = '1rem';
          form.parentNode.insertBefore(el, form.nextSibling);
        }
        el.textContent = text;
        el.style.color = isError ? 'red' : 'green';
      }

      // auto move focus and allow backspace
      inputs.forEach((input, idx) => {
        input.addEventListener('input', (e) => {
          const val = e.target.value;
          if (val.length > 1) {
            // keep only last char
            e.target.value = val.slice(-1);
          }
          if (e.target.value && idx < inputs.length - 1) {
            inputs[idx + 1].focus();
          }
        });

        input.addEventListener('keydown', (e) => {
          if (e.key === 'Backspace' && !e.target.value && idx > 0) {
            inputs[idx - 1].focus();
          }
        });
      });

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const otp = Array.from(inputs).map(i => i.value.trim()).join('');
        if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
          showMessage('Please enter a 6-digit code.', true);
          return;
        }

        showMessage('Verifying...');

         try {
          const body = new URLSearchParams();
          body.append('otp', otp);

          const res = await fetch('../../app/controllers/otpVerification.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: body.toString()
          });

          // Expect JSON response from the server
          const data = await res.json();

          if (res.ok && data.success) {
            showMessage(data.message || 'OTP verified successfully.');
              window.location.href = '../redirecting.php';

          } else {
            showMessage(data.message || 'OTP verification failed.', true);
          }

        } catch (err) {
          showMessage('Network error. Please try again.', true);
          console.error(err);
        }
      });

      // placeholder for resend action
      resendBtn.addEventListener('click', (e) => {
        if (resendBtn.hasAttribute('disabled')) {
          e.preventDefault();
          return;
        }
        e.preventDefault();
        showMessage('Resending OTP.');
        window.location.href = '../../app/controllers/resendOtp.php?resend=1';
        
      });
    })();

  </script>

</body>
</html>

  <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['res'])) {
      if ($_GET['res'] == '1') {
          echo "<script>Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: 'OTP Resend Successfully',
              });</script>";
      } else {
        echo "<p>Error Resending</p>
        ";
      } 
    }
  
  ?>