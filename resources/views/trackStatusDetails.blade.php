@extends('public_layouts.app')

@section('content')

<section class="content-section ">

    <div id="loading-overlay" class=" flex items-center justify-center z-10 h-screen bg-transparent">
        <p class="flex items-center justify-center gap-2 -mt-[200px]">
        <img class="w-[42px] animate-spin" src="{{ asset('passets/images/icons/refresh.png') }}" alt="Loading">
        <span class="text-gray-500 load text-lg">Loading...</span>
        </p>
    </div>

    <!-- <h2 id="application-status" class="uppercase text-2xl font-semibold -mt-4 text-center sm:text-left md:text-left px-4 py-2 bg-slate-100/70"></h2> -->
    <div id="application-details-section" class="-mt-4">
        <div id="application-details"></div>
    </div>
    <div id="print_container" class="hidden flex flex-col justify-start items-start">
    <button id="print-application-btn" class="flex gap-2 mt-5 p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg mb-20 sm:mb-4 sm:mt-5"><img src="{{ asset('passets/images/icons/print.svg')}}" alt="">Print Application</button> <!-- Print button -->
    <div class="sm:mt-4 -mt-10 mb-20 sm:mb-0" id="note">
    <span><strong >Note : </strong>Actual delivery of certified copy after making payment on intimation made by copying section ! <br>Payment can be done through <button onclick="detailsPayment()" class="text-blue-500 border-b border-blue-500 ml-1">Pending Payments</button>.</span>
    </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function detailsPayment(application_number) {
        window.location.href = "{{ route('pendingPayments') }}?application_number=" + encodeURIComponent(application_number);
    }
</script>    

<script>
    $(document).ready(function() {
        function getQueryParam(param) {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }
        var url_application_number = getQueryParam('application_number');
        console.log(application_number);
        // Retrieve the application number from sessionStorage
        var application_number = sessionStorage.getItem('track_application_number') || url_application_number;

        if (application_number) {
            // continue with AJAX logic
            if(application_number.startsWith('HC')) {
                sessionStorage.setItem('selectedCourt', 'HC');
            }else{
                sessionStorage.setItem('selectedCourt', 'DC');
            }
            // Make AJAX request to fetch the application details
            var selectedCourt = sessionStorage.getItem('selectedCourt');
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
                        var app_no = response.data[0].application_number;
                        const noteButton = document.querySelector('#note button');
                        noteButton.setAttribute('onclick', `detailsPayment('${app_no}')`);

                        displayApplicationDetails(response.data[0]);
                    } else {
                        $('#application-details').html('<p class="text-red-500">No details found for this application number.</p>');
                    }
                },
                error: function() {
                    $('#application-details').html('<p class="text-red-500">An error occurred while fetching the application details.</p>');
                }
            });
        } else {
            window.location.href = '/trackStatus';
            return;
        }
    });

    function displayApplicationDetails(data) {
        // Log the full data to inspect its structure
        // console.log('data',data);
        sessionStorage.removeItem('track_application_number');
        sessionStorage.removeItem('selectedCourt');
            // Show a persistent warning message
function showWarningMessage() {
    const toast = document.createElement("div");
    toast.innerHTML = `
        <div style="
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #fff3cd;
            color: #856404;
            padding: 12px 14px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Segoe UI', sans-serif;
            font-size: 14px;
            min-width: 320px;
            max-width: 400px;
            animation: slideIn 0.5s ease-out;
            z-index: 1000;
        ">
            <span style="font-size: 20px;">⚠️</span>
            <div style="flex: 1;">
                Refreshing this page will redirect you to the track status page.
                <div id="countdown" style="margin-top: 4px; font-size: 12px; color: #666;"></div>
            </div>
            <button id="dismissWarning" style="
                background: transparent;
                border: none;
                font-size: 20px;
                font-weight: bold;
                color: #856404;
                cursor: pointer;
            " title="Close">&times;</button>
        </div>
    `;
    document.body.appendChild(toast);

    const countdownSpan = toast.querySelector('#countdown');
    let countdown = 10;
    countdownSpan.textContent = `(Auto-hide in ${countdown}s)`;

    const intervalId = setInterval(() => {
        countdown--;
        countdownSpan.textContent = `(Auto-hide in ${countdown}s)`;
        if (countdown <= 0) {
            clearInterval(intervalId);
            toast.remove();
        }
    }, 1000);

    document.getElementById("dismissWarning").addEventListener("click", () => {
        clearInterval(intervalId);
        toast.remove();
    });
}

// Add this to your CSS <style> or in <head> for animation:
const style = document.createElement('style');
style.innerHTML = `
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}`;
document.head.appendChild(style);
     showWarningMessage();
        document.getElementById('loading-overlay').style.display ='none';
        const print_btn_track = document.getElementById('print_container');
        print_btn_track.classList.remove('hidden');
        // Display the application status
        var applicationStatus = data.application_status ? `Application Status - ( ${data.application_status} )` : '';
        var statusElement = $('#application-status');

        // Define status colors
        var statusColors = {
            "Rejected": "text-red-500",
            "Certified copy is ready to be download": "text-teal-500",
            "Payment Success": "text-lime-500",
            "Document Uploaded": "text-yellow-500",
            "In Progress": "text-green-500"
        };

        // Remove any previous color classes
        statusElement.removeClass("text-red-500 text-teal-500 text-lime-500 text-yellow-500 text-green-500");

        // Set text and color
        if (data.application_status) {
            statusElement.text(applicationStatus);
            var statusColor = statusColors[data.application_status] || "text-gray-500"; // Default color if status not matched
            statusElement.addClass(statusColor);
        } else {
            statusElement.text('');
        }
        var detailsSection = $('#application-details');

        // Format the created_at time as dd-mm-yyyy hh:mm AM/PM
        var createdAt = new Date(data.created_at);
        var formattedCreatedAt = formatDateTime(createdAt);

        var applicationStatus = data.application_status ? data.application_status : 'N/A';

        var applicationStatusRow = `
            <tr class="border">
                <td class="px-6 py-2 font-semibold uppercase border">Application Status</td>
                <td class="px-6 py-2 uppercase">${applicationStatus}</td>
            </tr>
        `;

        var establishmentRow = data.establishment_name ? `
            <tr class="border">
                <td class="px-6 py-2 font-semibold uppercase border">Establishment Name</td>
                <td class="px-6 py-2">${data.establishment_name}</td>
            </tr>
        ` : '';

        var districtNameRow = data.district_name ? `
            <tr class="border">
                <td class="px-6 py-2 font-semibold uppercase border">District Name</td>
                <td class="px-6 py-2">${data.district_name}</td>
            </tr>
        ` : '';

        var reqDocs = data.required_document ? `
            <tr class="border">
                <td class="px-6 py-2 font-semibold uppercase border">Required Document</td>
                <td class="px-6 py-2 capitalize">${data.required_document}</td>
            </tr>
        ` : '';

        // ✅ Improved logic for handling both types of responses
        var caseDetails = '';

        if (data.selected_method === 'F') {
            caseDetails = `
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Filing Number</td>
                    <td class="px-6 py-2">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
                </tr>
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Filing Year</td>
                    <td class="px-6 py-2">${data.case_filling_year}</td>
                </tr>
            `;
        } else if (data.selected_method === 'C') {
            caseDetails = `
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Case Number</td>
                    <td class="px-6 py-2">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
                </tr>
            `;
        } else if (data.case_number && data.case_year) {
            caseDetails = `
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Case Number</td>
                    <td class="px-6 py-2">${data.case_type || ''}/${data.case_number}/${data.case_year}</td>
                </tr>
            `;
        }

        detailsSection.html(`
            <table class="dark_form min-w-full overflow-hidden">
                <thead>
                    <tr class="bg-[#D09A3F] text-white">
                        <th class="px-6 py-2 text-left text-sm sm:text-lg uppercase border-t border-b border-l">Request Details</th>
                        <th class="px-6 py-2 text-left text-sm sm:text-lg uppercase border-t border-b border-r">Request Information</th>
                    </tr>
                </thead>
                <tbody>
                    ${applicationStatusRow}
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Application Number</td>
                        <td class="px-6 py-2 text-teal-500 font-bold text-lg">${data.application_number}</td>
                    </tr>
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Applicant Name</td>
                        <td class="px-6 py-2 capitalize">${data.applicant_name}</td>
                    </tr>
                    ${districtNameRow}
                    ${establishmentRow}
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Mobile Number</td>
                        <td class="px-6 py-2">${data.mobile_number}</td>
                    </tr>
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Email</td>
                        <td class="px-6 py-2">${data.email}</td>
                    </tr>
                    ${caseDetails}
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Request Mode</td>
                        <td class="px-6 py-2 capitalize">${data.request_mode}</td>
                    </tr>
                    ${reqDocs}
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Applied By</td>
                        <td class="px-6 py-2 capitalize">${data.applied_by}</td>
                    </tr>
                    ${data.applied_by === 'advocate' ? `
                        <tr class="border">
                            <td class="px-6 py-2 font-semibold uppercase border">Advocate Registration Number</td>
                            <td class="px-6 py-2">${data.advocate_registration_number || 'N/A'}</td>
                        </tr>
                    ` : ''}
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Applied Date</td>
                        <td class="px-6 py-2">${formattedCreatedAt}</td>
                    </tr>
                </tbody>
            </table>
        `);
   
    }

    function formatDateTime(date) {
        var day = ("0" + date.getDate()).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var year = date.getFullYear();
        var hours = date.getHours();
        var minutes = ("0" + date.getMinutes()).slice(-2);
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + ampm;
    }
    $('#print-application-btn').click(function() {
        const content = document.getElementById('application-details-section').innerHTML;
        const pageURL = window.location.href;

        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <html>
            <head>
            
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 20px;
                    }
                    h1 {
                        text-align: center;
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                    h2 {
                        text-align: center;
                        font-size: 20px;
                        font-weight: bold;
                        margin-bottom: 5px;
                    }
                    h3 {
                        text-align: center;
                        font-size: 18px;
                        margin-bottom: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }
                    th, td {
                        padding: 12px;
                        text-align: left;
                        border: 1px solid #000;
                    }
                    th {
                        background-color: #D09A3F;
                        color: black;  
                        font-size: 16px;
                        font-weight: bold;
                    }
                    td {
                        font-size: 14px;
                        font-weight: normal;
                        color: #000;
                    }
                    td:first-child {
                        width: 30%;  
                    }
                    td:nth-child(2) {
                        width: 70%;  
                    }
                    footer {
                        position: fixed;
                        bottom: 20px;
                        left: 20px;
                        font-size: 12px;
                        color: #555;
                    }
                    @media print {
                        @page {
                            margin: 0;
                        }
                        body {
                            margin: 0;
                            padding: 20px;
                        }
                        footer {
                            display: block;
                        }
                    }
                </style>
            </head>
            <body>
                <h3>Online Certified Copy</h3>
                ${content}
               
                <footer>Generated by: ${pageURL}</footer>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });
</script>
<!-- <span><strong >Note : </strong>Actual delivery of certified copy after making payment on intimation made by copying section ! <br>Payment can be done through <a href="#" class="text-blue-500">Payment link</a>.</span> -->
@endpush