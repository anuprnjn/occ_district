@extends('public_layouts.app')

@section('content')
<section class="content-section p-6 relative">
    <!-- Loading overlay -->
    <div id="loading-overlay" class="flex items-center justify-center z-10 h-screen bg-transparent">
        <p class="flex items-center justify-center gap-2 -mt-[200px]">
        <img class="w-[42px] animate-spin" src="{{ asset('passets/images/icons/refresh.png') }}" alt="Loading">
        <span class="text-gray-500 load text-lg">Loading...</span>
        </p>
    </div>
    
    <h2 id="application-title" class="sm:text-2xl text-center sm:text-start text-xl uppercase mb-6 font-semibold -mt-6"></h2>
    <div id="application-details-container" class="">
        <!-- Application data will appear here -->
    </div>
    
    <button id="print-button" class="hidden sm:mt-10 sm:mb-0 mb-16 mt-2 p-2 bg-red-600 hover:bg-red-700 pl-5 pr-5 text-white rounded" onclick="printApplication()">
        <div class="flex gap-2">
        <img src="{{ asset('passets/images/icons/print.svg')}}" alt="">Print Document
        </div>
    </button>
    <br>
    <div class="sm:mt-4 hidden -mt-10 mb-20 sm:mb-0" id="note">
    <span><strong >Note : </strong>Actual delivery of certified copy after making payment on intimation made by copying section ! <br>Payment can be done through <button onclick="detailsPayment()" class="text-blue-500 border-b border-blue-500 ml-1">Pending Payments</button>.</span>
    </div>
    
</section>
@endsection

@push('scripts')  
<script>

    function detailsPayment(application_number) {
        window.location.href = "{{ route('pendingPayments') }}?application_number=" + encodeURIComponent(application_number);
    }
        
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
            // Correct implementation of beforeunload for Chrome
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
                    console.log('applied data',data);
                    const container = document.getElementById('application-details-container');

                    const noteButton = document.querySelector('#note button');
                    noteButton.setAttribute('onclick', `detailsPayment('${data.application_number}')`);

                    // Set the dynamic title
                    const title = ` ${data.district_name || districtName} - ${data.establishment_name || establishmentName}`;
                    sessionStorage.setItem('district_name', data.district_name || 'District Name Not Available');
                    sessionStorage.setItem('establishment_name', data.establishment_name || 'Establishment Name Not Available');
                    document.getElementById('application-title').textContent = title;

                    let caseDetails = '';

                    if (data.selected_method === 'C') {
                        caseDetails = `
                            <tr class="border">
                                <td class="px-6 py-2.5 border text-sm sm:text-[1rem] font-semibold uppercase">Case Number</td>
                                <td class="px-6 py-2.5 border text-sm sm:text-[1rem]">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
                            </tr>
                        `;
                    } else if (data.selected_method === 'F') {
                        caseDetails = `
                            <tr class="border">
                                <td class="px-6 py-2.5 border text-sm sm:text-[1rem] font-semibold uppercase">Filling Number</td>
                                <td class="px-6 py-2.5 border text-sm sm:text-[1rem]">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
                            </tr>
                        `;
                    }

                    let paymentStatus = '';

                    if(data.payment_status === 0){
                        paymentStatus = ` 
                    <tr>
                        <td class="px-6 py-2.5 font-semibold uppercase border">Application Status</td>
                        <td class="px-6 py-2.5 border font-bold">
                        <span class="-ml-1.5 bg-green-100 text-green-700 font-semibold px-3 py-1 rounded-md border border-green-500 text-center">Applied Successfully</span>
                        </td>
                    </tr>`
                    }
                    

                    container.innerHTML = `
                        <table class="min-w-full overflow-hidden">
                            <thead>
                                <tr class="bg-[#D09A3F] text-white">
                                    <th class="px-6 py-2.5 text-left text-sm sm:text-lg uppercase">Details</th>
                                    <th class="px-6 py-2.5 text-left text-sm sm:text-lg uppercase">Information</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm sm:text-[1rem] dark_form">
                            ${paymentStatus}
                                <tr class="border-b">
                                    <td class="px-6 py-1 font-semibold uppercase border">Application Number</td>
                                    <td class="px-6 py-1 border text-teal-600 font-bold text-lg">${data.application_number}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Application Date</td>
                                    <td class="px-6 py-2.5 border">${formatDateTime(data.created_at)}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Applicant Name</td>
                                    <td class="px-6 py-2.5 border capitalize">${data.applicant_name}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Mobile Number</td>
                                    <td class="px-6 py-2.5 border">${data.mobile_number}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Email</td>
                                    <td class="px-6 py-2.5 border">${data.email}</td>
                                </tr>
                                ${caseDetails}
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Applied By</td>
                                    <td class="px-6 py-2.5 border capitalize">${data.applied_by}</td>
                                </tr>
                                
                                ${data.applied_by === 'advocate' ? `
                                    <tr class="border-b">
                                        <td class="px-6 py-2.5 font-semibold uppercase border">Advocate Registration Number</td>
                                        <td class="px-6 py-2.5 border">${data.advocate_registration_number || 'N/A'}</td>
                                    </tr>
                                ` : ''}
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Request Type</td>
                                    <td class="px-6 py-2.5 border capitalize">${data.request_mode}</td>
                                </tr>
                                
                                <tr class="border-b">
                                    <td class="px-6 py-2.5 font-semibold uppercase border">Required Document</td>
                                    <td class="px-6 py-2.5 border capitalize">${data.required_document}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                        
                    `;

                    // Show print button after data loads
                    document.getElementById('print-button').style.display = 'inline-block';
                    document.getElementById('note').style.display = 'inline-block';
                    
                } else {
                    document.getElementById('application-details-container').innerHTML = '<p class="text-center text-red-500">Failed to fetch application details.</p>';
                }

                document.getElementById('loading-overlay').style.display = 'none';
                // sessionStorage.removeItem('application_number');

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
                    font-family: Arial, sans-serif;
                    font-weight: bold;
                    border-left: 5px solid red;
                ">
                    <span style="font-size: 24px; color: red;">⚠️</span>
                    <span style="flex: 1; color: #333;font-size:14px;">Warning: Refreshing this page will redirect you to the website home page.</span>
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

            // Call this function when the application details page loads
            // showWarningMessage();
            } catch (error) {
                console.error('Error fetching application details:', error);
                document.getElementById('application-details-container').innerHTML = '<p class="text-center text-red-500">An error occurred while fetching application details.</p>';
                document.getElementById('loading-overlay').style.display = 'none';
            }
        }, 100);
    });

    function printApplication() {
    const districtName = sessionStorage.getItem('district_name') || "District Name Not Available";
    const establishmentName = sessionStorage.getItem('establishment_name') || "Establishment Name Not Available";
    const content = document.getElementById('application-details-container').innerHTML;
    const pageURL = window.location.href;

    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Civil Court - ${districtName}</title>
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
            <h1>Civil Court - ${districtName}</h1>
            <h2>${establishmentName}</h2>
            <h3>Online Certified Copy</h3>
            ${content}
            <footer>Generated by: ${pageURL}</footer>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>
<!-- <span><strong >Note : </strong>Actual delivery of certified copy after making payment on intimation made by copying section ! <br>Payment can be done through <a href="#" class="text-blue-500">Pending Payments</a>.</span> -->
  
@endpush