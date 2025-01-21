
// function to call another pages in the main page 

    const loadingSpinner = document.getElementById('loading-spinner');
    const contentArea = document.getElementById('content-area');

    const loadContent = (routeName) => {
       
        loadingSpinner.classList.remove('hidden');
        contentArea.innerHTML = ''; 

        fetch(`/${routeName}`, { 
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to fetch: ${response.statusText}`);
            }
            return response.text();
        })
        .then(data => {
            contentArea.innerHTML = data;
            loadingSpinner.classList.add('hidden');
        })
        .catch(error => {
            console.error('Fetch error:', error.message);
            contentArea.innerHTML = `<p class="text-red-500">Failed to load content. (${error.message})</p>`;
            loadingSpinner.classList.add('hidden');
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadContent('hcPage');
    });

// function to switch between pages using radio button 

    function myfun() {
        var selectedOption = document.getElementById("highCourtSelect").value;
        if (selectedOption === "applyJudgement") {
            document.getElementById("orderJudgementForm").style.display = "block";
            document.getElementById("otherForm").style.display = "none";
        } else if (selectedOption === "applyOrders") {
            document.getElementById("orderJudgementForm").style.display = "none";
            document.getElementById("otherForm").style.display = "block";
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        myfun();  
    });



    // function to toggle dropdown 
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    }

    // Filter dropdown options
    function filterOptions() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const options = document.querySelectorAll('#dropdownOptions li');

        options.forEach(option => {
            const text = option.textContent || option.innerText;
            option.style.display = text.toLowerCase().includes(searchInput) ? '' : 'none';
        });
    }

    // Select option
    function selectOption(element) {
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        // Set selected value
        dropdownToggle.innerText = element.innerText;
        dropdownToggle.dataset.value = element.dataset.value;

        // Close dropdown
        dropdownMenu.classList.add('hidden');

        // Trigger additional actions (e.g., fetchEstablishments)
        fetchEstablishments(element.dataset.value);
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('dropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (!dropdown.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    // Toggle dropdown visibility
    function toggleCaseTypeDropdown() {
        const caseTypeMenu = document.getElementById('caseTypeMenu');
        caseTypeMenu.classList.toggle('hidden');
    }

    // Filter dropdown options
    function filterCaseTypeOptions() {
        const searchInput = document.getElementById('caseTypeSearchInput').value.toLowerCase();
        const options = document.querySelectorAll('#caseTypeOptions li');

        options.forEach(option => {
            const text = option.textContent || option.innerText;
            option.style.display = text.toLowerCase().includes(searchInput) ? '' : 'none';
        });
    }

    // Select option
    function selectCaseTypeOption(element) {
        const caseTypeToggle = document.getElementById('caseTypeToggle');
        const caseTypeMenu = document.getElementById('caseTypeMenu');

        // Set selected value
        caseTypeToggle.innerText = element.innerText;
        caseTypeToggle.dataset.value = element.dataset.value;

        // Close dropdown
        caseTypeMenu.classList.add('hidden');

        // Set the hidden input value (if needed for form submission)
        const caseTypeInput = document.createElement('input');
        caseTypeInput.type = 'hidden';
        caseTypeInput.name = 'case_type';
        caseTypeInput.value = element.dataset.value;
        caseTypeInput.id = 'hiddenCaseTypeInput';

        const existingInput = document.getElementById('hiddenCaseTypeInput');
        if (existingInput) {
            existingInput.remove();
        }
        document.querySelector('#caseTypeDropdown').appendChild(caseTypeInput);
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const caseTypeDropdown = document.getElementById('caseTypeDropdown');
        const caseTypeMenu = document.getElementById('caseTypeMenu');

        if (!caseTypeDropdown.contains(event.target)) {
            caseTypeMenu.classList.add('hidden');
        }
    });

// function to send otp and verify 

let timerInterval;
let mobileNumber;

// Function to send OTP
function sendOtp() {
    const mobileInput = document.getElementById("mobileInput");
    const otpButton = document.getElementById("otpButton");
    const mobileLabel = document.getElementById("mobileLabel");

    if (!mobileInput.value || isNaN(mobileInput.value)) {
        alert("Please enter a valid mobile number.");
        return;
    }

    mobileNumber = mobileInput.value;

    // Make a POST request to the sendOtp endpoint
    fetch('/send-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ mobile: mobileNumber }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`OTP sent successfully. Your OTP is: ${data.otp}`); // Show OTP for now
                otpButton.disabled = true;

                // Update UI for OTP input
                mobileLabel.textContent = "Enter OTP:";
                mobileInput.value = "";
                mobileInput.placeholder = "Enter OTP";
                otpButton.textContent = "Verify OTP";
                otpButton.disabled = false;
                otpButton.setAttribute("onclick", "verifyOtp()"); // Ensure verifyOtp is set

                // Start the timer
                startOtpTimer();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error sending OTP:', error));
}

// Function to verify OTP
function verifyOtp() {
    const mobileInput = document.getElementById("mobileInput");
    const otpButton = document.getElementById("otpButton");
    const mobileLabel = document.getElementById("mobileLabel");
    const verificationMessage = document.getElementById("verificationMessage");
    const otpTimer = document.getElementById("otpTimer");
    const submitButton = document.querySelector(".order_btn");

    if (!mobileInput.value) {
        alert("Please enter the OTP.");
        return;
    }

    const otp = mobileInput.value;

    // Make a POST request to the verifyOtp endpoint
    fetch('/verify-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ mobile: mobileNumber, otp: otp }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearInterval(timerInterval);

                mobileLabel.textContent = "Mobile Number Verified:";
                mobileLabel.classList.add("text-green-500");
                mobileLabel.classList.add("font-normal");
                mobileInput.value = mobileNumber;
                mobileInput.disabled = true;
                otpButton.classList.add("hidden");
                otpTimer.classList.add("hidden");
                submitButton.classList.remove("hidden");
            } else {
                alert("Invalid OTP. Try again.");
            }
        })
        .catch(error => console.error('Error verifying OTP:', error));
}

// Function to resend OTP
function resendOtp() {
    const otpButton = document.getElementById("otpButton");
    const mobileInput = document.getElementById("mobileInput");
    mobileInput.disabled = false;

    // Make a POST request to the resendOtp endpoint
    fetch('/resend-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ mobile: mobileNumber }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`OTP resent successfully. Your OTP is: ${data.otp}`); // Show OTP for now
                otpButton.textContent = "Verify OTP";
                otpButton.setAttribute("onclick", "verifyOtp()"); // Ensure verifyOtp is set
                startOtpTimer();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error resending OTP:', error));
}

// Function to start OTP timer
function startOtpTimer() {
    const otpButton = document.getElementById("otpButton");
    const otpTimer = document.getElementById("otpTimer");
    const mobileInput = document.getElementById("mobileInput");
    let timeLeft = 60;

    otpTimer.textContent = "Resend OTP in (01:00)";
    otpTimer.classList.remove("hidden");

    timerInterval = setInterval(() => {
        timeLeft--;
        const minutes = String(Math.floor(timeLeft / 60)).padStart(2, "0");
        const seconds = String(timeLeft % 60).padStart(2, "0");
        otpTimer.textContent = `Resend OTP in (${minutes}:${seconds})`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            otpTimer.classList.add("hidden");
            otpButton.textContent = "Resend OTP";
            mobileInput.disabled = true;
            otpButton.disabled = false;
            otpButton.setAttribute("onclick", "resendOtp()"); // Ensure resendOtp is set
        }
    }, 1000);
}




