
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

    function toggleDropdownDC() {
        const dropdownMenu = document.getElementById('dropdownMenuDC');
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
    function filterOptionsDC() {
        const searchInput = document.getElementById('searchInputDC').value.toLowerCase();
        const options = document.querySelectorAll('#dropdownOptionsDC li');

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
    function selectOptionDC(element) {
        const dropdownToggle = document.getElementById('dropdownToggleDC');
        const dropdownMenu = document.getElementById('dropdownMenuDC');

        // Set selected value
        dropdownToggle.innerText = element.innerText;
        dropdownToggle.dataset.value = element.dataset.value;

        // Close dropdown
        dropdownMenu.classList.add('hidden');

        // Trigger additional actions (e.g., fetchEstablishments)
        fetchEstablishmentsDC(element.dataset.value);
    }
    // // Close dropdown when clicking outside
    // document.addEventListener('click', function (event) {
    //     const dropdown = document.getElementById('dropdown');
    //     const dropdownMenu = document.getElementById('dropdownMenu');

    //     if (!dropdown.contains(event.target)) {
    //         dropdownMenu.classList.add('hidden');
    //     }
    // });

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
    // document.addEventListener('click', function (event) {
    //     const caseTypeDropdown = document.getElementById('caseTypeDropdown');
    //     const caseTypeMenu = document.getElementById('caseTypeMenu');

    //     if (!caseTypeDropdown.contains(event.target)) {
    //         caseTypeMenu.classList.add('hidden');
    //     }
    // });

// function to send otp and verify 

let timerInterval;
let mobileNumber;

function isNumber(event) {
    const key = event.key;
    // Allow numeric keys (0-9) and control keys (backspace, delete, tab, arrow keys)
    if ((key >= '0' && key <= '9') || key === 'Backspace' || key === 'Delete' || key === 'Tab' || key === 'ArrowLeft' || key === 'ArrowRight') {
        return true;
    }
    // Prevent any other keypress
    event.preventDefault();
    return false;
}

function view_recent_app(){
    const application_modal = document.getElementById("application_n_details");
    application_modal.classList.remove("hidden");
}


//************************************************** OTP logic OTHERS COPY *****************************************************

// Function to send OTP
function sendOtp(courtType) {
    sessionStorage.setItem("court_type_for_verify_otp",courtType);
    const mobileInput = document.getElementById("mobileInput");
    sessionStorage.setItem("mobile_number_dc",mobileInput.value);
    const otpButton = document.getElementById("otpButton");
    const mobileLabel = document.getElementById("mobileLabel");
    const mobile_indicator = document.getElementById("mobile_indicator");

    if (!mobileInput.value || mobileInput.value.length !== 10) {
        alert("Please enter a valid 10-digit mobile number.");
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
                mobileLabel.innerHTML = 'Enter OTP : <span style="color:red;">*</span>';
                mobile_indicator.classList.remove("hidden");
                const maskedMobile = mobileNumber.slice(0, 2) + 'xxxx' + mobileNumber.slice(-4);
                mobile_indicator.textContent = `OTP has been sent to ${maskedMobile}`;
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
    const TrackMobileNumber = sessionStorage.getItem('mobile_number_dc');
    const mobile_indicator = document.getElementById("mobile_indicator");
   
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
                mobile_indicator.classList.add("hidden");
                mobileLabel.textContent = "Mobile Number Verified:";
                mobileLabel.classList.add("text-green-500");
                mobileLabel.classList.add("font-normal");
                mobileInput.value = mobileNumber;
                mobileInput.disabled = true;
                otpButton.classList.add("hidden");
                otpTimer.classList.add("hidden");
                submitButton.classList.remove("hidden");
                getApplicationDetailByMobile(mobileNumber);
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
    const mobile_indicator = document.getElementById("mobile_indicator");
    const mobileLabel = document.getElementById("mobileLabel");
    mobileInput.disabled = false;

    const mobileNumber = sessionStorage.getItem('mobile_number_dc');
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
               mobileLabel.innerHTML = `Enter OTP : <span style="color:red;">*</span>`;
                mobileInput.classList.remove("cursor-not-allowed", "opacity-50");
                mobileInput.value='';
                mobile_indicator.classList.remove('hidden');
                const maskedMobile = mobileNumber.slice(0, 2) + 'xxxx' + mobileNumber.slice(-4);
                mobile_indicator.textContent = `OTP has been sent to ${maskedMobile}`;
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
    const mobile_indicator = document.getElementById("mobile_indicator");
    const mobileLabel = document.getElementById("mobileLabel");
    let timeLeft = 60;

    const mobile_number_dc = sessionStorage.getItem("mobile_number_dc");
    otpTimer.textContent = "Resend OTP in (01:00)";
    otpTimer.classList.remove("hidden");

    timerInterval = setInterval(() => {
        timeLeft--;
        const minutes = String(Math.floor(timeLeft / 60)).padStart(2, "0");
        const seconds = String(timeLeft % 60).padStart(2, "0");
        otpTimer.textContent = `Resend OTP in (${minutes}:${seconds})`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            mobileLabel.innerHTML = 'Resend OTP : <span style="color:red;">*</span>';
            otpTimer.classList.add("hidden");
            mobile_indicator.classList.add("hidden");
            otpButton.textContent = "Resend OTP";
            mobileInput.disabled = true;
            mobileInput.value = mobile_number_dc;
            mobileInput.classList.add("cursor-not-allowed", "opacity-50");
            otpButton.disabled = false;
            otpButton.setAttribute("onclick", "resendOtp()"); // Ensure resendOtp is set
        }
    }, 1000);
}

//**************************************************track status OTP logic *****************************************************

function sendOtpTrack(selectedCourt,validatedMobile, application_number){
    
    sessionStorage.setItem('otp_mobile', validatedMobile);
    // console.log('new function',selectedCourt, validatedMobile);
    const otpButton = document.getElementById("otpButtonTrack");
    const otp_label = document.getElementById("otp_label");
    otpButton.textContent = "Verify OTP";
    otpButton.setAttribute("onclick", `verifyOtpTrack('${selectedCourt}', '${validatedMobile}', '${application_number}')`)

     fetch('/send-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ mobile: validatedMobile }),
    }).then(response => response.json())
    .then(data => {
        if(data.success){
            alert(`Your OTP is: ${data.otp}`);
            startOtpTimerTrack(selectedCourt,validatedMobile);
        }else{
            alert(data.message);
        }
    })
    
}
// Function to verify OTP
function verifyOtpTrack(selectedCourt,validatedMobile, application_number) {
    
    const mobileInput = document.getElementById("otp");
    const otp_label = document.getElementById("otp_label");
    const otpTimertrack = document.getElementById("otpTimertrack");
    const app_mobile = document.getElementById("application_number");
    const otpButtonTrack = document.getElementById("otpButtonTrack");

    if (!mobileInput.value) {
        alert("Please enter the OTP.");
        return;
    }

    const otp = mobileInput.value;
    console.log('otp',otp);

    // Make a POST request to the verifyOtp endpoint
    fetch('/verify-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ mobile: validatedMobile, otp: otp }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearInterval(timerInterval);
                otp_label.textContent = "Mobile Number Verified:";
                otp_label.classList.add("text-green-500");
                otp_label.classList.add("font-normal");
                mobileInput.type = "password";
                mobileInput.value = validatedMobile;
                mobileInput.disabled = true;
                app_mobile.disabled = true;
                mobileInput.style.cursor = "not-allowed";
                app_mobile.style.cursor = "not-allowed";
                otpTimertrack.classList.add("hidden");
                otpButtonTrack.disabled = true;
                otpButtonTrack.classList.add("cursor-not-allowed", "opacity-50");
                sessionStorage.removeItem("otp_mobile");
                console.log(selectedCourt);
                if (selectedCourt === 'HC') {
                    window.location.href='/trackStatusMobileHC';
                } else if (selectedCourt === 'DC') {
                    window.location.href='/trackStatusMobileDC';
                } else {
                    const applicationNumber = application_number;
                    const encodedAppNumber = btoa(applicationNumber); // Base64 encode
                    window.location.href = `/trackStatusDetails?application_number=${encodeURIComponent(encodedAppNumber)}`;
                }
            } else {
                alert("Invalid OTP. Try again.");
            }
        })
        .catch(error => console.error('Error verifying OTP:', error));
}

function startOtpTimerTrack(selectedCourt,validatedMobile) {
    
    const otpButton = document.getElementById("otpButtonTrack");
    const otpTimer = document.getElementById("otpTimertrack");
    const mobileInput = document.getElementById("otp");
    const err_span = document.getElementById('error_span');
    let timeLeft = 10;
    const mobile_number = sessionStorage.getItem("otp_mobile");
    otpTimer.textContent = "Resend OTP in (01:00)";
    otpTimer.classList.remove("hidden");

    timerInterval = setInterval(() => {
        timeLeft--;
        const minutes = String(Math.floor(timeLeft / 60)).padStart(2, "0");
        const seconds = String(timeLeft % 60).padStart(2, "0");
        otpTimer.textContent = `Resend OTP in (${minutes}:${seconds})`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            err_span.classList.add('hidden');
            otpTimer.classList.add("hidden");
            otpButton.textContent = "Resend OTP";
            mobileInput.disabled = true;
            // mobileInput.classList.add('cursor-not-allowed');
            otpButton.disabled = false;
            otpButton.setAttribute("onclick", `resendOtpTrack('${selectedCourt}', '${mobile_number}')`); // Ensure resendOtp is set
        }
    }, 1000);
}

// Function to resend OTP
function resendOtpTrack(selectedCourt,validatedMobile) {
    const otpButton = document.getElementById("otpButtonTrack");
    const mobileInput = document.getElementById("otp");
    const err_span = document.getElementById('error_span');

    mobileInput.disabled = false;

    // Make a POST request to the resendOtp endpoint
    fetch('/resend-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ mobile: validatedMobile }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`OTP resent successfully. Your OTP is: ${data.otp}`); // Show OTP for now
            err_span.classList.remove('hidden');
            const maskedMobile = validatedMobile.slice(0, 2) + 'xxxx' + validatedMobile.slice(-4);
           if(selectedCourt === 'HC'){
               err_span.innerHTML = `OTP has been sent to mobile number - <span style="color:red;">${maskedMobile}</span>.`;
           } else if (selectedCourt === 'DC') {
                err_span.innerHTML = `OTP has been sent to mobile number - <span style="color:red;">${maskedMobile}</span>.`;
            } else {
                err_span.innerHTML = `<span style="color: green;">OTP has been sent to registered mobile number - </span><span style="color:red;">${maskedMobile}</span>.`;
            }
            otpButton.textContent = "Verify OTP";
            otpButton.setAttribute("onclick", `verifyOtpTrack('${selectedCourt}', '${validatedMobile}')`); // Ensure verifyOtp is set
            startOtpTimerTrack(selectedCourt,validatedMobile);
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error resending OTP:', error));
}


//**************************************************track status OTP logic end *****************************************************



//CODE FOR CASE TYPE OF ORDER JUDGEMENT FORM

function toggleCaseTypeDropdownForOrderJudgement() {
    const caseTypeMenu = document.getElementById('caseTypeMenuForOrderJudgementForm');
    caseTypeMenu.classList.toggle('hidden');
}

function toggleCaseTypeDropdownForOrderJudgementDC() {
    const dropdownMenu = document.getElementById('caseTypeMenuForOrderJudgementFormDC');
    
    // Toggle the visibility of the dropdown menu
    dropdownMenu.classList.toggle('hidden');
}


// Filter dropdown options
function filterCaseTypeOptionsForOrderJudgementForm() {
    const searchInput = document.getElementById('caseTypeSearchInputForOrderJudgementForm').value.toLowerCase();
    const options = document.querySelectorAll('#caseTypeOptionsForOrderJudgementForm li');

    options.forEach(option => {
        const text = option.textContent || option.innerText;
        option.style.display = text.toLowerCase().includes(searchInput) ? '' : 'none';
    });
}


function selectCaseTypeOptionForOrderJudgementForm(element) {
    const caseTypeToggle = document.getElementById('caseTypeToggleForOrderJudgementForm');
    const caseTypeMenu = document.getElementById('caseTypeMenuForOrderJudgementForm');

    // Check if elements exist before manipulating them
    if (!caseTypeToggle || !caseTypeMenu) {
        console.error("Dropdown elements not found!");
        return;
    }

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
    document.querySelector('#caseTypeDropdownForOrderJudgement').appendChild(caseTypeInput);
}

function getApplicationDetailByMobile(mobileNumber) {
    const courtType = sessionStorage.getItem('court_type_for_verify_otp');
    
    const view_recent_applications = document.getElementById('view_recent_button');
    var url = '/application-mobile-track';
    if(courtType == 'HC') {
        url = '/application-mobile-track-hc';
    } else {
        url = '/application-mobile-track';
    }
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ TrackMobileNumber : mobileNumber }),
    })
    .then(response => response.json())
    .then(serverData =>{
        if(serverData.success && serverData.data.count > 0)
            {
            view_recent_applications.classList.remove('hidden');
            
            let maskedNumber = `xxxxx xxx${mobileNumber.slice(-2)}`;
            document.getElementById("modalText").innerHTML = 
            `Details of recently applied applications with mobile no <span class="text-green-500">${maskedNumber}</span>`;

            var MobileTrackApplicationDataDC = serverData.data.data;
            console.log(MobileTrackApplicationDataDC);

           
            // Sort data from latest to oldest based on created_at
            let trackedDataHTML = `
            <table class="border-collapse border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Application Number</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Applied On</th>
                    </tr>
                </thead>
                <tbody>`;
        
        MobileTrackApplicationDataDC.forEach(app => {
            trackedDataHTML += `
                <tr class="text-center">
                    <td class="border border-gray-300 px-4 py-2 text-[#D09A3F]">
                        <a href="trackStatusDetails?application_number=${btoa(app.application_number)}" 
                        class="underline hover:text-[#B07D2E]">
                            ${app.application_number}
                        </a>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">${app.status}</td>
                    <td class="border border-gray-300 px-4 py-2 text-sky-500">${app.created_at}</td>
                </tr>`;
        });
        
        trackedDataHTML += `
                </tbody>
            </table>`;
        
        document.getElementById("trackedDataDC").innerHTML = trackedDataHTML;

            // document.getElementById("trackedDataDC").innerHTML = 
            // `Details of recently applied applications with mobile no <span class="text-green-500">${MobileTrackApplicationDataDC.application_number}</span>`;
        }else{
            return;
        }
    })

}

// function to toggle the district court form using select box 

function toggleDistForm() {
    var selectedOption = document.getElementById("civilCourtSelect").value;
    if (selectedOption === "applyJudgementDC") {
        document.getElementById("orderJudgementFormDC").style.display = "block";
        document.getElementById("applyOrdersFormDC").style.display = "none";
    } else if (selectedOption === "applyOrdersDC") {
        document.getElementById("applyOrdersFormDC").style.display = "block";
        document.getElementById("orderJudgementFormDC").style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleDistForm();  
});


function toggleFieldsDC(radio) {
    const caseFields = document.querySelectorAll('.case-field');
    const fillingFields = document.querySelectorAll('.filling-field');

    if (radio.value === 'case') {
        caseFields.forEach(el => el.style.display = 'block');
        fillingFields.forEach(el => el.style.display = 'none');
    } else if (radio.value === 'filling') {
        caseFields.forEach(el => el.style.display = 'none');
        fillingFields.forEach(el => el.style.display = 'block');
    }
}

// Ensure default view is correct on page load
document.addEventListener('DOMContentLoaded', function () {
    const defaultRadio = document.querySelector('input[name="search-type-case"]:checked');
    if (defaultRadio) {
        toggleFieldsDC(defaultRadio);
    }
});


document.addEventListener('DOMContentLoaded',function(){
    
})