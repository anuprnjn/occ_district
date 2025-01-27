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
        refresh.classList.add("animate-spin");
        captchaImg.src = '{{ captcha_src('math') }}?' + new Date().getTime(); 
        setTimeout(function() {
            refresh.classList.remove("animate-spin");
        }, 1000);
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
    if (selectedMode === "case_no") {
        field1Label.innerHTML = "Case No: <span class='red'>*</span>";
        field1Input.placeholder = "Enter Case No";
        field2Label.innerHTML = "Case Year: <span class='red'>*</span>";
        field2Input.placeholder = "Enter Case Year";
    } else if (selectedMode === "filling_no") {
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
    function handleFormSubmit(event) {
        event.preventDefault();

        // Collecting session and form data
        var district_code = sessionStorage.getItem('selectedDistCode');
        var establishment_code = sessionStorage.getItem('selectedEstCode');
        var applicant_name = document.getElementById('name').value;
        var mobile_number = document.getElementById('mobileInput').value;
        var email = document.getElementById('email').value;
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

        // Collect the data into an object to send to the backend
        var formData = {
            district_code: district_code,
            establishment_code: establishment_code,
            applicant_name: applicant_name,
            mobile_number: mobile_number,
            email: email,
            case_type: case_type,
            case_filling_number: case_filling_number,
            case_filling_year: case_filling_year,
            request_mode: request_mode,
            required_document: required_document,
            applied_by: applied_by,
            advocate_registration_number: advocate_registration,
            selected_method: selected_method,
        };

        console.log('Sending data:', formData);

        // Send the POST request to the Laravel backend
        fetch('/register-application', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify(formData),
        })
            .then((response) => {
                if (!response.ok) {
                    // If response is not successful, throw an error
                    return response.json().then((errorData) => {
                        throw new Error(errorData.message || 'An error occurred');
                    });
                }
                return response.json();
            })
            .then((data) => {
                // Check if the response indicates success
                if (data.success) {
                    alert(`Application registered successfully! Application Number: ${data.application_number}`);
                    console.log('Success:', data);
                } else {
                    alert('Failed to register application. Please try again.');
                    console.error('Error:', data.message);
                }
            })
            .catch((error) => {
                // Handle network or server errors
                console.error('Error:', error.message);
                alert(`Error: ${error.message}`);
            });
    }
</script> 

@endpush
