@extends('public_layouts.app')

@section('content')
<section class="content-section p-6 relative">
    <!-- Loading overlay -->
    <div id="loading-overlay" class="flex items-center justify-center z-10 h-screen bg-transparent">
        <p class="text-gray-500 text-xl -mt-[200px]">Loading application details...</p>
    </div>
    
    <h1 id="application-title" class="sm:text-3xl text-center sm:text-start text-xl uppercase mb-6 font-semibold"></h1>
    <div id="application-details-container" class=" border-b border-gray-300 shadow-md rounded-lg">
        <!-- Application data will appear here -->
    </div>
    
    <button id="print-button" class="hidden sm:mt-10 sm:mb-0 mb-16 mt-4 p-2 bg-rose-500 pl-5 pr-5 text-white rounded" onclick="printApplication()">Print Document</button>
</section>
@endsection

@push('scripts')
<script>
    // Format the date and time to "YYYY-MM-DD HH:MM:SS"
    const formatDateTime = (dateString) => {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        let hours = date.getHours();
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? String(hours).padStart(2, '0') : '12';
        const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;

        return `${year}-${month}-${day} ${timeString}`;
    };

    document.addEventListener('DOMContentLoaded', async function () {
        const applicationNumber = sessionStorage.getItem('application_number');
        const districtName = sessionStorage.getItem('district_name');
        const establishmentName = sessionStorage.getItem('establishment_name');
        
        if (!applicationNumber) {
            window.location.href = '/'; 
            return;
        }
        
        document.getElementById('loading-overlay').style.display = 'flex'; // Show loading overlay

        setTimeout(async () => {
            try {
                const response = await fetch("{{ route('fetch_application_details') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ application_number: applicationNumber })
                });

                const result = await response.json();

                if (result.success) {
                
                    const data = result.data[0]; 
                    const container = document.getElementById('application-details-container');

                    // Set the dynamic title
                    const title = ` ${data.district_name || districtName} - ${data.establishment_name || establishmentName}`;
                    sessionStorage.setItem('district_name', data.district_name || 'District Name Not Available');
                    sessionStorage.setItem('establishment_name', data.establishment_name || 'Establishment Name Not Available');
                    document.getElementById('application-title').textContent = title;

                    let caseDetails = '';

                    if (data.selected_method === 'C') {
                        caseDetails = `
                            <tr class="border-b">
                                <td class="px-6 py-3 text-sm sm:text-lg font-semibold text-gray-700">Case Year</td>
                                <td class="px-6 py-3 text-sm sm:text-lg text-gray-900">${data.case_filling_year}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-6 py-3 text-sm sm:text-lg font-semibold text-gray-700">Case Number</td>
                                <td class="px-6 py-3 text-sm sm:text-lg text-gray-900">${data.case_filling_number}</td>
                            </tr>
                        `;
                    } else if (data.selected_method === 'F') {
                        caseDetails = `
                            <tr class="border-b">
                                <td class="px-6 py-3 text-sm sm:text-lg font-semibold text-gray-700">Filling Year</td>
                                <td class="px-6 py-3 text-sm sm:text-lg text-gray-900">${data.case_filling_year}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-6 py-3 text-sm sm:text-lg font-semibold text-gray-700">Filling Number</td>
                                <td class="px-6 py-3 text-sm sm:text-lg text-gray-900">${data.case_filling_number}</td>
                            </tr>
                        `;
                    }

                    container.innerHTML = `
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-[#D09A3F] text-white">
                                    <th class="px-6 py-3 text-left text-sm sm:text-lg">Detail</th>
                                    <th class="px-6 py-3 text-left text-sm sm:text-lg">Information</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm sm:text-lg font-light text-gray-700">
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Application Number</td>
                                    <td class="px-6 py-4">${data.application_number}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Application Date</td>
                                    <td class="px-6 py-4">${formatDateTime(data.created_at)}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Applicant Name</td>
                                    <td class="px-6 py-4">${data.applicant_name}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Mobile Number</td>
                                    <td class="px-6 py-4">${data.mobile_number}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Email</td>
                                    <td class="px-6 py-4">${data.email}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Applied By</td>
                                    <td class="px-6 py-4">${data.applied_by}</td>
                                </tr>
                                ${data.applied_by === 'advocate' ? `
                                    <tr class="border-b">
                                        <td class="px-6 py-4 font-medium">Advocate Registration Number</td>
                                        <td class="px-6 py-4">${data.advocate_registration_number || 'N/A'}</td>
                                    </tr>
                                ` : ''}
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Request Type</td>
                                    <td class="px-6 py-4">${data.request_mode}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium">Case Type</td>
                                    <td class="px-6 py-4">${data.case_type}</td>
                                </tr>
                                ${caseDetails}
                                <tr>
                                    <td class="px-6 py-4 font-medium">Required Document</td>
                                    <td class="px-6 py-4">${data.required_document}</td>
                                </tr>
                            </tbody>
                        </table>
                    `;

                    // Show print button after data loads
                    document.getElementById('print-button').style.display = 'inline-block';
                } else {
                    document.getElementById('application-details-container').innerHTML = '<p class="text-center text-red-500">Failed to fetch application details.</p>';
                }

                document.getElementById('loading-overlay').style.display = 'none';
                sessionStorage.removeItem('application_number');
            } catch (error) {
                console.error('Error fetching application details:', error);
                document.getElementById('application-details-container').innerHTML = '<p class="text-center text-red-500">An error occurred while fetching application details.</p>';
                document.getElementById('loading-overlay').style.display = 'none';
            }
        }, 2000);
    });

    function printApplication() {
        const districtName = sessionStorage.getItem('district_name');
        const establishmentName = sessionStorage.getItem('establishment_name');
        const content = document.getElementById('application-details-container').innerHTML;
        const printWindow = window.open('', '', 'width=800,height=600');
        
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Application Details</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
                    th { background-color: #D09A3F; color: white; }
                </style>
            </head>
            <body>
                <h1>Online Certified Copy - ${districtName} - ${establishmentName}</h1>
                ${content}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
    }
</script>

  
@endpush