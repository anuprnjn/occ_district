<footer class="footer">
<span>
    Developed and hosted by 
    <a href="https://www.nic.in" target="_blank" class="nic-highlight" onclick="return confirmRedirect(event, 'https://www.nic.in')">
        National Informatics Centre,
    </a><br>
    <a href="https://www.meity.gov.in" target="_blank" class="nic-highlight" onclick="return confirmRedirect(event, 'https://www.meity.gov.in')">
        Ministry of Electronics & Information Technology,
    </a> Government of India.
</span>

    <img src="{{ asset('passets/images/niclogo.png') }}" alt="NIC Logo">
</footer>
</main>
</body>
<script>
function confirmRedirect(event, url) {
    event.preventDefault(); // stop immediate navigation
    const proceed = confirm("The website you want to open will take you to an external website. Do you want to continue?");
    if (proceed) {
        window.open(url, '_blank');
    }
    return false; // prevent default anchor behavior
}
</script>
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
        "/pendingPayments": "track_app",
        "/application-details": "home",
        "/trackStatusDetails": "track_app",
        "/caseInformation": "home",
        "/caseInformationDc": "home",
        "/occ/cd_pay": "track_app", // Default behavior for /occ/cd_pay
        "/api/occ/gras_resp_cc": "track_app",
        "/trackStatusMobileHC" : "track_app",
        "/trackStatusMobileDC" : "track_app",
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