<footer class="footer">
    <span>Content Owned and managed by High Court of Jharkhand</span>
    <span style="color: white !important;font-weight:normal !important;">© Copyright reserved by High Court of Jharkhand</span>
</footer>
</main>
</body>
<script type="text/javascript" src="{{ asset('passets/js/script.js')}}" defer></script>
<script type="text/javascript" src="{{ asset('passets/js/jspdf.js')}}" defer></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll("nav ul li");
    
    // Get the current page URL
    const currentUrl = window.location.pathname;

    // Define routes
    const routes = {
        "/": "home",
        "/hc-application-details" : "home",
        "/trackStatus": "track_app",
        "/pendingPayments": "pending_payments",
        "/application-details": "home",
        "/trackStatusDetails": "track_app",
        "/caseInformation" : "home",
        "/occ/cd_pay" : "home",
        // "/occ/cd_pay" : "pendingPayments",
        "/api/occ/gras_resp_cc" : "home",
        // "/screenReader" : "home",
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