@extends('public_layouts.app')

@section('content')

<section class="content-section relative">
    <!-- Navigation to select pages -->
    <div class="radio-container mb-4">
        <label>
            <input type="radio" name="courtType" value="hcPage" checked onchange="loadContent('hcPage')"> High Court
        </label>
        <label class="ml-4">
            <input type="radio" name="courtType" value="dcPage" onchange="loadContent('dcPage')"> District Court
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
{{-- function for setting dist_code to esta_api  --}}
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
{{-- function for captcha  --}}
<script>
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
</script>
{{-- function for change the input based on applied by  --}}
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
{{-- function for changing the fields based on case no and filling no  --}}
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
{{-- Function to store the district code in sessionStorage --}}
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
{{-- function to get the case type  --}}
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
{{-- function for setting on submit  --}}
<script>
    function handleFormSubmit1(event) {
        event.preventDefault();

        // Collect captcha input
        const captcha = document.getElementById('captcha').value.trim();

        // Step 1: Validate CAPTCHA
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
            if (data.success) {
                // Proceed with form submission or other logic after successful CAPTCHA validation
                submitFormData();
            } else {
                alert('CAPTCHA validation failed. Please try again.');
                document.getElementById('captcha').value = '';
                refreshCaptcha(); 
                return;
            }
        })
        .catch(error => {
            console.error('CAPTCHA validation error:', error);
            alert('An error occurred while validating the CAPTCHA.');
        });

        // Collecting form data and session data
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

        // Validate required fields
        if (!district_code || !establishment_code || !case_type) {
            console.error('Missing required session data.');
            alert('Missing session data. Please select the required options.');
            return;
        }

        if (!applicant_name || !mobile_number || !email || !case_filling_number || !case_filling_year || !request_mode || !required_document || !applied_by) {
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
            return;
        }
    }

    // Function to submit the form data to the backend
    function submitFormData() {
        // Collect all form data for submission
        var confirmation = confirm('This is the final submit. Please check all your data. If you want to continue, press OK. Press Cancel to cancel the submission.');

        if (!confirmation) {
            console.log('Form submission canceled.');
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
                alert(`Application registered successfully! Application Number: ${data.application_number}`);
                console.log('Success:', data);
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

    console.log(
    "Case Type:", case_type,
    "Applicant Name:", applicant_name,
    "Mobile Number:", mobile_number,
    "Email:", email,
    "Case Filing Number:", case_filling_number,
    "Case Filing Year:", case_filling_year,
    "Request Mode:", request_mode,
    "Required Document:", required_document,
    "Applied By:", applied_by
);
   
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
            document.getElementById('captcha').value = '';  // Clear captcha input field
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
            window.location.href = '/hc-application-details';
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
@endpush
