<!--begin::Footer-->
<footer class="app-footer">
  <!--begin::To the end-->
  <div class="float-end d-none d-sm-inline">Anything you want</div>
  <!--end::To the end-->
  <!--begin::Copyright-->
  <strong>
    Copyright &copy; 2020-2030&nbsp;
    <a href="https://adminlte.io" class="text-decoration-none">NIC</a>.
  </strong>
  All rights reserved.
  <!--end::Copyright-->
</footer>
<!--end::Footer-->
</div>
<!--end::App Wrapper-->
<!--begin::Script-->
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <!-- Correct way to include DataTables JS -->
 <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script
src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
crossorigin="anonymous"
></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="{{ asset('assets/dist/js/adminlte.js')}}""></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const Default = {
  scrollbarTheme: 'os-theme-light',
  scrollbarAutoHide: 'leave',
  scrollbarClickScroll: true,
};
document.addEventListener('DOMContentLoaded', function () {
  const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
  if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
      scrollbars: {
        theme: Default.scrollbarTheme,
        autoHide: Default.scrollbarAutoHide,
        clickScroll: Default.scrollbarClickScroll,
      },
    });
  }
});
</script>
<!--end::OverlayScrollbars Configure-->
<!--end::Script-->


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

<script>
  $(document).ready(function () { 
      // First, remove 'menu-open' and 'active' from all items
      $(".nav-item").removeClass("menu-open");
      $(".nav-link").removeClass("active");

      // Get the current URL path
      let currentUrl = window.location.href;

      // Loop through all sidebar links
      $(".nav-link").each(function () {
          let link = $(this).attr("href");

          // If the link matches the current URL, activate the corresponding menu
          if (currentUrl.includes(link)) {
              $(this).addClass("active"); // Activate current link
              
              // Activate the parent menu (if exists)
              $(this).closest(".nav-treeview").parent().addClass("menu-open");
              $(this).closest(".nav-treeview").prev(".nav-link").addClass("active");
          }
      });
  });
</script>


</body>
<!--end::Body-->
</html>
