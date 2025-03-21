@extends('public_layouts.app')

@section('content')

<section class="content-section h-screen">
    <h3 class="font-semibold text-xl -mt-8">Pending Payment</h3>

    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-6" id='pendingPaymentForm'>
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
                <button type="submit" class="sm:w-[50%] w-[100%] btn-submit order_btn mt-4" onClick="pendingPayment(event)">Submit</button>
            </div>
        </div>
        <span id="error_span" class="text-red-500 font-bold text-sm ml-5 sm:ml-0"></span>
    </form>
    <div class="hidden loading">
        <span class="text-gray-400 text-lg animate-pulse">Checking details...</span>
    </div>
    <span class="hidden title_payment_success text-[#D09A3F] text-lg font-extrabold">Payment already done for the application number - &nbsp;<span class="title_success_payment text-teal-500"></span></span>
    <table class="w-full border border-gray-300 mt-4 success_payment_table hidden">
        <!-- Second row: Transaction Number | Transaction Date -->
        <tr>
            <td class="border p-2 font-bold">Applicant Name</td>
            <td class="border p-2 applicant-name"></td>
            <td class="border p-2 font-bold">Mobile Number</td>
            <td class="border p-2 mobile-number"></td>
        </tr>
        <tr>
            <td class="border p-2 font-bold">Transaction Number</td>
            <td class="border p-2 transaction-number"></td>
            <td class="border p-2 font-bold">Transaction Date</td>
            <td class="border p-2 transaction-date"></td>
        </tr>

        <!-- Third row: Paid Amount | Transaction Status -->
        <tr>
            <td class="border p-2 font-bold">Paid Amount</td>
            <td class="border p-2 paid-amount">
            <span class="bg-green-500 px-4 py-1 text-white rounded-md"></span>
            </td>
            <td class="border p-2 font-bold">Transaction Status</td>
            <td class="border p-2 transaction-status text-green-500"></td>
        </tr>
    </table>
</section>

@endsection

@push('scripts')
<script>
function pendingPayment(event) {
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
        pendingPaymentForm.reset();
        return;
    }

    // Store the application number and court in sessionStorage
    // sessionStorage.setItem('pending_payment_application_number', application_number);
    sessionStorage.setItem('selectedCourt', selectedCourt);

    // Make AJAX request based on selected court
    var url = selectedCourt === 'HC' ? '/fetch-pending-payments-hc' : '/fetch-pending-payments-dc';

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            application_number: application_number,
        },
        success: function(response) {
        if (response.success) {
            var caseInfoDetails = response;
            // console.log(caseInfoDetails);
            $.ajax({
                url: '/set-caseInformation-PendingData-HC', 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    caseInfoDetailsPendingPayHC: caseInfoDetails 
                },
                success: function(detailsResponse) {
                   if (detailsResponse) {
                    console.log(detailsResponse)
                        // Get necessary elements
                        const successTable = document.querySelector(".success_payment_table");
                        const titlePaymentSuccess = document.querySelector(".title_payment_success");
                        const loading = document.querySelector(".loading");
                        const applicationInput = document.querySelector("#application_number");
                        // Show loading initially
                        loading.classList.remove("hidden");
                        loading.classList.add("flex");

                        // Hide success elements initially
                        successTable.classList.add("hidden");
                        titlePaymentSuccess.classList.add("hidden");

                        const caseInfo = detailsResponse.session_data.PendingCaseInfoDetails.case_info;
                        const paymentStatus = caseInfo.payment_status;

                        if (paymentStatus === "0") {
                            applicationInput.value = "";
                            loading.classList.add("hidden");
                            // If payment is pending, redirect to the given location
                            window.location.href = detailsResponse.session_data.PendingCaseInfoDetails.location;
                        } else {
                            // Delay showing the success message and table by 1 second
                            setTimeout(() => {
                                // Hide loading animation
                                loading.classList.add("hidden");

                                // Show success message and table
                                successTable.classList.remove("hidden");
                                titlePaymentSuccess.classList.remove("hidden");

                                // Update content
                                document.querySelector(".title_success_payment").innerText = caseInfo.application_number;
                                document.querySelector(".success_payment_table .applicant-name").innerText = caseInfo.applicant_name;
                                document.querySelector(".success_payment_table .mobile-number").innerText = caseInfo.mobile_number;
                                document.querySelector(".success_payment_table .transaction-number").innerText = caseInfo.transaction_no;
                                document.querySelector(".success_payment_table .transaction-date").innerText = caseInfo.transaction_date;
                                document.querySelector(".success_payment_table .paid-amount span").innerText = `₹${caseInfo.payable_amount}`;
                                document.querySelector(".success_payment_table .transaction-status").innerText = caseInfo.transaction_status;
                            }, 1000);
                        }
                    } else {
                        alert(detailsResponse.message || "Error showing pending payment details HC");
                    }
                },
                error: function() {
                    console.log('An error occurred while setting pending Payment details.');
                }
            });
        } else {
            errorSpan.innerText = response.message || 'Failed to fetch application details.';
        }
    },
        error: function() {
            errorSpan.innerText = 'Application number not found.';
        }
    });
}
</script>   

@endpush