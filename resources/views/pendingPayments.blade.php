@extends('public_layouts.app')

@section('content')

<section class="content-section h-screen">
    <h3 class="font-semibold text-xl -mt-8">Complete Pending Payment</h3>

    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-6" id='pendingPaymentForm'>
        <span id="error_span" class="text-red-500 font-bold text-sm ml-5 sm:ml-0"></span>
    </form>
    <div class="hidden loading">
        <span class="text-gray-400 text-lg animate-pulse">Checking details please wait...</span>
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
            <!-- <td class="border p-2 font-bold">Paid Amount</td>
            <td class="border p-2 paid-amount">
            <span class="text-green-600 font-extrabold"></span>
            </td> -->
            <td class="border p-2 font-bold">Transaction Status</td>
            <td class="border p-2 ">
                <span class="bg-green-500 text-white rounded-md px-4 py-1 transaction-status"></span>
            </td>
        </tr>
    </table>
    <div id="message" class="mt-2"></div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const applicationNumber = params.get('application_number');

    if (applicationNumber) {
       
        try {
            const decodedAppNo = atob(applicationNumber); // Decode base64
            pendingPayment(decodedAppNo)


        } catch (error) {
            console.error("Invalid base64 string in URL:", error);
        }
    }
});
</script>



<script>
function pendingPayment(decodedAppNo) {
    
    var application_number = decodedAppNo.toUpperCase();
    
    var errorSpan = document.getElementById('error_span');
    // Clear previous error message
    errorSpan.innerText = '';  

    // Check if the application number is empty
    if (application_number === '') {
        errorSpan.innerText = 'Please enter the application number!';
        return;
    }

    var loading = document.querySelector('.loading');
    loading.classList.remove('hidden');
    loading.classList.add('flex');
    

    // Make AJAX request based on selected court
    var url = application_number.startsWith('HC')  ? '/fetch-pending-payments-hc' : '/fetch-pending-payments-dc';

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
            if ('document_details' in caseInfoDetails) {
                var show = caseInfoDetails.document_details[0]?.amount;
                if(show === null){
                const messageSpan = `
                    <div class="dark_form flex items-start gap-2 p-4 rounded-xl border border-red-300 bg-red-50 text-red-500 shadow-sm">
                        <span class="font-semibold">
                            Documents are not uploaded by the copying section yet for the application number 
                            <span class="text-teal-500 font-bold ml-1">${application_number}</span>
                        </span>
                    </div>
                `;
                document.getElementById('message').innerHTML = messageSpan;
                return;
            }else{
                ajaxForPendingPayment(caseInfoDetails);
           }

            } else {
                console.log("else");
               if((response.case_info.payment_status === 0) || (response.case_info.deficit_status === 1 && response.case_info.deficit_payment_status ===0)) {
                ajaxForPendingPayment(caseInfoDetails);
               } else {
                const messageSpan = `
                    <div class="dark_form flex items-start gap-2 p-4 rounded-xl border border-red-300 bg-red-50 text-red-500 shadow-sm">
                        <span class="font-semibold">
                            Payment is already completed for the application number 
                            <span class="text-teal-500 font-bold ml-1">${application_number}</span>
                        </span>
                    </div>
                `;
                document.getElementById('message').innerHTML = messageSpan;
                return;
               }
            }
     
        } else {
            errorSpan.innerText = response.message || 'Failed to fetch application details.';
            var hide_message_span = document.getElementById("message");
            hide_message_span.classList.add("hidden");
        }
    },
        error: function() {
            errorSpan.innerText = 'Application number not found.';
            var hide_message_span = document.getElementById("message");
            hide_message_span.classList.add("hidden");
        }
    });
}
</script>   
<script>
    function ajaxForPendingPayment(caseInfoDetails){
        $.ajax({
                url: '/set-caseInformation-PendingData-HC', 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    caseInfoDetailsPendingPayHC: caseInfoDetails 
                },
                success: function(detailsResponse) {
                   if (detailsResponse) {
                    console.log('this is detailed response',detailsResponse)
                        // Get necessary elements
                        const successTable = document.querySelector(".success_payment_table");
                        const titlePaymentSuccess = document.querySelector(".title_payment_success");
                        const loading = document.querySelector(".loading");
                    
                        // Show loading initially
                        loading.classList.remove("hidden");
                        loading.classList.add("flex");

                        // Hide success elements initially
                        successTable.classList.add("hidden");
                        titlePaymentSuccess.classList.add("hidden");

                        const caseInfo = detailsResponse.session_data.PendingCaseInfoDetails.case_info;
                        const transactionInfo = detailsResponse.session_data.PendingCaseInfoDetails.transaction_details;    
                        const paymentStatus = caseInfo.payment_status;
                        const application_number = caseInfo.application_number;
                        const isDCOrderCopy = application_number.length >= 4 && application_number[3].toUpperCase() === 'W';

                        if (paymentStatus === "0" || paymentStatus === "3") {
                            // applicationInput.value = "";
                            loading.classList.add("hidden");
                            // If payment is pending, redirect to the given location
                            window.location.href = detailsResponse.session_data.PendingCaseInfoDetails.location;
                        } else {
                            if(caseInfo.application_number.startsWith("HCW") && paymentStatus === "1" && caseInfo.  deficit_status === "1" && caseInfo.deficit_payment_status === "0"){
                                window.location.href = detailsResponse.session_data.PendingCaseInfoDetails.location;
                                return;
                            } else {
                                if(isDCOrderCopy && paymentStatus === "1" && caseInfo.  deficit_status === "1" && caseInfo.deficit_payment_status === "0") {
                                    window.location.href = detailsResponse.session_data.PendingCaseInfoDetails.location;
                                return; 
                                }
                            }
                           
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
                            document.querySelector(".success_payment_table .transaction-number").innerText = transactionInfo.transaction_no;
                            document.querySelector(".success_payment_table .transaction-date").innerText = transactionInfo.transaction_date;
                            // document.querySelector(".success_payment_table .paid-amount span").innerText = `₹${transactionInfo.payable_amount}`;
                            document.querySelector(".success_payment_table .transaction-status").innerText = transactionInfo.transaction_status;
                        }, 1000);
                        }
                    } else {
                        alert(detailsResponse.message || "Error showing pending payment details HC");
                    }
                },
                error: function() {
                    console.log('An error occurred while setting pending Payment details.');
                    var hide_message_span = document.getElementById("message");
                    hide_message_span.classList.add("hidden");
                }
            });
    }
    </script>

@endpush