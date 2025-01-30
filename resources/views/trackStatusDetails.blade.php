@extends('public_layouts.app')

@section('content')

<section class="content-section">
    <!-- <h3 class="font-semibold text-xl -mt-8">Application Details</h3> -->

    <!-- Displaying the application status above the table -->
    <h2 id="application-status" class="text-red-500 text-2xl font-semibold -mt-4"></h2>

    <!-- Displaying the application details -->
    <div id="application-details-section" class="shadow-md rounded-lg mt-10">
        <div id="application-details"></div>
    </div>
</section>

@endsection

@push('scripts')

<script>
$(document).ready(function() {
    // Retrieve the application number from sessionStorage
    var application_number = sessionStorage.getItem('track_application_number');

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
        $('#application-details').html('<p class="text-red-500">No application number provided.</p>');
    }
});

function displayApplicationDetails(data) {
    // Log the full data to inspect its structure
    console.log(data);

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

    detailsSection.html(`
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Details</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Information</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Application Number</td>
                    <td class="px-6 py-4 text-teal-700 font-bold text-2xl">${data.application_number}</td>
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
               
                <tr class="border-b">
                    <td class="px-6 py-4 font-semibold uppercase">Required Document</td>
                    <td class="px-6 py-4">${data.required_document}</td>
                </tr>
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

// Function to format the date and time as dd-mm-yyyy hh:mm AM/PM
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
</script>

@endpush