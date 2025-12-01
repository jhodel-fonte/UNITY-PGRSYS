  // Password match validation
    document.getElementById("registerForm").addEventListener("submit", function(e) {
      const pass = document.getElementById("password").value;
      const confirm = document.getElementById("confirm_password").value;
      if (pass !== confirm) {
        e.preventDefault();
        alert("Passwords do not match!");
      }
    });