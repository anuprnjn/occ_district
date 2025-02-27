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
            <img class="w-[42px] animate-spin" src="{{ asset('passets/images/icons/loading.png') }}" alt="Loading">
            <span class="text-gray-800 load text-lg">Loading please wait...</span>
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

    // Function to store the selected establishment code in sessionStorage
    function saveEstCode(selectElement) {
       
        var selectedEstCode = selectElement.value; // Get the selected establishment code
        if (selectedEstCode !== '') {
            sessionStorage.setItem('selectedEstCode', selectedEstCode);
        }
        else{
            sessionStorage.removeItem('selectedEstCode');
        }
    }
   
</script>
<!-- {{-- function for captcha  --}} -->
<!-- <script>
    function refreshCaptcha() {
        const refresh = document.querySelector(".refresh-btn");
        const captchaImg = document.getElementById('captchaImage');
        
        // Add animation class to the refresh button
        refresh.classList.add("animate-spin");

        // Make an AJAX request to the route that generates the CAPTCHA
        fetch('/refresh-captcha')
            .then(response => response.json())
            .then(data => {
                // Update the CAPTCHA image with the new source URL
                captchaImg.src = data.captcha_src + '?' + new Date().getTime(); 
            })
            .catch(error => {
                console.error('Error refreshing CAPTCHA:', error);
            })
            .finally(() => {
                // Remove the spin animation after the request
                setTimeout(function() {
                    refresh.classList.remove("animate-spin");
                }, 1000);
            });
    }
</script> -->
<script>
    function refreshCaptcha() {
    const refreshBtn = document.querySelector(".refresh-btn img"); // Refresh icon
    const captchaImg = document.getElementById('captchaImage');

    // Add spinning animation
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


<!-- {{-- function for captcha for order judgement --}} -->
<!-- <script>
    function refreshCaptchaForOrderJudgement() {
        const refresh = document.querySelector(".refresh-btn-orderJudgement");
        const captchaImg = document.getElementById('captchaImageOrderJudgement');
        
        // Add animation class to the refresh button
        refresh.classList.add("animate-spin");

        // Make an AJAX request to the route that generates the CAPTCHA
        fetch('/refresh-captcha')
            .then(response => response.json())
            .then(data => {
                // Update the CAPTCHA image with the new source URL
                captchaImg.src = data.captcha_src + '?' + new Date().getTime(); 
            })
            .catch(error => {
                console.error('Error refreshing CAPTCHA:', error);
            })
            .finally(() => {
                // Remove the spin animation after the request
                setTimeout(function() {
                    refresh.classList.remove("animate-spin");
                }, 1000);
            });
    }
</script> -->
<!-- <script>
    function refreshCaptchaForOrderJudgement() {
     fetch('/refresh-captcha')
            .then(response => response.json())
            .then(data => {
                document.getElementById('captchaImageOrderJudgement').src = data.captcha_src;
            })
            .catch(error => console.error('Error refreshing CAPTCHA:', error));
        }
 </script> -->
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
    // Get the selected radio button value
    var selectedMode = document.querySelector('input[name="select_mode"]:checked').value;

    // Get references to the labels and input fields
    var field1Label = document.getElementById("field1-label");
    var field1Input = document.getElementById("case-no");
    var field2Label = document.getElementById("field2-label");
    var field2Input = document.getElementById("case-year");

    // Update the labels and placeholders based on the selected mode
    if (selectedMode === "C") {
        field1Label.innerHTML = "Case No: <span class='red'>*</span>";
        field1Input.placeholder = "Enter Case No";
        field2Label.innerHTML = "Case Year: <span class='red'>*</span>";
        field2Input.placeholder = "Enter Case Year";
    } else if (selectedMode === "F") {
        field1Label.innerHTML = "Filing No: <span class='red'>*</span>";
        field1Input.placeholder = "Enter Filing No";
        field2Label.innerHTML = "Filing Year: <span class='required'>*</span>";
        field2Input.placeholder = "Enter Filing Year";
    }
    }
    window.onload = updateFields;
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
<!-- {{-- function to get the case type  --}} -->
<script>
    function getCaseType(element){
        var caseType = element.getAttribute('data-value');
        if(caseType !== ''){
            sessionStorage.setItem('selectedCaseType', caseType); 
        }else{
            sessionStorage.removeItem('selectedCaseType');
        }
    }
</script>    
<!-- {{-- function for setting on submit  --}} -->
<script>
    function handleFormSubmit(event) {
    event.preventDefault();

    // Collect form data
    var district_code = sessionStorage.getItem('selectedDistCode');
    var establishment_code = sessionStorage.getItem('selectedEstCode');
    var applicant_name = document.getElementById('name').value;
    var mobile_number = document.getElementById('mobileInput').value;
    var email = document.getElementById('email').value;
    const cnfemail = document.getElementById('confirm-email').value.trim();
    var case_type = sessionStorage.getItem('selectedCaseType');
    var case_filling_number = document.getElementById('case-no').value;
    var case_filling_year = document.getElementById('case-year').value;
    var request_mode = document.querySelector('input[name="request_mode"]:checked')?.value;
    var required_document = document.getElementById('required-document').value;
    var applied_by = document.getElementById('apply-by').value;
    var advocate_registration = document.getElementById('adv_res').value;
    const selected_method = document.querySelector('input[name="select_mode"]:checked')?.value;
    const captcha = document.getElementById('captcha').value.trim();

    // Validate required fields
    if (!district_code || !establishment_code || !case_type) {
        console.error('Missing required session data.');
        alert('Missing session data. Please select the required options.');
        return;
    }

    if (!applicant_name || !mobile_number || !email || !case_filling_number || !case_filling_year || !request_mode || !required_document || !applied_by ) {
        console.error('Missing required form data.');
        alert('Please fill out all required fields.');
        return;
    }
    if (applied_by === 'advocate' && !advocate_registration) {
        alert('Please enter the advocate registration number.');
        return false;
    }

    if (email !== cnfemail) {
        alert('Email and Confirm Email do not match.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    if (!captcha) {
        alert('Please evaluate the CAPTCHA.');
        return;  // Stop the process if CAPTCHA is not filled
    }
    fetch('/validate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',  // Ensure the response is expected in JSON format
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            captcha: captcha,
        }),
    })
    .then(response => response.json()) // Ensure you're parsing the JSON response
    .then(data => {
        if (!data.success) {
            alert('CAPTCHA validation failed. Please try again.');
            document.getElementById('captcha').value = '';  // Clear captcha input field
            refreshCaptcha(); // Optional: refresh captcha image
            return;  // Stop further validation if CAPTCHA fails
        }
        submitFormData();
    })
    .catch(error => {
        console.error('CAPTCHA validation error:', error);
        alert('An error occurred while validating the CAPTCHA.');
    });
}

// Function to submit the form data to the backend
function submitFormData() {
    var confirmation = confirm('This is the final submit. Please check all your data. If you want to continue, press OK. Press Cancel to cancel the submission.');

    if (!confirmation) {
        refreshCaptcha();
        document.getElementById('captcha').value = '';
        return;  // Exit the function if the user cancels
    }

    var formData = {
        district_code: sessionStorage.getItem('selectedDistCode'),
        establishment_code: sessionStorage.getItem('selectedEstCode'),
        applicant_name: document.getElementById('name').value,
        mobile_number: document.getElementById('mobileInput').value,
        email: document.getElementById('email').value,
        case_type: sessionStorage.getItem('selectedCaseType'),
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
            document.getElementById('applyOrdersForm').reset();
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


<script>
    function handleFormSubmitForHighCourt(event) {
    event.preventDefault();

    // Collect form data
    var applicant_name = document.getElementById('name').value;
    var mobile_number = document.getElementById('mobileInput').value;
    var email = document.getElementById('email').value;
    const cnfemail = document.getElementById('confirm-email').value.trim();
    var case_type = sessionStorage.getItem('selectedCaseType');
    var case_filling_number = document.getElementById('case-no-hc').value;
    var case_filling_year = document.getElementById('case-year-hc').value;
    var request_mode = document.querySelector('input[name="request_mode"]:checked')?.value;
    var required_document = document.getElementById('required-document').value;
    var applied_by = document.getElementById('apply-by').value;
    var advocate_registration = document.getElementById('adv_res').value;
    const selected_method = document.querySelector('input[name="select_mode"]:checked')?.value;
    const captcha = document.getElementById('captcha-hc').value.trim();

    if (!case_type || !applicant_name || !mobile_number || !email || !case_filling_number || !case_filling_year || !request_mode || !required_document || !applied_by) {
        console.error('Missing required form data.');
        alert('Please fill out all required fields.');
        return;
    }

    if (email !== cnfemail) {
        alert('Email and Confirm Email do not match.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    if (!captcha) {
        alert('Please evaluate the CAPTCHA.');
        return;  // Stop the process if CAPTCHA is not filled
    }
    fetch('/validate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',  // Ensure the response is expected in JSON format
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            captcha: captcha,
        }),
    })
    .then(response => response.json()) // Ensure you're parsing the JSON response
    .then(data => {
        if (!data.success) {
            alert('CAPTCHA validation failed. Please try again.');
            document.getElementById('captcha-hc').value = '';  // Clear captcha input field
            refreshCaptcha(); // Optional: refresh captcha image
            return;  // Stop further validation if CAPTCHA fails
        }
        submitHcFormData();
    })
    .catch(error => {
        console.error('CAPTCHA validation error:', error);
        alert('An error occurred while validating the CAPTCHA.');
    });
}

// Function to submit the form data to the backend
function submitHcFormData() {
    var confirmation = confirm('This is the final submit. Please check all your data. If you want to continue, press OK. Press Cancel to cancel the submission.');

    if (!confirmation) {
        refreshCaptcha();
        document.getElementById('captcha').value = '';
        return;  // Exit the function if the user cancels
    }

    var formData = {
        applicant_name: document.getElementById('name').value,
        mobile_number: document.getElementById('mobileInput').value,
        email: document.getElementById('email').value,
        case_type: sessionStorage.getItem('selectedCaseType'),
        case_filling_number: document.getElementById('case-no-hc').value,
        case_filling_year: document.getElementById('case-year-hc').value,
        request_mode: document.querySelector('input[name="request_mode"]:checked')?.value,
        required_document: document.getElementById('required-document').value,
        applied_by: document.getElementById('apply-by').value,
        advocate_registration_number: document.getElementById('adv_res').value,
        selected_method: document.querySelector('input[name="select_mode"]:checked')?.value,
    };

    // Send the form data to the server
    fetch('/hc-register-application', {
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
            document.getElementById('applyOrdersFormHC').reset();
            const mobileLabel = document.getElementById("mobileLabel");
            mobileLabel.innerHTML = 'Mobile Number : <span class="text-red-500">*</span>';
            mobileLabel.classList.remove("text-green-500");
            mobileInput.placeholder = "Enter mobile number";
            window.location.href = '/hc-application-details';
           

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
<script>
function closeModal(){
    var application_modal = document.getElementById('application_n_details');
    application_modal.classList.add("hidden");
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
function submitJudgementForm(event) {
    event.preventDefault(); 

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

    // Step 5: Now that all validations are passed, validate CAPTCHA via API.
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

        fetch('/fetch-judgement-data', {
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

                // sessionStorage.setItem("caseInfo", JSON.stringify(data));
                sessionStorage.setItem("responseData", JSON.stringify(data));
                // sessionStorage.setItem('urgent_fee', data.urgent_fee);
                fetch('/set-urgent-fee', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ urgent_fee: data.urgent_fee}) 
                })
                .then(response => response.json())
                .then(data => console.log(data.message, "Stored Fee:", data.urgent_fee))
                .catch(error => console.error('Error:', error));
                // const loadingOverlay = document.getElementById("loadingOverlay"); // Get loading div

                // Show loading overlay
                // loadingOverlay.classList.remove("hidden");
               
                // window.scrollBy(0, 500);
                setTimeout(() => window.scrollBy(0, 350), 200);

                // Remove selected case type from session storage
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

                // Get all list items and reset their visibility
                const optionItems = caseTypeOptions.querySelectorAll('li');
                optionItems.forEach(item => {
                    item.classList.remove('bg-gray-100'); // Remove any highlight
                    item.style.display = 'block'; // Ensure all items are visible
                });

                // Set default highlight (if needed)
                if (optionItems.length > 0) {
                    optionItems[0].classList.add('bg-gray-100');
                }
            

                function populateTable(responseData, count_data) {
                    const orderDetailsDiv = document.getElementById("orderDetails");
                    const tableBody = document.getElementById("orderTableBody");
                    const caseErrElement = document.getElementById('case_err');

                    // Clear previous table data
                    tableBody.innerHTML = "";

                    if (responseData.cases && responseData.cases.length > 0) {
                        responseData.cases.forEach((caseData, index) => {
                            caseErrElement.classList.add('hidden');

                            let applyText = count_data === 0 ? "Apply for Others Copy" : "Click Here";
                            let applyText2 = count_data === 0 ? "No Order Found" : "Apply Link";

                            let buttonAction = count_data === 0
                                ? `handleApplyForOthers()`
                                : `handleApplyForOthersHavingOrders(${index})`;

                            tableBody.innerHTML += `
                               
                                <tr class="border-b">
                                    <td class="p-3 font-bold uppercase">Filling Number</td>
                                    <td class="p-3">${caseData?.fillingno || 'N/A'}</td>
                                </tr>
                                 <tr class="border-b">
                                    <td class="p-3 font-bold uppercase">Case Number</td>
                                    <td class="p-3">${caseData?.caseno || 'N/A'}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="p-3 font-bold uppercase">CIN Number</td>
                                    <td class="p-3">${caseData.cino}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="p-3 font-bold uppercase">Petitioner Name</td>
                                    <td class="p-3">${caseData.pet_name}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="p-3 font-bold uppercase">Respondent Name</td>
                                    <td class="p-3">${caseData.res_name}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="p-3 font-bold uppercase">Case Status</td>
                                    <td class="p-3">${caseData.casestatus}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-bold uppercase">${applyText2}</td>
                                    <td class="p-3">
                                        <button onclick="${buttonAction}" class="p-[10px] bg-teal-600 sm:w-[250px] hover:bg-teal-700 text-white rounded-md uppercase">
                                            ${applyText}
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        resetButton();
                        orderDetailsDiv.classList.remove("hidden");
                    } else {
                        
                            caseErrElement.classList.remove('hidden');
                            caseErrElement. innerHTML = 'No Cases found !!!';
                            resetButton();
                    }
                   
                    function resetButton() {
                        searchBtn.disabled = false;
                        btnText.textContent = "Search";
                        btnSpinner.classList.add("hidden");
                    }
                    // setTimeout(() => {
                    //     loadingOverlay.classList.add("hidden");
                    // }, 2000);
                }

                // Fetch response data and populate table
                const responseData = data;
                // sessionStorage.setItem('urgent_fee',data.urgent_fee);
                const count_data = data.order_count;

                populateTable(responseData, count_data);
            })
        .catch(error => {
            const tableBody = document.getElementById("orderTableBody");
            tableBody.innerHTML += `
                <tr class="border-b">
                    <td class="p-3 font-bold uppercase text-red-500">No Data found !!!</td>
                </tr>`
            sessionStorage.removeItem('selectedHcCaseType');
            refreshCaptchaForOrderJudgement();
            document.getElementById('captcha-hc-orderJudgement').value = '';
            console.error('Error fetching judgement data:', error);
        });
    })
    .catch(error => {
        alert('An error occurred while validating the CAPTCHA.');
    });
}
</script>
<script>
    function handleApplyForOthers() {
    const orderDetailsDiv = document.getElementById("orderDetails");

    orderDetailsDiv.classList.add("hidden");

    var selectedOption = document.getElementById("highCourtSelect").value = "applyOrders";
    document.getElementById("orderJudgementForm").style.display = "none";
    document.getElementById("otherForm").style.display = "block";
}
</script>  
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


@endpush
