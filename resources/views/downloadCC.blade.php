@extends('public_layouts.app')

@section('content')

<section class="content-section h-[60vh]">
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
            <!-- Select City Dropdown -->
            <div class="form-field hidden sm:w-[50%] w-full" id="district_city_field">
                <label for="district_city">Select City: <span>*</span></label>
                <select id="district_city" name="district_city" class="w-full p-[11px]">
                    <option value="">-- Select District/City --</option>
                </select>
            </div>

            <!-- Application Number Field -->
            <div class="form-field sm:w-[50%] w-full" id="application_field">
                <label for="application_number">Application Number: <span>*</span></label>
                <input type="text" id="application_number" name="application_number" placeholder="Enter Application Number" class="w-full">
            </div>

            <!-- Submit Button -->
            <div class="form-field sm:w-[50%] w-full" id="button_field">
                <button type="submit" id="submit_button" 
                    class="w-full sm:w-[50%] btn-submit order_btn" 
                    onClick="trackApplication(event)">
                    Submit
                </button>
            </div>
        </div>

        <span id="error_span" class="text-red-500 font-bold text-sm ml-5 sm:ml-0 mt-2 block"></span>
    </form>
</section>

@endsection

@push('scripts')
<!-- script to toggle fields of DC and HC and to get district from db -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioButtons = document.querySelectorAll('input[name="search-type"]');
    const districtCityField = document.getElementById('district_city_field');
    const submitButton = document.getElementById('submit_button');
    const districtSelect = document.getElementById('district_city');
    const applicationInput = document.getElementById('application_number');

    function setButtonWidth(type) {
        if (type === 'DC') {
            districtCityField.classList.remove('hidden');
            // On larger screens, 70%
            submitButton.classList.remove('sm:w-[50%]');
            submitButton.classList.add('sm:w-[70%]');
        } else {
            districtCityField.classList.add('hidden');
            // On larger screens, 50%
            submitButton.classList.remove('sm:w-[70%]');
            submitButton.classList.add('sm:w-[50%]');
        }
    }

    function clearFields() {
        // Reset district dropdown to default option
        districtSelect.selectedIndex = 0;
        // Clear application number input
        applicationInput.value = '';
    }

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function () {
            setButtonWidth(this.value);
            clearFields();
        });
    });

    // Initial setup based on checked radio button
    setButtonWidth(document.querySelector('input[name="search-type"]:checked').value);

    // Load districts dynamically from backend (only needed once)
    function loadDistricts() {
        fetch('/get-districts')
            .then(response => response.json())
            .then(data => {
                // Clear previous options except placeholder
                districtSelect.innerHTML = '<option value="">-- Select District/City --</option>';
                data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.dist_name;
                    option.textContent = district.dist_name;
                    districtSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching districts:', error);
            });
    }

    loadDistricts();
});
</script>
@endpush
