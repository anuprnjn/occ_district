<script src="{{ asset('assets/js/jquery.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assets/js/sidebarmenu.js')}}"></script>
  <script src="{{ asset('assets/js/app.min.js')}}"></script>
  <script src="{{ asset('assets/js/simplebar.js')}}"></script>
  <script src="{{ asset('assets/js/dashboard.js')}}"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script>
  document.getElementById('logoutButton').addEventListener('click', function() {
  // Show the logout confirmation modal
  var myModal = new bootstrap.Modal(document.getElementById('logoutModal'), {
    keyboard: false
  });
  myModal.show();
  
  let progressBar = document.getElementById('logoutProgressBar');
  let countdownElement = document.getElementById('countdown');
  let progress = 0;
  let countdown = 10;

  // Start the progress bar and countdown timer
  let progressInterval = setInterval(function() {
    progress += 1;
    progressBar.style.width = progress + '%';
    progressBar.setAttribute('aria-valuenow', progress);

    if (progress >= 100) {
      clearInterval(progressInterval);
      logoutUser(); // Automatically log the user out
    }
  }, 100); // Update progress every 100ms (for 10 seconds)

  let countdownInterval = setInterval(function() {
    countdown -= 1;
    countdownElement.innerText = countdown; // Update the countdown timer

    if (countdown <= 0) {
      clearInterval(countdownInterval);
      logoutUser(); // Automatically log the user out when countdown reaches 0
    }
  }, 1000); // Update the countdown every second

  document.getElementById('cancelLogout').addEventListener('click', function() {
    clearInterval(progressInterval); // Stop the progress bar when canceled
    clearInterval(countdownInterval); // Stop the countdown timer
  });

  document.getElementById('confirmLogout').addEventListener('click', function() {
    clearInterval(progressInterval); // Stop the progress bar when confirmed
    clearInterval(countdownInterval); // Stop the countdown timer
    logoutUser(); // Log the user out
  });

  async function logoutUser() {
    try {
      let logoutResponse = await fetch('/admin/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });

      let responseData = await logoutResponse.json();

      if (responseData.success) {
        window.location.href = responseData.redirect; // Redirect to login page
      } else {
        alert("Failed to logout. Please try again.");
      }
    } catch (error) {
      console.error("Logout Error:", error);
      alert("Something went wrong. Please try again.");
    }
  }
});
</script>

</body>

</html>