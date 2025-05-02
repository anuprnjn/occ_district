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
    // function saveEstCode(selectElement) {
       
    //     var selectedEstCode = selectElement.value; // Get the selected establishment code
    //     if (selectedEstCode !== '') {
    //         sessionStorage.setItem('selectedEstCode', selectedEstCode);
    //     }
    //     else{
    //         sessionStorage.removeItem('selectedEstCode');
    //     }
    // }
   
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
    function getCaseType(element){
        var caseType = element.getAttribute('data-value');
        if(caseType !== ''){
            sessionStorage.setItem('selectedCaseType', caseType); 
        }else{
            sessionStorage.removeItem('selectedCaseType');
        }
    }
</script>    
<!-- {{-- function for submit application for Civil Court Other Copy --}} -->
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
    if (!district_code) {
    alert('Please select a district.');
    return;
    }
    if (!establishment_code) {
        alert('Please select an establishment.');
        return;
    }
    if (!case_type) {
        alert('Please select a case type.');
        return;
    }
    if (!applicant_name) {
        alert('Please enter the applicant name.');
        return;
    }
    if (!mobile_number) {
        alert('Please enter the mobile number.');
        return;
    }
    if (!email) {
        alert('Please enter the email address.');
        return;
    }
    if (!cnfemail) {
        alert('Please confirm the email address.');
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
    if (!case_filling_number) {
        alert('Please enter the case filling number.');
        return;
    }
    if (!case_filling_year) {
        alert('Please enter the case filling year.');
        return;
    }
    if (!request_mode) {
        alert('Please select the request mode.');
        return;
    }
    if (!required_document) {
        alert('Please select the required document.');
        return;
    }
    if (!applied_by) {
        alert('Please select who is applying (self or advocate).');
        return;
    }
    if (applied_by === 'advocate' && !advocate_registration) {
        alert('Please enter the advocate registration number.');
        return;
    }
    if (!captcha) {
        alert('Please evaluate the CAPTCHA.');
        return;
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

<!-- {{-- function for submit application for High Court Other Copy --}} -->
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

<!--Case Search For High court Order Copy--> 
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
            // console.log(data);

                // sessionStorage.setItem("caseInfo", JSON.stringify(data));
                // sessionStorage.setItem("responseData", JSON.stringify(data));
                fetch('/set-response-data', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ respData: data, urgent_fee: data.urgent_fee }) 
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to store data in session');
                    }
                    return response.json();
                })
                .then(data => console.log(data.message))  // Logs success message
                .catch(error => console.error('Error:', error));
                
                // sessionStorage.setItem('urgent_fee', data.urgent_fee);
                // fetch('/set-urgent-fee', {
                //     method: "POST",
                //     headers: {
                //         "Content-Type": "application/json",
                //         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                //     },
                //     body: JSON.stringify({ urgent_fee: data.urgent_fee}) 
                // })
                // .then(response => response.json())
                // .then(data => console.log('Data fetched'))
                // .catch(error => console.error('Error:', error));
                
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

<script>
        // Function to store the selected establishment code in sessionStorage and call napix api to get case type
    // function saveEstCode(selectElement) {
    //     var selectedEstCode = selectElement.value;

    //     if (selectedEstCode !== '') {
    //         sessionStorage.setItem('selectedEstCode', selectedEstCode);

    //         // Make an AJAX call to Laravel controller
    //         fetch('/get-dc-case-type-napix', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             },
    //             body: JSON.stringify({ est_code: selectedEstCode })
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log('Case Types Response:', data);  // Logs the entire response here
    //         })
    //         .catch(error => {
    //             console.error('Error fetching case types:', error);
    //         });

    //     } else {
    //         sessionStorage.removeItem('selectedEstCode');
    //     }
    // }
   

    function saveEstCode(selectElement) {
    var selectedEstCode = selectElement.value;

    if (selectedEstCode !== '') {
        sessionStorage.setItem('selectedEstCode', selectedEstCode);

        // Show loading spinner
        document.getElementById('loadingSpinner').classList.remove('hidden');

        // Make AJAX call
        fetch('/get-dc-case-type-napix', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ est_code: selectedEstCode })
        })
        .then(response => response.json()
            .then(data => ({ status: response.status, body: data }))) // combine HTTP status with JSON body
        .then(({ status, body }) => {
            console.log('Case Types Response:', body);

            if (!body.status) {
                if (body.message === 'Failed to fetch access token') {
                    alert('Failed to fetch access token. Try again.');
                } else if (body.message === 'Invalid response from NAPIX API') {
                    alert('Invalid response from NAPIX. Try refreshing the page.');
                } else {
                    alert('An unexpected error occurred. Please try again.');
                }

                // Hide loading spinner
                document.getElementById('loadingSpinner').classList.add('hidden');
                return;
            }

            var caseTypes = Object.values(body.data); // Convert object to array
            populateSelectDropdown(caseTypes);
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
<!--Case Search For Civil court Order Copy--> 
<script>

function setcaseDetailsToPhpSession(caseDataStr, caseType) {
    try {
        const caseDetails = JSON.parse(caseDataStr);
        caseDetails.case_type = caseType;

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
                console.log('Case data is set to the php session storage');
                window.location.href = data.redirectLocation;
            } else {
                console.log(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    } catch (err) {
        console.error("Error parsing case data:", err);
    }
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
            const cino = data.data.casenos.case1.cino;
            const originalData = data;
            console.log("CINO:", cino);
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
            console.log("DATA",responsedata);
            console.log("OrderDetails", responsedata.data?.interimorder ?? []);
            interimOrderGlobal = responsedata.data?.interimorder ?? [];


          setTimeout(() => window.scrollBy(0, 350), 200);
           btnText.textContent = "Search";
           btnSpinner.classList.add("hidden");
           console.log("error",interimOrderGlobal);
           const orderDetailsCount = Object.keys(interimOrderGlobal).length;
           populateTableDCOrderCopy(originalData,orderDetailsCount);
        })

        })
        .catch(error => {
            console.log(error);
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

function populateTableDCOrderCopy(responseData,orderDetailsCount) {
    console.log("count2",orderDetailsCount);
    const case_type = responseData.case_type;
    const search_type = responseData.search_type;
    const orderDetailsDiv = document.getElementById("orderDetails");
    const tableBody = document.getElementById("orderTableBody");
    const caseErrElement = document.getElementById('case_err');

    tableBody.innerHTML = '';
    caseErrElement.classList.add('hidden');

    if (responseData && responseData.data && responseData.data.casenos) {
        const cases = responseData.data.casenos;

        let applyText = orderDetailsCount === 0 ? "Apply for Others Copy" : "Click Here";
        let applyText2 = orderDetailsCount === 0 ? "No Order Found" : "Apply Link";

        let buttonAction = orderDetailsCount === 0
                                ? `handleApplyForOthersDC()`
                                : `handleApplyForOthersHavingOrdersDC()`;

        Object.keys(cases).forEach((key, index) => {
            const caseData = cases[key];

            // Determine labels and values based on search_type
            const numberLabel = search_type === 'case' ? 'Case Number' : 'Filing Number';
            const yearLabel = search_type === 'case' ? 'Case Year' : 'Filing Year';
            const numberValue = search_type === 'case' ? (caseData.reg_no ?? 'N/A') : (caseData.fil_no ?? 'N/A');
            const yearValue = search_type === 'case' ? (caseData.reg_year ?? 'N/A') : (caseData.fil_year ?? 'N/A');

            const combinedCaseDetail = `${caseData.type_name ?? 'N/A'}/${numberValue}/${yearValue}`;

            const caseDataStr = JSON.stringify(caseData).replace(/"/g, '&quot;');
            const caseTypeStr = case_type.replace(/"/g, '&quot;');

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
                                        <button onclick="${buttonAction}" class="p-[10px] bg-teal-600 sm:w-[250px] hover:bg-teal-700 text-white rounded-md uppercase">
                                            ${applyText}
                                        </button>
                                    </td>
                                </tr>
            `;
        });

        orderDetailsDiv.classList.remove("hidden");
        resetForm(); 
    } else {
        const title = document.getElementById('orderDetails');
        title.classList.add('hidden');
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

<!--Script for Civil Court when in order copy order is available-->
<script>
  function handleApplyForOthersHavingOrdersDC() {
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
    window.location.href = '/caseInformationDc';
  }
</script> 



@endpush
