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

    // Remove all active classes
    navLinks.forEach(li => li.classList.remove("active"));

    // Get current path
    const currentUrl = window.location.pathname;

    // Store previous page from referrer (if not already stored or changed)
    if (!sessionStorage.getItem("previousPage") || sessionStorage.getItem("previousPage") !== currentUrl) {
        sessionStorage.setItem("previousPage", document.referrer.split(window.location.origin)[1] || "/");
    }

    const previousPage = sessionStorage.getItem("previousPage");

    // Route to menu ID mapping
    const routes = {
        "/": "home",
        "/hc-application-details": "home",
        "/trackStatus": "track_app",
        "/pendingPayments": "track_app",
        "/application-details": "home",
        "/trackStatusDetails": "track_app",
        "/caseInformation": "home",
        "/caseInformationDc": "home",
        "/occ/cd_pay": "track_app",
        "/api/occ/gras_resp_cc": "track_app",
        "/trackStatusMobileHC": "track_app",
        "/trackStatusMobileDC": "track_app",
    };

    // Default activeId from route
    let activeId = routes[currentUrl] || "home";

    // Check previousPage override: caseInformation or occ/cd_pay
    if (
        previousPage === "/caseInformation" ||
        previousPage === "/caseInformationDc" ||
        previousPage.includes("/occ/cd_pay")
    ) {
        activeId = "home";
    }

    // Final override: If PHP session indicates user is logged in
    const isUserLoggedIn = {{ session('isUserLoggedIn') ? 'true' : 'false' }};
    if (isUserLoggedIn) {
        activeId = "track_app";
    }

    // Apply the active class
    const activeElement = document.getElementById(activeId);
    if (activeElement) {
        activeElement.classList.add("active");
    }

    // Optional debug
    // console.log("currentUrl:", currentUrl);
    // console.log("previousPage:", previousPage);
    // console.log("activeId:", activeId);
});
</script>



</html>