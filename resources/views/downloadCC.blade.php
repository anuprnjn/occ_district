@extends('public_layouts.app')

@section('content')

<section class="content-section h-full">
    <h3 class="font-semibold text-xl -mt-8">Download Certified Copy</h3>

    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-8" id='trackApplicationForm'>
        <div class="form-group mb-4">
            <label>
                <input type="radio" name="search-type" value="HC" checked>
                High Court
            </label>
            <label class="ml-4">
                <input type="radio" name="search-type" value="DC" class="-ml-4">
                Civil Court
            </label>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-end items-stretch sm:gap-4 gap-4 w-full" id="input-wrapper">
            <!-- Application Number Field -->
            <div class="form-field sm:w-[50%] w-full" id="application_field">
                <label for="application_number">Application Number: <span>*</span></label>
                <input type="text" id="application_number" name="application_number" placeholder="Enter Application Number" class="w-full">
            </div>

            <!-- Select City Dropdown -->
            <div class="form-field hidden sm:w-[50%] w-full" id="district_city_field">
                <label for="district_city">Select City: <span>*</span></label>
                <select id="district_city" name="district_city" class="w-full p-[11px]">
                    <option value="">-- Please Select District --</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-field sm:w-[50%] w-full" id="button_field">
                <button type="button" id="submit_button" class="w-full sm:w-[50%] btn-submit order_btn" onclick="openMobileModal()">
                    Search
                </button>
            </div>
        </div>

        <span id="error_span" class="text-red-500 font-bold text-sm ml-5 sm:ml-0 mt-2 block"></span>
    </form>
</section>

<!-- MODAL -->
<div id="mobileModal" class="fixed z-50 inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-md w-[100%] sm:w-[400px] relative">
        <h2 class="text-lg font-bold mb-4">Verify Mobile Number</h2>

        <!-- Mobile Input & Timer -->
        <div class="flex items-start justify-start gap-2 mb-2">
            <label for="mobileInput" id="mobileLabel">Mobile No: <span>*</span></label>
            <span id="otpTimer" class="sm:text-md text-sm text-rose-600"></span>
        </div>

        <!-- Mobile Input Field + Send OTP -->
        <div class="flex items-center justify-center gap-2 mb-4">
            <input
                type="text"
                id="mobileInput"
                name="mobile"
                placeholder="Enter Your Mobile No"
                class="p-[10px] border border-gray-300 rounded w-full"
                required
                maxlength="10"
                onkeydown="return isNumber(event)"
            >
            <button
                type="button"
                id="otpButton"
                value="HC"
                onclick="sendOtp(value)"
                class="bg-[#4B3E2F] w-[150px] sm:w-[200px] p-[8px] sm:p-[10px] rounded-md text-white hover:bg-[#D09A3F]"
            >
                Send OTP
            </button>
        </div>

        <!-- Modal Actions -->
        <div class="mt-8 flex justify-end space-x-3">
            <button
                onclick="closeMobileModal()"
                class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
            >Cancel</button>

            <button
                onclick="verifyOtpAndSubmit()"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >Submit</button>
        </div>
        <div class="mt-6 flex justify-center items-center flex-col gap-2">
            <h6 id="mobile_number_show" class="text-sm text-red-500 text-center">The OTP has been sent to registered mobile number</h6>
            <h6 class="text-sm text-teal-600">{MobileNumber}</h6>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[name="search-type"]');
        const districtCityField = document.getElementById('district_city_field');
        const submitButton = document.getElementById('submit_button');
        const districtSelect = document.getElementById('district_city');
        const applicationInput = document.getElementById('application_number');
        const errorSpan = document.getElementById('error_span');

        function setButtonWidth(type) {
            if (type === 'DC') {
                districtCityField.classList.remove('hidden');
                submitButton.classList.remove('sm:w-[50%]');
                submitButton.classList.add('sm:w-[70%]');
            } else {
                districtCityField.classList.add('hidden');
                submitButton.classList.remove('sm:w-[70%]');
                submitButton.classList.add('sm:w-[50%]');
            }
        }

        function clearFields() {
            districtSelect.selectedIndex = 0;
            applicationInput.value = '';
        }

        radioButtons.forEach(radio => {
            radio.addEventListener('change', function () {
                setButtonWidth(this.value);
                clearFields();
                errorSpan.textContent = '';
            });
        });

        setButtonWidth(document.querySelector('input[name="search-type"]:checked').value);

        function loadDistricts() {
            fetch('/get-districts')
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">-- Please Select District --</option>';
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.dist_code;
                        option.textContent = district.dist_name;
                        districtSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    alert('Error fetching districts');
                    console.error(error);
                });
        }

        loadDistricts();
    });

    function isNumber(e) {
        return e.key >= '0' && e.key <= '9';
    }

    // from this function getting the application details and document details 
    
function openMobileModal() {
    // Display the modal
    const modal = document.getElementById('mobileModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const selectedCourtType = document.querySelector('input[name="search-type"]:checked').value;
    const applicationNumber = document.getElementById('application_number').value.trim();
    const districtSelect = document.getElementById('district_city');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const errorSpan = document.getElementById('error_span');
    errorSpan.textContent = '';

    if (!applicationNumber) {
        alert('Please enter the application number');
        return;
    }

    const payload = {
        court_type: selectedCourtType,
        application_number: applicationNumber,
    };

    if (selectedCourtType === 'DC') {
        payload.district_code = districtSelect.value;
        payload.district_name = districtSelect.options[districtSelect.selectedIndex].text;
    }

    const endpoint = selectedCourtType === 'HC'
        ? '/certified-copy/high-court'
        : '/certified-copy/civil-court';

    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(payload),
    })
    .then(res => res.json())
    .then(data => {
        if (data.status !== 'success' || !data.data?.applicant_details?.mobile_number) {
            alert('No data found or invalid response');
            return;
        }

        const fullMobile = data.data.applicant_details.mobile_number;

        // Set mobile number in the hidden input inside the modal
        const mobileInput = document.getElementById('mobileInput');
        if (mobileInput) {
            mobileInput.value = fullMobile;
        }

        // Auto-call sendOtp with court type
        sendOtp(selectedCourtType);

    })
    .catch(err => {
        console.error('Fetch Error:', err);
        alert('Something went wrong while fetching application details.');
    });
}




    function closeMobileModal() {
        document.getElementById('mobileModal').classList.remove('flex');
        document.getElementById('mobileModal').classList.add('hidden');
    }

    function verifyOtpAndSubmit() {

    }
</script>
@endpush
