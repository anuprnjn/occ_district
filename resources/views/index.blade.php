@extends('public_layouts.app')

@section('content')

<section class="content-section relative" id="main-content">
    <!-- Navigation to select pages -->
    <div class="radio-container mb-4">
        <label>
            <input type="radio" name="courtType" value="hcPage" checked onchange="loadContent('hcPage')"> High Court
        </label>
        <label class="ml-4">
            <input type="radio" name="courtType" value="dcPage" onchange="loadContent('dcPage')"> Civil Court
        </label>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="absolute w-full h-[100vh] bg-white mt-10 inset-0 flex items-start justify-start z-50">
        <div class="spinner flex items-center gap-2 p-2 ml-4 mt-4"> 
            <img class="w-[42px] animate-spin" src="{{ asset('passets/images/icons/refresh.png') }}" alt="Loading">
            <span class="text-gray-800 load text-md">Loading please wait...</span>
        </div>
    </div>
    

    <!-- Content Section -->
    <div id="content-area" class="w-full">
        <!-- Default content or a placeholder -->
        <p class="text-lg font-semibold text-rose-800">Please select a court type to view the content.</p>
    </div>
</section>

@endsection

@push('scripts')

<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>
<!-- {{-- function for setting dist_code to esta_api  --}} -->
<script>
    function fetchEstablishments(dist_code) {
        if (!dist_code) {
            // If no district is selected, reset the Establishment dropdown
            $('#selectEsta').html('<option value="" selected>Select Establishment</option>');
            return;
        }

        $.ajax({
            url: "{{ route('get-establishments') }}", // Ensure this route exists in your Laravel app
            method: 'POST',
            data: {
                dist_code: dist_code,
                _token: "{{ csrf_token() }}" // Include the CSRF token for Laravel
            },
            success: function (data) {
                // Clear and populate the Establishment dropdown
                let options = '<option value="" selected>Select Establishment</option>';
                data.forEach(function (establishment) {
                    options += `<option value="${establishment.est_code}">${establishment.estname}</option>`;
                });
                $('#selectEsta').html(options);
            },
            error: function () {
                alert('Unable to fetch establishments. Please try again later.');
            }
        });
    }
   
</script>
<!--Fetch Establishment-->
<script>
    function fetchEstablishmentsDC(dist_code) {
        if (!dist_code) {
            // If no district is selected, reset the Establishment dropdown
            $('#selectEstaDC').html('<option value="" selected>Select Establishment</option>');
            return;
        }

        $.ajax({
            url: "{{ route('get-establishments') }}", // Ensure this route exists in your Laravel app
            method: 'POST',
            data: {
                dist_code: dist_code,
                _token: "{{ csrf_token() }}" // Include the CSRF token for Laravel
            },
            success: function (data) {
                // Clear and populate the Establishment dropdown
                let options = '<option value="" selected>Select Establishment</option>';
                data.forEach(function (establishment) {
                    options += `<option value="${establishment.est_code}">${establishment.estname}</option>`;
                });
                $('#selectEstaDC').html(options);
            },
            error: function () {
                alert('Unable to fetch establishments. Please try again later.');
            }
        });
    }

   
</script>

<script>
async function refreshCaptcha() {
    const refreshBtn = document.querySelector(".refresh-btn img");
    const captchaImg = document.getElementById('captchaImage');

    refreshBtn.classList.add("animate-spin");

    try {
        const response = await fetch('/refresh-captcha');

        // DEBUG: Log raw response
        const text = await response.text();
        console.log("Raw Response:", text);

        const data = JSON.parse(text); // Convert to JSON

        if (data.captcha_src) {
            captchaImg.src = data.captcha_src;
        } else {
            alert('Failed to refresh CAPTCHA.');
        }
    } catch (error) {
        console.error('Error refreshing CAPTCHA:', error);
        alert('An error occurred while refreshing the CAPTCHA.');
    } finally {
        setTimeout(() => refreshBtn.classList.remove("animate-spin"), 1000);
    }
}
</script>
<script>
    function refreshCaptchaForOrderJudgement() {
        const refreshBtn = document.querySelector(".refresh-btn-orderJudgement img"); // Select the refresh icon
        const captchaImg = document.getElementById('captchaImageOrderJudgement');

        // Add spin animation
        refreshBtn.classList.add("animate-spin");

        fetch('/refresh-captcha')
        .then(response => response.json())
        .then(data => {
            if (data.captcha_src) {
                captchaImg.src = data.captcha_src; // Update CAPTCHA image
            } else {
                console.error('Failed to update CAPTCHA');
            }
        })
        .catch(error => {
            console.error('Error refreshing CAPTCHA:', error);
        })
        .finally(() => {
            // Remove spin animation after 1 second
            setTimeout(() => refreshBtn.classList.remove("animate-spin"), 1000);
        });
    }
</script>

<!-- {{-- function for change the input based on applied by  --}} -->
<script>
    function toggleAdvocateField() {
        var applyBy = document.getElementById("apply-by").value;
        var advocateField = document.getElementById("adv_res");
        if (applyBy === "advocate") {
            advocateField.closest('.form-field').style.display = "block";
        } else {
            advocateField.closest('.form-field').style.display = "none";
        }
    }
</script>
<!-- {{-- function for changing the fields based on case no and filling no  --}} -->
<script>
   function updateFields() {
    const selectedRadio = document.querySelector('input[name="select_mode"]:checked');
    const field1Label = document.getElementById("field1-label");
    const field1Input = document.getElementById("case-no");
    const field2Label = document.getElementById("field2-label");
    const field2Input = document.getElementById("case-year");
    const field1InputHc = document.getElementById("case-no-hc");
    const field2InputHc = document.getElementById("case-year-hc");

    if (!selectedRadio || !field1Label || !field1Input || !field2Label || !field2Input) {
        return; // Safely exit if any element is missing
    }

    const selectedMode = selectedRadio.value;

    if (selectedMode === "C") {
        field1Label.innerHTML = "Case No: <span class='red'>*</span>";
        field1Input.placeholder = "Enter Case No";
        field2Label.innerHTML = "Case Year: <span class='red'>*</span>";
        field2Input.placeholder = "Enter Case Year";
        field1InputHc.placeholder = "Enter Case No";
        field2InputHc.placeholder = "Enter Case Year";
    } else if (selectedMode === "F") {
        field1Label.innerHTML = "Filing No: <span class='red'>*</span>";
        field1Input.placeholder = "Enter Filing No";
        field2Label.innerHTML = "Filing Year: <span class='required'>*</span>";
        field2Input.placeholder = "Enter Filing Year";
        field1InputHc.placeholder = "Enter Filing No";
        field2InputHc.placeholder = "Enter Filing Year";
    }
}
</script>
<!-- {{-- Function to store the district code in sessionStorage --}} -->
<script>
    function getDistCode(element) {
        var distCode = element.getAttribute('data-value');
        if(distCode !== ''){
            sessionStorage.setItem('selectedDistCode', distCode); 
            sessionStorage.removeItem('selectedEstCode');
        }else{
            sessionStorage.removeItem('selectedDistCode');
            sessionStorage.removeItem('selectedEstCode');
        }
    }
    
</script>
<script>
    function getDistCodeDC(element) {
        var distCode = element.getAttribute('data-value');
        if(distCode !== ''){
            sessionStorage.setItem('selectedDistCode', distCode); 
            sessionStorage.removeItem('selectedEstCode');
        }else{
            sessionStorage.removeItem('selectedDistCode');
            sessionStorage.removeItem('selectedEstCode');
        }
    }
    
</script>
<!-- {{-- function to get the case type  --}} -->
<script>
    function getCaseType(elem) {
        const selectedValue = elem.getAttribute('data-value');
        if (selectedValue && !isNaN(selectedValue)) {
            sessionStorage.setItem('selectedCaseType', selectedValue);
        }
    }
</script> 

<!-- {{-- function for submit application for Civil Court Other Copy --}} -->
<script>
function handleFormSubmit(event) {
    event.preventDefault();

    // Clear all previous error messages
    document.querySelectorAll('.error-message').forEach(e => e.remove());

    const showError = (el, message) => {
        const error = document.createElement('div');
        error.className = 'error-message text-red-500 text-sm mt-1';
        error.innerText = message;
        el.parentElement.appendChild(error);
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    // 1. District
    const district_code = sessionStorage.getItem('selectedDistCode');
    if (!district_code) {
        const districtDropdown = document.getElementById('dropdown');
        showError(districtDropdown, 'Please select a district.');
        return;
    }

    // 2. Establishment
    const establishment_code = sessionStorage.getItem('selectedEstCodeDC');
    if (!establishment_code) {
        const establishmentSelect = document.getElementById('selectEsta');
        showError(establishmentSelect, 'Please select an establishment.');
        return;
    }

    // 3. Name
    const nameInput = document.getElementById('name');
    if (!nameInput.value.trim()) {
        showError(nameInput, 'Please enter the applicant name.');
        return;
    }

    // 4. Mobile
    const mobile = document.getElementById('mobileInput');
    if (!mobile.value.trim()) {
        showError(mobile, 'Please enter the mobile number.');
        return;
    }

    // 5. Email
    const email = document.getElementById('email');
    if (!email.value.trim()) {
        showError(email, 'Please enter email.');
        return;
    }

    // 6. Confirm Email
    const confirmEmail = document.getElementById('confirm-email');
    if (!confirmEmail.value.trim()) {
        showError(confirmEmail, 'Please confirm email.');
        return;
    }

    if (email.value.trim() !== confirmEmail.value.trim()) {
        showError(confirmEmail, 'Emails do not match.');
        return;
    }

    // 7. Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
        showError(email, 'Enter a valid email.');
        return;
    }

    // 8. Method (Case No / Filling No)
    const selectedMethod = document.querySelector('input[name="select_mode"]:checked');
    if (!selectedMethod) {
        const methodField = document.querySelector('input[name="select_mode"]').closest('.form-field');
        showError(methodField, 'Please select the method.');
        return;
    }

    // 9. Case Type
    const case_type = sessionStorage.getItem('selectedCaseTypeDCNapix');
    if (!case_type) {
        const caseTypeSelect = document.getElementById('caseTypeSelectForOyherCopyDC');
        showError(caseTypeSelect, 'Please select a case type.');
        return;
    }

    // 10. Case/Filing No
    const caseFillingNumber = document.getElementById('case-no');
    if (!caseFillingNumber.value.trim()) {
        showError(caseFillingNumber, 'Enter case/filling number.');
        return;
    }

    // 11. Case Year
    const caseFillingYear = document.getElementById('case-year');
    if (!caseFillingYear.value.trim()) {
        showError(caseFillingYear, 'Enter case/filling year.');
        return;
    }

    // 12. Request Mode (Urgent/Ordinary)
    const requestMode = document.querySelector('input[name="request_mode"]:checked');
    if (!requestMode) {
        const requestModeContainer = document.getElementById('urgent').parentElement;
        showError(requestModeContainer, 'Please select request mode.');
        return;
    }
    // 13. Required Document
    const requiredDoc = document.getElementById('required-document');
    if (!requiredDoc.value.trim()) {
        showError(requiredDoc, 'Enter document details.');
        return;
    }

    // 14. Applied By
    const appliedBy = document.getElementById('apply-by');
    if (!appliedBy.value.trim()) {
        showError(appliedBy, 'Select who is applying.');
        return;
    }

    // 15. Advocate Registration No (if applicable)
    const advocateField = document.getElementById('adv_res');
    if (appliedBy.value === 'advocate' && !advocateField.value.trim()) {
        showError(advocateField, 'Enter Advocate Registration No.');
        return;
    }

    // 16. CAPTCHA
    const captcha = document.getElementById('captcha');
    if (!captcha.value.trim()) {
        showError(captcha, 'Please solve the captcha.');
        return;
    }

    // ✅ CAPTCHA validation via API
    fetch('/validate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ captcha: captcha.value.trim() })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showError(captcha, 'CAPTCHA validation failed.');
            captcha.value = '';
            refreshCaptcha();
        } else {
            submitFormData(); // Proceed only if captcha passed
        }
    })
    .catch(error => {
        console.error('CAPTCHA validation error:', error);
        showError(captcha, 'An error occurred while validating CAPTCHA.');
    });
}   
// Function to submit the form data to the backend
function submitFormData() {
    var confirmation = confirm('This is the final submit. Please check all your data. If you want to continue, press OK. Press Cancel to cancel the submission.');

    if (!confirmation) {
        refreshCaptcha();
        document.getElementById('captcha').value = '';
        return;  
    }

    var formData = {
        district_code: sessionStorage.getItem('selectedDistCode'),
        establishment_code: sessionStorage.getItem('selectedEstCodeDC'),
        applicant_name: document.getElementById('name').value,
        mobile_number: document.getElementById('mobileInput').value,
        email: document.getElementById('email').value,
        case_type: sessionStorage.getItem('selectedCaseTypeDCNapix'),
        case_filling_number: document.getElementById('case-no').value,
        case_filling_year: document.getElementById('case-year').value,
        request_mode: document.querySelector('input[name="request_mode"]:checked')?.value,
        required_document: document.getElementById('required-document').value,
        applied_by: document.getElementById('apply-by').value,
        advocate_registration_number: document.getElementById('adv_res').value,
        selected_method: document.querySelector('input[name="select_mode"]:checked')?.value,
    };

    // Send the form data to the server
    fetch('/register-application', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify(formData),
    })
    .then(response => response.json()) // Ensure the response is in JSON format
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('application_number', data.application_number);
            document.getElementById('applyOrdersFormDC').reset();
            const dropdownToggle = document.getElementById('dropdownToggle');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const searchInput = document.getElementById('searchInput');
            const dropdownOptions = document.getElementById('dropdownOptions').querySelectorAll('li');
            dropdownToggle.textContent = 'Please Select District'; // Reset dropdown toggle text
            dropdownMenu.classList.add('hidden'); // Hide the dropdown menu
            searchInput.value = ''; // Clear the search input

            // Reset the selected option in the custom dropdown
            dropdownOptions.forEach(option => {
                option.classList.remove('bg-gray-200'); // Remove any selected styling
            });

            // Reset the "Select Establishment" dropdown
            const selectEsta = document.getElementById('selectEsta');
            selectEsta.innerHTML = '<option value="" selected>Select Establishment</option>';
            const mobileLabel = document.getElementById("mobileLabel");
            mobileLabel.innerHTML = 'Mobile Number : <span class="text-red-500">*</span>';
            mobileLabel.classList.remove("text-green-500");
            mobileInput.placeholder = "Enter mobile number";
            window.location.href = '/application-details';
            // alert(`Application registered successfully! Application Number: ${data.application_number}`);
            // console.log('Success:', data);

        } else {
            alert('Failed to register application. Please try again.');
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error.message);
        alert(`Error: ${error.message}`);
    });
}
</script>    

<!-- {{-- function for submit application for High Court Other Copy --}} -->
<script>
function handleFormSubmitForHighCourt(event) {
    event.preventDefault();

    // Clear all previous errors
    document.querySelectorAll('.error-message').forEach(e => e.remove());
    document.querySelectorAll('.border-red-500').forEach(e => e.classList.remove('border-red-500'));

    const showError = (el, message) => {
        const container = el.classList.contains('form-field') ? el : el.closest('.form-field') || el;

        // Avoid duplicates
        if (!container.querySelector('.error-message')) {
            const errorMsg = document.createElement('div');
            errorMsg.className = 'error-message text-red-500 text-sm mt-1';
            errorMsg.innerText = message;
            container.appendChild(errorMsg);
        }

        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    const applicantName = document.getElementById('name');
    if (!applicantName.value.trim()) {
        showError(applicantName, 'Please enter your name.');
        return;
    }

    const mobileInput = document.getElementById('mobileInput');
    if (!mobileInput.value.trim()) {
        showError(mobileInput, 'Please enter mobile number.');
        return;
    }

    const email = document.getElementById('email');
    const confirmEmail = document.getElementById('confirm-email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim() || !emailRegex.test(email.value)) {
        showError(email, 'Enter a valid email.');
        return;
    }
    if (email.value.trim() !== confirmEmail.value.trim()) {
        showError(confirmEmail, 'Emails do not match.');
        return;
    }

    const selectedMethod = document.querySelector('input[name="select_mode"]:checked');
    if (!selectedMethod) {
        const methodGroup = document.querySelector('input[name="select_mode"]').closest('.radio-group');
        showError(methodGroup, 'Please select a method.');
        return;
    }

    const caseType = document.getElementById('case_type');
    if (!caseType.value) {
        showError(caseType, 'Please select a case type.');
        return;
    }

    const caseNo = document.getElementById('case-no-hc');
    if (!caseNo.value.trim()) {
        showError(caseNo, 'Please enter case number or filling number.');
        return;
    }

    const caseYear = document.getElementById('case-year-hc');
    if (!caseYear.value.trim()) {
        showError(caseYear, 'Please enter case year or filling year.');
        return;
    }

    const selectedRequestMode = document.querySelector('input[name="request_mode"]:checked');
    if (!selectedRequestMode) {
        const requestGroup = document.getElementById('request-mode-group');
        showError(requestGroup, 'Please select request mode.');
        return;
    }

    const requiredDoc = document.getElementById('required-document');
    if (!requiredDoc.value.trim()) {
        showError(requiredDoc, 'Please enter document details.');
        return;
    }

    const appliedBy = document.getElementById('apply-by');
    if (!appliedBy.value) {
        showError(appliedBy, 'Please select applied by.');
        return;
    }

    const advocateField = document.getElementById('adv_res');
    if (appliedBy.value === 'advocate' && !advocateField.value.trim()) {
        showError(advocateField, 'Enter Advocate Registration No.');
        return;
    }

    const captcha = document.getElementById('captcha-hc');
    if (!captcha.value.trim()) {
        showError(captcha, 'Please solve the captcha.');
        return;
    }

    // CAPTCHA API Check
    fetch('/validate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ captcha: captcha.value.trim() })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showError(captcha, 'CAPTCHA validation failed.');
            captcha.value = '';
            refreshCaptcha();
        } else {
            submitHcFormData(); // Proceed
        }
    })
    .catch(error => {
        console.error('CAPTCHA validation error:', error);
        alert('An error occurred while validating CAPTCHA.');
    });
}
// Helper to show field-specific errors
function markFieldError(input, message) {
    input.classList.add('border-red-500');
    const parent = input.closest('.form-field');
    if (!parent.querySelector('.error-message')) {
        const error = document.createElement('span');
        error.classList.add('text-red-500', 'text-sm', 'error-message');
        error.innerText = message;
        parent.appendChild(error);
    }
}

// Function to submit the form data to the backend
function submitHcFormData() {
    var confirmation = confirm('This is the final submit. Please check all your data. If you want to continue, press OK. Press Cancel to cancel the submission.');

    if (!confirmation) {
        refreshCaptcha();
        document.getElementById('captcha').value = '';
        return;  
    }
    // Clear previous errors
    document.querySelectorAll('.form-field input, .form-field select, .form-field textarea').forEach(field => {
        field.classList.remove('border-red-500');
        let errorSpan = field.parentElement.querySelector('.error-message');
        if (errorSpan) errorSpan.remove();
    });

    const formData = {
        applicant_name: document.getElementById('name').value,
        mobile_number: document.getElementById('mobileInput').value,
        email: document.getElementById('email').value,
        case_type: document.getElementById('case_type').value,
        case_filling_number: document.getElementById('case-no-hc').value,
        case_filling_year: document.getElementById('case-year-hc').value,
        request_mode: document.querySelector('input[name="request_mode"]:checked')?.value,
        required_document: document.getElementById('required-document').value,
        applied_by: document.getElementById('apply-by').value,
        advocate_registration_number: document.getElementById('adv_res').value,
        selected_method: document.querySelector('input[name="select_mode"]:checked')?.value,
    };

    fetch('/hc-register-application', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify(formData),
    })
    .then(response => {
        if (response.status === 422) {
            return response.json().then(data => {
                const errors = data.errors;
                for (const field in errors) {
                    let fieldId = mapFieldNameToId(field);
                    let inputField = document.getElementById(fieldId);
                    if (inputField) {
                        inputField.classList.add('border-red-500');
                        const errorMsg = document.createElement('span');
                        errorMsg.classList.add('text-red-500', 'text-sm', 'error-message');
                        errorMsg.innerText = errors[field][0];
                        inputField.parentElement.appendChild(errorMsg);
                    }
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('applyOrdersFormHC').reset();
            sessionStorage.removeItem('application_number');
            sessionStorage.setItem('application_number', data.application_number);
            window.location.href = '/hc-application-details';
        }
    })
    .catch(error => {
        console.error('Submission error:', error.message);
    });
}

function mapFieldNameToId(fieldName) {
    const map = {
        applicant_name: 'name',
        mobile_number: 'mobileInput',
        email: 'email',
        case_type: 'case_type',
        case_filling_number: 'case-no-hc',
        case_filling_year: 'case-year-hc',
        request_mode: 'urgent', // fallback radio
        required_document: 'required-document',
        applied_by: 'apply-by',
        advocate_registration_number: 'adv_res',
        selected_method: 'case_no' // fallback radio
    };
    return map[fieldName] || fieldName;
}
</script>
<script>
    window.addEventListener('load', function () {
        sessionStorage.removeItem('selectedDistCode');
        sessionStorage.removeItem('selectedEstCodeDC');
        sessionStorage.removeItem('selectedCaseTypeDCNapix');
    });
</script>
<script>
function closeModal(event) {
    if (event) event.preventDefault(); 
    document.getElementById("application_n_details").classList.add("hidden");
}   
</script>

<script>
    window.addEventListener('load', function() {
        sessionStorage.setItem('search_type','case');
    });
</script>    

<script>
    function toggleFields(radio) {
        

    var selectedMode = document.querySelector('input[name="search-type-case"]:checked').value;
    sessionStorage.setItem('search_type',selectedMode);

    const caseFields = document.querySelectorAll(".case-field");
    const filingFields = document.querySelectorAll(".filling-field");
    const caseInputs = document.querySelectorAll(".case-field input");
    const filingInputs = document.querySelectorAll(".filling-field input");

    // Check which radio button is selected and adjust the fields accordingly
    if (radio.value === "case") {
        // Show Case fields, hide Filing fields
        caseFields.forEach(field => field.style.display = "block");
        filingFields.forEach(field => field.style.display = "none");

        // Update data-value for inputs in case section
        caseInputs.forEach(input => input.dataset.value = "C");
        filingInputs.forEach(input => input.dataset.value = ""); // Clear data-value in filing fields
    } else {
        // Show Filing fields, hide Case fields
        caseFields.forEach(field => field.style.display = "none");
        filingFields.forEach(field => field.style.display = "block");

        // Update data-value for inputs in filing section
        filingInputs.forEach(input => input.dataset.value = "F");
        caseInputs.forEach(input => input.dataset.value = ""); // Clear data-value in case fields
    }
}

// Ensure the correct state on page load
document.addEventListener("DOMContentLoaded", function () {
    // Get the selected radio button on page load
    const selectedRadio = document.querySelector('input[name="search-type-case"]:checked');
    
    // If a radio button is selected, toggle fields accordingly
    if (selectedRadio) {
        toggleFields(selectedRadio);
    }
});
</script>

<!--get Case Type From High court case type drop down --> 
<script>
   function getHcCaseType(element) {
        var caseType = element.getAttribute('data-value');
        if (caseType !== '') {
            sessionStorage.setItem('selectedHcCaseType', caseType); 
        } else {
            sessionStorage.removeItem('selectedHcCaseType');
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const caseNoInput = document.getElementById("case-no");
        const filingNoInput = document.getElementById("filling-no");

        function allowOnlyDigits(e) {
            const allowedKeys = [
                'Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab'
            ];
            if (
                !/^\d$/.test(e.key) && // Not a digit (0–9)
                !allowedKeys.includes(e.key)
            ) {
                e.preventDefault(); // Block the character from being typed
            }
        }

        if (caseNoInput) {
            caseNoInput.addEventListener("keydown", allowOnlyDigits);
        }

        if (filingNoInput) {
            filingNoInput.addEventListener("keydown", allowOnlyDigits);
        }
    });
</script>

<!--Script for Search High court order copy From Napix-->
<script>

    function submitJudgementForm(e) {
        e.preventDefault();

        // Step 1: Get the Case Type from the selected element (if any)
        const selectedCaseTypeHc = sessionStorage.getItem('selectedHcCaseType');
        
        // Ensure Case Type is selected before proceeding
        if (!selectedCaseTypeHc) {
            alert("Please select Case Type.");
            return;
        }

        // Step 2: Check if the user selected a Case Type for the search form.
        const selectedRadio = document.querySelector('input[name="search-type-case"]:checked');
        if (!selectedRadio) {
            alert("Please choose Case Number or Filling Number.");
            return;
        }

        let caseNo, caseYear, filingNo, filingYear;
        
        // Step 3: Validate Case No / Filing No based on the selected search type.
        if (selectedRadio.value === "case") {
            caseNo = document.getElementById('case-no').value.trim();
            caseYear = document.getElementById('case-year').value.trim();
            
            if (!caseNo) {
                alert("Please enter Case Number.");
                return;
            }
            if (!caseYear) {
                alert("Please enter Case Year.");
                return;
            }
        } else if (selectedRadio.value === "filling") {
            filingNo = document.getElementById('filling-no').value.trim();
            filingYear = document.getElementById('filling-year').value.trim();
            
            if (!filingNo) {
                alert("Please enter Filing Number.");
                return;
            }
            if (!filingYear) {
                alert("Please enter Filing Year.");
                return;
            }
        }

        // Step 4: Validate CAPTCHA.
        const captcha = document.getElementById('captcha-hc-orderJudgement').value.trim();
        if (!captcha) {
            alert('Please evaluate the CAPTCHA.');
            return;
        }

        // Step 5: CAPTCHA API Validation
        fetch('/validate-captcha', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ captcha: captcha })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('CAPTCHA validation failed. Please try again.');
                refreshCaptchaForOrderJudgement();
                document.getElementById('captcha-hc-orderJudgement').value = '';
                return;
            }

            // Step 6: Proceed with submitting the form data after CAPTCHA is validated.
            let requestData = { selectedCaseTypeHc };
            requestData.search_type = sessionStorage.getItem('search_type');  
            if (selectedRadio.value === "case") {
                requestData.HcCaseNo = caseNo;
                requestData.HcCaseYear = caseYear;
            } else if (selectedRadio.value === "filling") {
                requestData.HcFillingNo = filingNo;
                requestData.HcFillingYear = filingYear;
            }

            const searchBtn = document.getElementById('searchBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            searchBtn.disabled = true;
            btnText.textContent = " ";
            btnSpinner.classList.remove("hidden");

            fetch('/get-hc-case-search-napix', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.status) {
                    if (data.message === "Failed to fetch access token") {
                        alert("Napix server not responding. Please try again !");
                    } else if (data.message === "Invalid response from NAPIX API") {
                        const orderDetailsDiv = document.getElementById("orderDetails");
                        orderDetailsDiv.classList.add('hidden');
                        const caseErrElement = document.getElementById('case_err');
                        caseErrElement.classList.remove('hidden');
                        caseErrElement.innerHTML = 'No Cases found !!!';
                    } else {
                        alert(data.message || "An unknown error occurred");
                    }
                    
                    btnText.textContent = "Search";
                    document.getElementById('captcha-hc-orderJudgement').value = '';
                    refreshCaptchaForOrderJudgement(); 
                    btnSpinner.classList.add("hidden");
                    searchBtn.disabled = false;
                    return;
                }

                const cino = data.data.casenos.case1.cino;
                const originalData = data;
                // console.log("CINO:", cino);
                const requestData = {
                cino,
            };
            fetch('/get-hc-case-search-cnr-napix', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ request_data: requestData })
            })
            .then(response => response.json())
            .then(responsedata => {
                console.log("OrderDetails", responsedata.data?.interimorder ?? []);
                interimOrderGlobal = responsedata.data?.interimorder ?? [];
                const caseStatus =  responsedata.data.pend_disp;

                const responseDataOfCnr = responsedata.data;

            setTimeout(() => window.scrollBy(0, 350), 200);
                
            btnText.textContent = "Search";
            btnSpinner.classList.add("hidden");
            const orderDetailsCount = Object.keys(interimOrderGlobal).length;
            populateTableHCOrderCopy(originalData,interimOrderGlobal,caseStatus,responseDataOfCnr);
            })

            })
            .catch(error => {
                console.warn(error);
            });

        })
        .catch(error => {
            console.error('Error validating CAPTCHA:', error);
        });

        function resetForm() {
            // Reset case and filing inputs
            document.getElementById('case-no-dc').value = '';
            document.getElementById('case-year-dc').value = '';
            document.getElementById('filling-no-dc').value = '';
            document.getElementById('filling-year-dc').value = '';

            // Reset district dropdown text and ensure all options are visible
            const dropdownToggle = document.getElementById("dropdownToggleDC");
            dropdownToggle.innerText = "Please Select District";
            document.getElementById("searchInputDC").value = ''; // Clear search filter
            filterOptionsDC(); // Re-show all district options
            // Optional: clear any stored value
            document.getElementById("dropdownToggleDC").dataset.value = "";

            // Reset establishment dropdown
            const establishmentSelect = document.getElementById("selectEstaDC");
            establishmentSelect.selectedIndex = 0;
            establishmentSelect.innerHTML = '<option value="">Select Establishment</option>';

            // Reset case type dropdown
            const caseTypeSelect = document.getElementById("caseTypeSelectForOrderJudgementFormDC");
            caseTypeSelect.selectedIndex = 0;

            // Reset radio buttons and toggle respective fields
            document.querySelector('input[value="case"]').checked = true;
            toggleFieldsDC(document.querySelector('input[name="search-type-case"]:checked'));

            // Reset captcha field
            document.getElementById('captcha-hc-orderJudgement').value = '';
            refreshCaptchaForOrderJudgement(); // call specific refresh for this captcha

            // Reset search button UI
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const searchBtn = document.getElementById('searchBtn');

            btnText.classList.remove('hidden');
            btnSpinner.classList.add('hidden');
            searchBtn.disabled = false;
        }

        function populateTableHCOrderCopy(responseData, interimOrderGlobal,caseStatus,responseDataOfCnr) {
            const orderDetailsCount = Object.keys(interimOrderGlobal).length;

            console.log("count2", orderDetailsCount);
            const case_type = responseData.case_type;
            const search_type = responseData.search_type;
            const case_number = responseDataOfCnr?.reg_no ?? 'N/A';
            const case_year = responseDataOfCnr?.reg_year ?? 'N/A';
            const filling_number = responseDataOfCnr?.fil_no ?? 'N/A';
            const filling_year = responseDataOfCnr?.fil_year ?? 'N/A';
            const orderDetailsDiv = document.getElementById("orderDetails");
            const tableBody = document.getElementById("orderTableBody");
            const caseErrElement = document.getElementById('case_err');

            tableBody.innerHTML = '';
            caseErrElement.classList.add('hidden');

            if (responseData && responseData.data && responseData.data.casenos) {
                const cases = responseData.data.casenos;

                let applyText = orderDetailsCount === 0 ? "Apply for Others Copy" : "Click Here";
                let applyText2 = orderDetailsCount === 0 ? "No Order Found" : "Apply Link";

                Object.keys(cases).forEach((key, index) => {
                    const caseData = cases[key];

                    const numberValue = search_type === 'case' ? (caseData.reg_no ?? 'N/A') : (caseData.fil_no ?? 'N/A');
                    const yearValue = search_type === 'case' ? (caseData.reg_year ?? 'N/A') : (caseData.fil_year ?? 'N/A');

                    const combinedCaseDetail = `${caseData.type_name ?? 'N/A'}/${numberValue}/${yearValue}`;

                    const caseDataStr = JSON.stringify(caseData).replace(/"/g, '&quot;');
                    const caseTypeStr = JSON.stringify(case_type).replace(/"/g, '&quot;');
                    const interimOrderStr = JSON.stringify(interimOrderGlobal).replace(/"/g, '&quot;');

                    const buttonHtml = orderDetailsCount === 0
                        ? `<button onclick="handleApplyForOthers()" class="p-[10px] bg-teal-600 sm:w-[250px] hover:bg-teal-700 text-white rounded-md uppercase -ml-2">${applyText}</button>`
                        : `<button 
                            onclick="handleSetHcPhpSession(this)" 
                            data-case='${caseDataStr}' 
                            data-type='${caseTypeStr}' 
                            data-interim='${interimOrderStr}' 
                            data-status='${caseStatus}'
                            data-caseno='${case_number}' 
                            data-caseyear='${case_year}' 
                            data-filno='${filling_number}' 
                            data-filyear='${filling_year}'  
                            class="p-[10px] bg-teal-600 sm:w-[250px] hover:bg-teal-700 text-white rounded-md uppercase -ml-2">
                            ${applyText}
                        </button>`;

                    tableBody.innerHTML += `
                        <tr class="border-b">
                            <td class="p-2 font-bold uppercase">${search_type === 'case' ? 'Case Details' : 'Filing Details'}</td>
                            <td class="p-2">${combinedCaseDetail}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2 font-bold uppercase">CIN Number</td>
                            <td class="p-2">${caseData.cino ?? 'N/A'}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2 font-bold uppercase">Petitioner Name</td>
                            <td class="p-2">${caseData.pet_name ?? 'N/A'}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2 font-bold uppercase">Respondent Name</td>
                            <td class="p-2">${caseData.res_name ?? 'N/A'}</td>
                        </tr>
                         <tr class="border-b">
                            <td class="p-2 font-bold uppercase">Case Status</td>
                            <td class="p-2">${caseStatus  === 'D' ? 'DISPOSED' : 'PENDING'}</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold uppercase">${applyText2}</td>
                            <td class="p-3">
                                ${buttonHtml}
                            </td>
                        </tr>
                    `;
                });

                orderDetailsDiv.classList.remove("hidden");
                resetFormHc(); 
            } else {
                orderDetailsDiv.classList.add('hidden');
                caseErrElement.classList.remove('hidden');
                caseErrElement.innerHTML = 'No Cases found !!!';
                resetFormHc(); 
            }
        }



    }

    function handleSetHcPhpSession(button) {
        try {
            const caseData = JSON.parse(button.dataset.case);
            const caseType = JSON.parse(button.dataset.type);
            const caseStatus = JSON.parse(button.dataset.type);
            const interimOrder = JSON.parse(button.dataset.interim);
            const status = button.getAttribute('data-status');
            const reg_no = button.getAttribute('data-caseno');
            const reg_year = button.getAttribute('data-caseyear');
            const fil_no = button.getAttribute('data-filno');
            const fil_year = button.getAttribute('data-filyear');

            // Add to caseDetails object if needed
            caseData.case_type = caseType;
            caseData.interim = interimOrder;
            caseData.case_status = status;
            caseData.case_number = reg_no;
            caseData.case_year = reg_year;
            caseData.filling_number = fil_no;
            caseData.filling_year = fil_year;

            setHccaseDetailsToPhpSession(caseData);
        } catch (error) {
            console.error("Error parsing button data attributes:", error);
        }
    }

    function setHccaseDetailsToPhpSession(caseDetails) {
        fetch('/store-hc-case-details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ caseDetails })
        })
        .then(response => response.json())
        .then(data => {
            if (data.redirectLocation) {
                console.log('Case data set in PHP session');
                window.location.href = data.redirectLocation;
            } else {
                console.log(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }


    function resetFormHc() {
        // Reset case and filing inputs

        // Reset district dropdown text and ensure all options are visible

        // Reset captcha field
        document.getElementById('captcha-hc-orderJudgement').value = '';
        refreshCaptchaForOrderJudgement(); // call specific refresh for this captcha

        // Reset search button UI
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const searchBtn = document.getElementById('searchBtn');

        btnText.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
        searchBtn.disabled = false;
     
        sessionStorage.removeItem('selectedHcCaseType');
                refreshCaptchaForOrderJudgement();

                // Clear input fields
                document.getElementById('captcha-hc-orderJudgement').value = '';
                document.getElementById('case-no').value = '';
                document.getElementById('case-year').value = '';
                document.getElementById('filling-no').value = '';
                document.getElementById('filling-year').value = '';

                // Reset case type dropdown
                const caseTypeMenu = document.getElementById('caseTypeMenuForOrderJudgementForm');
                const caseTypeOptions = document.getElementById('caseTypeOptionsForOrderJudgementForm');
                const searchInput = document.getElementById('caseTypeSearchInputForOrderJudgementForm');
                const caseTypeToggle = document.getElementById('caseTypeToggleForOrderJudgementForm');

                // Reset dropdown text
                caseTypeToggle.innerText = 'Please Select Case Type';

                // Hide dropdown menu
                caseTypeMenu.classList.add('hidden');

                // Clear search input
                searchInput.value = '';
    }
</script>
<!--Script for High court when in order copy order is not available-->
<script>
    function handleApplyForOthers() {
    const orderDetailsDiv = document.getElementById("orderDetails");

    orderDetailsDiv.classList.add("hidden");

    var selectedOption = document.getElementById("highCourtSelect").value = "applyOrders";
    document.getElementById("orderJudgementForm").style.display = "none";
    document.getElementById("otherForm").style.display = "block";
}
</script>  

<!--Script for High court when in order copy order is available-->
<script>
  function handleApplyForOthersHavingOrders(index) {
      // Get the stored response data from sessionStorage
      const storedData = sessionStorage.getItem("responseData");
      console.log('stored',storedData)
    const parsedData = storedData ? JSON.parse(storedData) : null;

    if (parsedData && parsedData.cases) {
        // Store the full response data (if not already stored)
        sessionStorage.setItem("caseInfo", JSON.stringify(parsedData));
        console.log(parsedData);
    }

    // Hide order details and navigate to the next page
    document.getElementById("orderDetails").classList.add("hidden");
    window.location.href = '/caseInformation';
  }
</script> 

<!--This script is used for get case type on the basis of selected establishment in civil court other copy-->
<script>
    function saveEstCode(selectElement) {
        var selectedEstCode = selectElement.value;

        if (selectedEstCode !== '') {
            sessionStorage.setItem('selectedEstCode', selectedEstCode);

            // Show loading spinner
            document.getElementById('loadingSpinner').classList.remove('hidden');

            fetch('/get-dc-case-master', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ est_code: selectedEstCode })
            })
            .then(response => response.json())
            .then(body => {
                console.log('Case Types Response:', body);

                if (!body.success) {
                    alert(body.message);
                    document.getElementById('loadingSpinner').classList.add('hidden');
                    return;
                }

                populateSelectDropdown(body.data);
                document.getElementById('loadingSpinner').classList.add('hidden');
            })
            .catch(error => {
                console.error('Error fetching case types:', error);
                alert('An error occurred while connecting to the server.');
                document.getElementById('loadingSpinner').classList.add('hidden');
            });
        } else {
            sessionStorage.removeItem('selectedEstCode');
        }
    }

    function populateSelectDropdown(caseTypes) {
        const selectElement = document.getElementById('caseTypeSelectForOrderJudgementFormDC');

        if (!selectElement) {
            console.error('caseTypeSelectForOrderJudgementFormDC element not found!');
            return;
        }

        // Clear existing options
        selectElement.innerHTML = '<option value="">Please Select Case Type</option>';

        caseTypes.forEach(caseType => {
            const optionElement = document.createElement('option');
            optionElement.value = caseType.case_type;
            optionElement.textContent = `${caseType.type_name}`;
            selectElement.appendChild(optionElement);
        });

        // Attach change event listener (to save selected case type in sessionStorage)
        selectElement.addEventListener('change', function() {
            const selectedCaseType = this.value;
            sessionStorage.setItem('selectedCaseTypeDCNapix', selectedCaseType);
            // console.log('Selected Case Type saved to sessionStorage:', selectedCaseType);
        });
    }
</script>  


<script>

function handleSetPhpSession(button) {
    try {
        const caseData = JSON.parse(button.dataset.case);

        // joining all comming data to the caseData json
        const caseType = JSON.parse(button.dataset.type);
        const interimOrder = JSON.parse(button.dataset.interim);
        const caseStatus = button.getAttribute('data-status');
        const districtName = button.getAttribute('data-dist');
        const establishmentName = button.getAttribute('data-est');
        const establishmentCode = button.getAttribute('data-est-code');
        const distCode = button.getAttribute('data-dist-code');
        const reg_no = button.getAttribute('data-dist-caseno');
        const reg_year = button.getAttribute('data-dist-caseyear');
        const fil_no = button.getAttribute('data-dist-filno');
        const fil_year = button.getAttribute('data-dist-filyear');
        
        caseData.case_type = caseType;
        caseData.interim = interimOrder;
        caseData.case_status = caseStatus;
        caseData.district_name = districtName;
        caseData.establishment_name = establishmentName;
        caseData.establishment_code = establishmentCode;
        caseData.dist_code = distCode;
        caseData.case_number = reg_no;
        caseData.case_year = reg_year;
        caseData.filling_number = fil_no;
        caseData.filling_year = fil_year;

        setcaseDetailsToPhpSession(caseData);
    } catch (error) {
        console.error("Error parsing button data attributes:", error);
    }
}

function setcaseDetailsToPhpSession(caseDetails) {
    fetch('/store-case-details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ caseDetails })
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirectLocation) {
            console.log('Case data set in PHP session');
            window.location.href = data.redirectLocation;
        } else {
            console.log(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function submitDCJudgementForm(e) {
    e.preventDefault();

    // Step 1: Get the selected values
    const selectedDistrict = document.getElementById('dropdownToggleDC')?.innerText.trim();
    const selectedEstablishment = document.getElementById('selectEstaDC')?.value.trim();
    const selectedCaseType = document.getElementById('caseTypeSelectForOrderJudgementFormDC')?.value.trim();
    const selectedRadio = document.querySelector('input[name="search-type-case"]:checked');

    // Step 2: Validate District, Establishment, CaseType
    if (!selectedDistrict || selectedDistrict === 'Please Select District') {
        alert('Please select District.');
        return;
    }
    if (!selectedEstablishment) {
        alert('Please select Establishment.');
        return;
    }
    if (!selectedCaseType) {
        alert('Please select Case Type.');
        return;
    }
    if (!selectedRadio) {
        alert('Please choose Case Number or Filing Number.');
        return;
    }

    let caseNo, caseYear, filingNo, filingYear;

    // Step 3: Validate based on selected search type
    if (selectedRadio.value === "case") {
        caseNo = document.getElementById('case-no-dc')?.value.trim();
        caseYear = document.getElementById('case-year-dc')?.value.trim();

        if (!caseNo) {
            alert("Please enter Case Number.");
            return;
        }
        if (!caseYear) {
            alert("Please enter Case Year.");
            return;
        }
    } else if (selectedRadio.value === "filling") {
        filingNo = document.getElementById('filling-no-dc')?.value.trim();
        filingYear = document.getElementById('filling-year-dc')?.value.trim();

        if (!filingNo) {
            alert("Please enter Filing Number.");
            return;
        }
        if (!filingYear) {
            alert("Please enter Filing Year.");
            return;
        }
    }

    // Step 4: Validate CAPTCHA
    const captcha = document.getElementById('captcha-hc-orderJudgement')?.value.trim();
    if (!captcha) {
        alert('Please evaluate the CAPTCHA.');
        return;
    }

    // Step 5: CAPTCHA API Validation
    fetch('/validate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ captcha: captcha })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('CAPTCHA validation failed. Please try again.');
            refreshCaptchaForOrderJudgement();
            document.getElementById('captcha-hc-orderJudgement').value = '';
            return;
        }

        // Step 6: Prepare request data
        const requestData = {
            selectedDistrict,
            selectedEstablishment,
            selectedCaseType,
            searchType: selectedRadio.value,
        };

        if (selectedRadio.value === "case") {
            requestData.caseNo = caseNo;
            requestData.caseYear = caseYear;
        } else {
            requestData.filingNo = filingNo;
            requestData.filingYear = filingYear;
        }

        // Step 7: Disable Search button and show loading
        const searchBtn = document.getElementById('searchBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        let interimOrderGlobal = [];

        if (searchBtn && btnText && btnSpinner) {
            searchBtn.disabled = true;
            btnText.textContent = " ";
            btnSpinner.classList.remove("hidden");
        }

        fetch('/get-dc-case-search-napix', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ request_data: requestData })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.status) {
                if (data.message === "Failed to fetch access token") {
                    alert("Napix server not responding. Please try again !");
                } else if (data.message === "Invalid response from NAPIX API") {
                    const orderDetailsDiv = document.getElementById("orderDetails");
                    orderDetailsDiv.classList.add('hidden');
                    const caseErrElement = document.getElementById('case_err');
                    caseErrElement.classList.remove('hidden');
                    caseErrElement.innerHTML = 'No Cases found !!!';
                } else {
                    alert(data.message || "An unknown error occurred");
                }
                
                btnText.textContent = "Search";
                document.getElementById('captcha-hc-orderJudgement').value = '';
                refreshCaptchaForOrderJudgement(); 
                btnSpinner.classList.add("hidden");
                searchBtn.disabled = false;
                return;
            }

            const cino = data.data.casenos.case1.cino;
            const originalData = data;
            // console.log("CINO:", cino);
            const requestData = {
            cino,
        };
        fetch('/get-dc-case-search-cnr-napix', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ request_data: requestData })
        })
        .then(response => response.json())
        .then(responsedata => {
            // console.log("DATA",responsedata);
            // return;

            console.log("OrderDetails", responsedata.data?.interimorder ?? []);
            interimOrderGlobal = responsedata.data?.interimorder ?? [];
            const case_status = responsedata.data?.pend_disp;
            const est_name_napix = responsedata.data?.establishment_name;
            const dist_name_napix = responsedata.data?.dist_name;
            const dist_code = responsedata.data?.district_code;
            const est_code = responsedata.data?.est_code;
            const responseDataOfCnr = responsedata.data;
            console.log("DATA------",responseDataOfCnr);
            

          setTimeout(() => window.scrollBy(0, 350), 200);
           btnText.textContent = "Search";
           btnSpinner.classList.add("hidden");
           const orderDetailsCount = Object.keys(interimOrderGlobal).length;
           populateTableDCOrderCopy(originalData,interimOrderGlobal,case_status,est_name_napix,dist_name_napix,dist_code,est_code,responseDataOfCnr);
        })

        })
        .catch(error => {
            console.warn(error);
        });

    })
    .catch(error => {
        console.error('Error validating CAPTCHA:', error);
    });

    function resetForm() {
        // Reset case and filing inputs
        document.getElementById('case-no-dc').value = '';
        document.getElementById('case-year-dc').value = '';
        document.getElementById('filling-no-dc').value = '';
        document.getElementById('filling-year-dc').value = '';

        // Reset district dropdown text and ensure all options are visible
        const dropdownToggle = document.getElementById("dropdownToggleDC");
        dropdownToggle.innerText = "Please Select District";
        document.getElementById("searchInputDC").value = ''; // Clear search filter
        filterOptionsDC(); // Re-show all district options
        // Optional: clear any stored value
        document.getElementById("dropdownToggleDC").dataset.value = "";

        // Reset establishment dropdown
        const establishmentSelect = document.getElementById("selectEstaDC");
        establishmentSelect.selectedIndex = 0;
        establishmentSelect.innerHTML = '<option value="">Select Establishment</option>';

        // Reset case type dropdown
        const caseTypeSelect = document.getElementById("caseTypeSelectForOrderJudgementFormDC");
        caseTypeSelect.selectedIndex = 0;

        // Reset radio buttons and toggle respective fields
        document.querySelector('input[value="case"]').checked = true;
        toggleFieldsDC(document.querySelector('input[name="search-type-case"]:checked'));

        // Reset captcha field
        document.getElementById('captcha-hc-orderJudgement').value = '';
        refreshCaptchaForOrderJudgement(); // call specific refresh for this captcha

        // Reset search button UI
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const searchBtn = document.getElementById('searchBtn');

        btnText.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
        searchBtn.disabled = false;
    }

    function populateTableDCOrderCopy(responseData, interimOrderGlobal,case_status,dist_name_napix,est_name_napix,dist_code,est_code,responseDataOfCnr) {
        const orderDetailsCount = Object.keys(interimOrderGlobal).length;

        // console.log("count2", orderDetailsCount);
        const case_type = responseData.case_type;
        const search_type = responseData.search_type;
        const case_number = responseDataOfCnr?.reg_no ?? 'N/A';
        const case_year = responseDataOfCnr?.reg_year ?? 'N/A';
        const filling_number = responseDataOfCnr?.fil_no ?? 'N/A';
        const filling_year = responseDataOfCnr?.fil_year ?? 'N/A';
        const orderDetailsDiv = document.getElementById("orderDetails");
        const tableBody = document.getElementById("orderTableBody");
        const caseErrElement = document.getElementById('case_err');

        tableBody.innerHTML = '';
        caseErrElement.classList.add('hidden');

        if (responseData && responseData.data && responseData.data.casenos) {
            const cases = responseData.data.casenos;

            let applyText = orderDetailsCount === 0 ? "Apply for Others Copy" : "Click Here";
            let applyText2 = orderDetailsCount === 0 ? "No Order Found" : "Apply Link";

            Object.keys(cases).forEach((key, index) => {
                const caseData = cases[key];

                const numberValue = search_type === 'case' ? (caseData.reg_no ?? 'N/A') : (caseData.fil_no ?? 'N/A');
                const yearValue = search_type === 'case' ? (caseData.reg_year ?? 'N/A') : (caseData.fil_year ?? 'N/A');

                const combinedCaseDetail = `${caseData.type_name ?? 'N/A'}/${numberValue}/${yearValue}`;

                const caseDataStr = JSON.stringify(caseData).replace(/"/g, '&quot;');
                const caseTypeStr = JSON.stringify(case_type).replace(/"/g, '&quot;');
                const interimOrderStr = JSON.stringify(interimOrderGlobal).replace(/"/g, '&quot;');

                const buttonHtml = orderDetailsCount === 0
                    ? `<button onclick="handleApplyForOthersDC()" class="p-[10px] bg-teal-600 sm:w-[250px] hover:bg-teal-700 text-white rounded-md uppercase -ml-2">${applyText}</button>`
                    : `<button 
                        onclick="handleSetPhpSession(this)" 
                        data-case='${caseDataStr}' 
                        data-type='${caseTypeStr}' 
                        data-interim='${interimOrderStr}' 
                        data-status='${case_status ?? ''}'
                        data-dist='${dist_name_napix ?? ''}'
                        data-est='${est_name_napix ?? ''}'
                        data-est-code='${est_code ?? ''}'
                        data-dist-code='${dist_code ?? ''}'
                        data-dist-caseno='${case_number}' 
                        data-dist-caseyear='${case_year}' 
                        data-dist-filno='${filling_number}' 
                        data-dist-filyear='${filling_year}'  
                        class="p-[10px] bg-teal-600 sm:w-[250px] hover:bg-teal-700 text-white rounded-md uppercase -ml-2">
                        ${applyText}
                    </button>`;

                tableBody.innerHTML += `
                    <tr class="border-b">
                        <td class="p-2 font-bold uppercase">Establishment</td>
                        <td class="p-2">${responseData.data.establishment_name ?? 'N/A'}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-2 font-bold uppercase">${search_type === 'case' ? 'Case Details' : 'Filing Details'}</td>
                        <td class="p-2">${combinedCaseDetail}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-2 font-bold uppercase">CIN Number</td>
                        <td class="p-2">${caseData.cino ?? 'N/A'}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-2 font-bold uppercase">Petitioner Name</td>
                        <td class="p-2">${caseData.pet_name ?? 'N/A'}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-2 font-bold uppercase">Respondent Name</td>
                        <td class="p-2">${caseData.res_name ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-bold uppercase">${applyText2}</td>
                        <td class="p-3">
                            ${buttonHtml}
                        </td>
                    </tr>
                `;
            });

            orderDetailsDiv.classList.remove("hidden");
            resetForm(); 
        } else {
            orderDetailsDiv.classList.add('hidden');
            caseErrElement.classList.remove('hidden');
            caseErrElement.innerHTML = 'No Cases found !!!';
            resetForm(); 
        }
    }

}
</script>
<!--Script for Civil Court when in order copy order is not available-->
<script>
    function handleApplyForOthersDC() {
    
    const orderDetailsDiv = document.getElementById("orderDetails");

    orderDetailsDiv.classList.add("hidden");

    var selectedOption = document.getElementById("civilCourtSelect").value = "applyOrdersDC";
    document.getElementById("orderJudgementFormDC").style.display = "none";
    document.getElementById("applyOrdersFormDC").style.display = "block";
}
</script> 

<!--This script is used for get case type on the basis of selected establishment in civil court other copy-->
<script>
 function saveEstCodeDcOtherCopy(selectElement) {
    const selectedEstCode = selectElement.value;

    if (selectedEstCode !== '') {
        sessionStorage.setItem('selectedEstCodeDC', selectedEstCode);
        // sessionStorage.setItem('selectedEstCodeDCOtherCopy', selectedEstCode);

        // Show loading spinner
        document.getElementById('loadingSpinnerOtherCopyDc').classList.remove('hidden');

        // Make AJAX call to fetch case types
        fetch('/get-dc-case-master', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ est_code: selectedEstCode })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            console.log('Case Types Response:', body);

            // Hide spinner regardless of result
            document.getElementById('loadingSpinnerOtherCopyDc').classList.add('hidden');

            if (!body.success) {
                alert(body.message);
                return;
            }

            const caseTypes = Array.isArray(body.data) ? body.data : Object.values(body.data);
            populateSelectDropdownDCOtherCopy(caseTypes);

        })
        .catch(error => {
            console.error('Error fetching case types:', error);
            alert('An error occurred while connecting to the server.');
            document.getElementById('loadingSpinnerOtherCopyDc').classList.add('hidden');
        });

    } else {
        sessionStorage.removeItem('selectedEstCodeDC');
    }
}

function populateSelectDropdownDCOtherCopy(caseTypes) {
    const selectElement = document.getElementById('caseTypeSelectForOyherCopyDC');

    if (!selectElement) {
        console.error('caseTypeSelectForOtherCopyFormDC element not found!');
        return;
    }

    // Clear existing options
    selectElement.innerHTML = '<option value="">Please Select Case Type</option>';

    caseTypes.forEach(caseType => {
        const optionElement = document.createElement('option');
        optionElement.value = caseType.case_type;
        optionElement.textContent = `${caseType.type_name}`;
        selectElement.appendChild(optionElement);
    });
  
    // Attach change event listener (to save selected case type in sessionStorage)
    selectElement.addEventListener('change', function() {
        const selectedCaseType = this.value;
        sessionStorage.setItem('selectedCaseTypeDCNapix', selectedCaseType);
        // console.log('Selected Case Type saved to sessionStorage:', selectedCaseType);
    });
}
</script>


@endpush
