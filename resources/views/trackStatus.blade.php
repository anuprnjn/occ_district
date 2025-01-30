@extends('public_layouts.app')

@section('content')

<section class="content-section h-[60vh]">
    <h3 class="font-semibold text-xl -mt-8">Track Application Status</h3>

    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10" id='trackApplicationForm'>
        <div class="form-group">
            <label>
                <input type="radio" name="search-type" value="HC" checked>
                High Court
            </label>
            <label>
                <input type="radio" name="search-type" value="DC">
                Civil Court
            </label>
        </div>
        <div class="flex justify-center sm:flex-row flex-col items-center sm:gap-10">
            <div class="form-field">
                <label for="application_number">Application Number: <span>*</span></label>
                <input type="text" id="application_number" name="application_number" placeholder="Enter Application Number" class="sm:mb-5">
            </div>    
            <div class="form-field">
                <button type="submit" class="sm:w-[50%] w-[100%] btn-submit order_btn mt-4" onClick="trackApplication(event)">Submit</button>
            </div>
        </div>
        <span id="error_span" class="text-red-500 font-bold text-sm ml-5 sm:ml-0"></span>
    </form>
</section>

@endsection

@push('scripts')
<script>
function trackApplication(event) {
    event.preventDefault();
    var applicationNumberInput = document.getElementById('application_number');
    var application_number = applicationNumberInput.value.trim().toUpperCase();
    var errorSpan = document.getElementById('error_span');
    var selectedCourt = document.querySelector('input[name="search-type"]:checked').value;

    // Clear previous error message
    errorSpan.innerText = '';  

    // Check if the application number is empty
    if (application_number === '') {
        errorSpan.innerText = 'Please enter the application number!';
        return;
    }

    // Check if the selected court matches the application number prefix
    if ((selectedCourt === 'HC' && application_number.startsWith('DC')) || 
        (selectedCourt === 'DC' && application_number.startsWith('HC'))) {
        errorSpan.innerText = 'Selected court and application number do not match!';
        trackApplicationForm.reset();
        return;
    }

    // Store the application number and court in sessionStorage
    sessionStorage.setItem('track_application_number', application_number);
    sessionStorage.setItem('selectedCourt', selectedCourt);

    // Make AJAX request based on selected court
    var url = selectedCourt === 'HC' ? '/fetch-hc-application-details' : '/fetch-application-details';

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            application_number: application_number,
        },
        success: function(response) {
            if (response.success) {
                // Redirect to the details page
                window.location.href = '/trackStatusDetails';
            } else {
                errorSpan.innerText = response.message || 'Failed to fetch application details.';
            }
        },
        error: function() {
            errorSpan.innerText = 'An error occurred while fetching the application details.';
        }
    });
}
</script>   

@endpush