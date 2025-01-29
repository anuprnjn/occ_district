<footer class="footer">
    <span>Content Owned and managed by High Court of Jharkhand</span>
    <span style="color: white !important;font-weight:normal !important;">© Copyright reserved by High Court of Jharkhand</span>
</footer>
</main>
</body>
<script type="text/javascript" src="{{ asset('passets/js/script.js')}}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll("nav ul li");
    
    // Get the current page URL
    const currentUrl = window.location.pathname;

    // Define routes
    const routes = {
        "/": "home",
        "/trackStatus": "track_app",
        "/pendingPayments": "pending_payments",  // Added route for pending payments
    };

    // Remove 'active' from all <li> elements
    navLinks.forEach((li) => li.classList.remove("active"));

    // Check which route matches and add 'active' class
    for (const [path, id] of Object.entries(routes)) {
        if (currentUrl === path) {
            document.getElementById(id)?.classList.add("active");
            break;
        }
    }
});
</script>    
</html>