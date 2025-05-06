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

    // Store previous page when navigating
    if (!sessionStorage.getItem("previousPage") || sessionStorage.getItem("previousPage") !== currentUrl) {
        sessionStorage.setItem("previousPage", document.referrer.split(window.location.origin)[1] || "/");
    }
    
    // Define routes
    const routes = {
        "/": "home",
        "/hc-application-details": "home",
        "/trackStatus": "track_app",
        "/pendingPayments": "pending_payments",
        "/application-details": "home",
        "/trackStatusDetails": "track_app",
        "/caseInformation": "home",
        "/caseInformationDc": "home",
        "/occ/cd_pay": "home", // Default behavior for /occ/cd_pay
        "/api/occ/gras_resp_cc": "home",
    };

    // Special condition for /occ/cd_pay
    if (currentUrl === "/occ/cd_pay") {
        const previousPage = sessionStorage.getItem("previousPage");

        if (previousPage === "/pendingPayments") {
            routes["/occ/cd_pay"] = "pending_payments"; // Show "Pending Payments" active
        }
    }

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