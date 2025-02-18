@extends('public_layouts.app')

@section('content')

<section class="content-section ">

    <div id="loading-overlay" class=" flex items-center justify-center z-10 h-screen bg-transparent">
        <p class="flex items-center justify-center gap-2 -mt-[200px]">
        <img class="w-[42px] animate-spin" src="{{ asset('passets/images/icons/refresh.png') }}" alt="Loading">
        <span class="text-gray-500 load text-lg">Loading...</span>
        </p>
    </div>

    <h2 id="application-status" class="text-rose-500 text-2xl font-semibold -mt-4 text-center sm:text-left md:text-left"></h2>
    <div id="application-details-section" class="shadow-md rounded-lg mt-10">
        <div id="application-details"></div>
        
    </div>
    <div id="print_container" class="hidden flex flex-col justify-start items-start">
    <button id="print-application-btn" class="mt-5 p-3 bg-rose-700 text-white rounded-lg mb-20 sm:mb-4 sm:mt-5">Print Application</button> <!-- Print button -->
    <div class="sm:mt-4 -mt-10 mb-20 sm:mb-0" id="note">
    <span><strong >Note : </strong>Actual delivery of certified copy after making payment on intimation made by copying section ! <br>Payment can be done through <a href="#" class="text-blue-500">Payment link</a>.</span>
    </div>
    </div>
</section>

@endsection

@push('scripts')

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

    if(application_number.startsWith('DC')) {
    sessionStorage.setItem('selectedCourt', 'DC');
    }else{
    sessionStorage.setItem('selectedCourt', 'HC');

    }
    if (application_number) {

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
    // console.log(data);
    // sessionStorage.removeItem('track_application_number');
    // sessionStorage.removeItem('selectedCourt');
        // Show a persistent warning message
        function showWarningMessage() {
        const warningMessage = document.createElement("div");
        warningMessage.innerHTML = `
            <div style="
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: #DAF7A6;
            padding: 15px;
            text-align: left;
            z-index: 1000;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            border-left: 5px solid red;
        ">
            <span style="font-size: 24px; color: red;">⚠️</span>
            <span style="flex: 1; color: #333;font-size:14px;">Warning: Refreshing this page will redirect you to the track status page.</span>
            <button id="dismissWarning" style="
                background: red;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                border-radius: 3px;
                font-weight: bold;
                transition: background 0.3s ease;
            ">X</button>
        </div>
        `;
        document.body.appendChild(warningMessage);

        // Add event listener to dismiss button
        document.getElementById("dismissWarning").addEventListener("click", function () {
            warningMessage.remove();
        });
    }
    showWarningMessage();
    document.getElementById('loading-overlay').style.display ='none';
    const print_btn_track = document.getElementById('print_container');
    print_btn_track.classList.remove('hidden');
    // Display the application status
    var applicationStatus = data.status ? `Application Status: ${data.status}` : 'Application Status: Pending';
    $('#application-status').text(applicationStatus);

    var detailsSection = $('#application-details');

    // Format the created_at time as dd-mm-yyyy hh:mm AM/PM
    var createdAt = new Date(data.created_at);
    var formattedCreatedAt = formatDateTime(createdAt);

    var establishmentRow = '';
    if (data.establishment_name) {
        establishmentRow = `
            <tr class="border-b">
                <td class="px-6 py-4 font-semibold uppercase">Establishment Name</td>
                <td class="px-6 py-4">${data.establishment_name}</td>
            </tr>
        `;
    }

    var caseFilingRow = '';
    var caseFilingYearRow = '';
    var caseNumberRow = '';
    var caseYearRow = '';

    if (data.selected_method === 'F') {
        caseFilingRow = `
            <tr class="border-b">
                <td class="px-6 py-4 font-semibold uppercase">Filling Number</td>
                <td class="px-6 py-4">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
            </tr>
        `;
        caseFilingYearRow = `
            <tr class="border-b">
                <td class="px-6 py-4 font-semibold uppercase">Filling Year</td>
                <td class="px-6 py-4">${data.case_filling_year}</td>
            </tr>
        `;
    } else if (data.selected_method === 'C') {
        caseNumberRow = `
            <tr class="border-b">
                <td class="px-6 py-4 font-semibold uppercase">Case Number</td>
                <td class="px-6 py-4">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
            </tr>
        `;
    }

    var districtNameRow = '';
    if (data.district_name) {
        districtNameRow = `
            <tr class="border-b">
                <td class="px-6 py-4 font-semibold uppercase">District Name</td>
                <td class="px-6 py-4">${data.district_name}</td>
            </tr>
        `;
    }
    var reqDocs = '';
    if (data.required_document) {
        reqDocs = `
              <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Required Document</td>
                    <td class="px-6 py-4">${data.required_document}</td>
                </tr>
        `;
    }

    detailsSection.html(`
        <table class="dark_form min-w-full bg-white rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-[#D09A3F] text-white">
                    <th class="px-6 py-3 text-left text-sm sm:text-lg">Request Details</th>
                    <th class="px-6 py-3 text-left text-sm sm:text-lg">Information</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Application Number</td>
                    <td class="px-6 py-4 text-teal-500 font-bold text-xl">${data.application_number}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Applicant Name</td>
                    <td class="px-6 py-4">${data.applicant_name}</td>
                </tr>
                 ${districtNameRow} 
                 ${establishmentRow}
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Mobile Number</td>
                    <td class="px-6 py-4">${data.mobile_number}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Email</td>
                    <td class="px-6 py-4">${data.email}</td>
                </tr>
                ${caseFilingRow}
                ${caseNumberRow}
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Request Mode</td>
                    <td class="px-6 py-4">${data.request_mode}</td>
                </tr>
               
                ${reqDocs}
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Applied By</td>
                    <td class="px-6 py-4">${data.applied_by}</td>
                </tr>
               ${data.applied_by === 'advocate' ? `
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Advocate Registration Number</td>
                        <td class="px-6 py-4">${data.advocate_registration_number || 'N/A'}</td>
                    </tr>
                ` : ''}
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Applied Date</td>
                    <td class="px-6 py-4">${formattedCreatedAt}</td>
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
            <span><strong >Note : </strong>Actual delivery of certified copy after making payment on intimation made by copying section ! <br>Payment can be done through <a href="#" class="text-blue-500">Payment link</a>.</span>
            <footer>Generated by: ${pageURL}</footer>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});
</script>

@endpush