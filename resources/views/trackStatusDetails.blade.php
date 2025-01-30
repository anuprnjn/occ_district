@extends('public_layouts.app')

@section('content')

<section class="content-section">
    <h3 class="font-semibold text-xl -mt-8">Application Details</h3>

    <!-- Displaying the application details -->
    <div id="application-details-section" class="mt-10">
        <div id="application-details"></div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Retrieve the application number from sessionStorage
    var application_number = sessionStorage.getItem('track_application_number');

    if (application_number) {
        // Make AJAX request to fetch the application details
        var selectedCourt = localStorage.getItem('selectedCourt');
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
    var detailsSection = $('#application-details');
    detailsSection.html(`
         <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#D09A3F] text-white">
                        <th class="px-6 py-3 text-left text-sm sm:text-lg">Detail</th>
                        <th class="px-6 py-3 text-left text-sm sm:text-lg">Information</th>
                    </tr>
                </thead>
                <tbody class="text-sm sm:text-lg dark_form">
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Application Number</td>
                        <td class="px-6 py-4">${data.application_number}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Application Date</td>
                        <td class="px-6 py-4">${formatDateTime(data.created_at)}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Applicant Name</td>
                        <td class="px-6 py-4">${data.applicant_name}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Mobile Number</td>
                        <td class="px-6 py-4">${data.mobile_number}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Email</td>
                        <td class="px-6 py-4">${data.email}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold">Applied By</td>
                        <td class="px-6 py-4">${data.applied_by}</td>
                    </tr>
                    ${data.applied_by === 'advocate' ? `
                        <tr class="border-b">
                            <td class="px-6 py-4 font-semibold uppercase">Advocate Registration Number</td>
                            <td class="px-6 py-4">${data.advocate_registration_number || 'N/A'}</td>
                        </tr>
                    ` : ''}
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Request Type</td>
                        <td class="px-6 py-4">${data.request_mode}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Case Type</td>
                        <td class="px-6 py-4">${data.case_type}</td>
                    </tr>
                    ${caseDetails}
                    <tr class="border-b">
                        <td class="px-6 py-4 font-semibold uppercase">Required Document</td>
                        <td class="px-6 py-4">${data.required_document}</td>
                    </tr>
                        <tr>
                        <td class="px-6 py-4 font-semibold uppercase">Payment status</td>
                        <td class="px-6 py-4 text-red-500">${data.status}</td>
                    </tr>
                </tbody>
            </table>
    `);
}
</script>   

@endpush