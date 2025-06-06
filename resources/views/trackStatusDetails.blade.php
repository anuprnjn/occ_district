@extends('public_layouts.app')

@section('content')

<section class="content-section ">
   
    <div class="-mt-10 -mb-12 sm:-mt-20 flex sm:justify-end justify-center items-center sm:mb-4">
        <button id="print-application-btn" class="flex gap-2 p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg mb-20 sm:mb-4 sm:mt-5"><img src="{{ asset('passets/images/icons/print.svg')}}" alt="">Print Application</button>
    </div>

    <div id="loading-overlay" class=" flex items-center justify-center z-10 h-screen">
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
    <div class="flex items-center justify-center gap-3">

        <button id="pay-now-button" class="hidden flex gap-1 mt-5 p-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg mb-20 sm:mb-4 sm:mt-5"><img src="{{ asset('passets/images/icons/rupees.svg')}}" alt="">Click to Pay Now</button>

       <button id="download-cc" class="hidden flex gap-2 mt-5 p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg mb-4">
            <img src="{{ asset('passets/images/icons/download.svg')}}" alt="">Download Certified Copy
        </button>

    </div>
    <div id="cc-download-result" class="mt-2 w-full mb-5">
    <!-- Table will be injected here -->
    </div>
        <span id="note_span" class="hidden">
        <strong >Note : </strong>Actual delivery of certified copy is available after making payment on intimation made by copying section !
    </span>
    </div>
    </div>
</section>

@endsection

@push('scripts')

<script>
    function paymentPending(applicationNo) {
        const encodedAppNo = btoa(applicationNo); 
        window.location.href = "{{ route('pendingPayments') }}?application_number=" + encodeURIComponent(encodedAppNo);
    }
    
    function downloadAllDocuments(encodedDocs) {
        try {
            const docs = JSON.parse(decodeURIComponent(encodedDocs));
            docs.forEach(doc => {
                if (Array.isArray(doc.certified_copy_links)) {
                    doc.certified_copy_links.forEach(link => {
                        if (link) {
                            const a = document.createElement("a");
                            a.href = link;
                            a.download = "";
                            a.target = "_blank";
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        }
                    });
                }
            });
        } catch (e) {
            console.error("Error in downloadAllDocuments:", e);
            alert("Failed to download all files.");
        }
    }

    // function downloadAsZip(applicationNo) {
    //     const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    //     fetch('/certified-copy/download-zip', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': csrfToken
    //         },
    //         body: JSON.stringify({ application_number: applicationNo })
    //     })
    //     .then(response => response.blob())
    //     .then(blob => {
    //         const url = window.URL.createObjectURL(blob);
    //         const a = document.createElement('a');
    //         a.href = url;
    //         a.download = `${applicationNo}.zip`;
    //         document.body.appendChild(a);
    //         a.click();
    //         document.body.removeChild(a);
    //         window.URL.revokeObjectURL(url);
    //     })
    //     .catch(error => {
    //         console.error("ZIP Download error:", error);
    //         alert("Failed to download ZIP file.");
    //     });
    // }
    
    function downloadCC(applicationNo) {
        const button = document.getElementById("download-cc");
        const isHighCourt = applicationNo.startsWith("HC") || applicationNo.startsWith("HCW");
        const route = isHighCourt 
            ? '/certified-copy/high-court' 
            : '/certified-copy/civil-court';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        button.innerHTML = `<span class="animate-spin border-2 border-white border-t-transparent rounded-full w-5 h-5"></span> Loading...`;
        button.disabled = true;
        button.style.cursor = "not-allowed";
        button.classList.add("opacity-60");


        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ application_number: applicationNo })
        })
        .then(res => res.json())
        .then(data => {
            button.innerHTML = `<img src="/passets/images/icons/download.svg" alt="">Download Certified Copy`;
            button.disabled = false;

            const resultContainer = document.getElementById("cc-download-result");
            resultContainer.innerHTML = "";

            if (data.status === "success") {
                const docs = data.data.document_details || [];

                if (docs.length === 0) {
                    resultContainer.innerHTML = `<p class="text-sm text-red-600 mt-2">No documents found.</p>`;
                    return;
                }

                const isOrderCopy = applicationNo.startsWith("HCW");

                // Optional buttons (only for multiple documents)
                let extraButtons = '';
                if (docs.length > 1) {
                    const encodedDocs = encodeURIComponent(JSON.stringify(docs));
                    extraButtons = `
                    <div class="mb-4 w-full flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end items-stretch sm:items-center sm:-mt-16 mt-0">
                            <button onclick="downloadAllDocuments('${encodedDocs}')" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm sm:text-[16px] w-full sm:w-auto">
                                <img src="/passets/images/icons/download.svg" alt="Download All" class="w-5 h-5">
                                Download All Files
                            </button>
                            <button onclick="" class="flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm sm:text-[16px] w-full sm:w-auto">
                                <img src="/passets/images/icons/zip.svg" alt="Download ZIP" class="w-5 h-5">
                                Download as ZIP
                            </button>
                        </div>
                    `;
                }

                let tableHTML = `
                    ${extraButtons}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 text-sm text-left">
                            <thead class="bg-[#D09A3F] text-md uppercase text-white">
                                <tr>
                                    <th class="px-2 py-2 border-b font-semibold text-md">S.No.</th>
                `;

                if (isOrderCopy) {
                    tableHTML += `
                        <th class="px-2 py-1 border-b font-semibold text-md">Order No</th>
                        <th class="px-2 py-1 border-b font-semibold text-md">Order Date</th>
                        <th class="px-2 py-1 border-b font-semibold text-md">No of Pages</th>
                    `;
                } else {
                    tableHTML += `
                        <th class="px-2 py-1 border-b font-semibold text-md">Required Document</th>
                        <th class="px-2 py-1 border-b font-semibold text-md">No. of Pages</th>
                    `;
                }

                tableHTML += `
                    <th class="px-2 py-1 border-b font-semibold text-md">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                docs.forEach((doc, index) => {
                    const downloadLink = doc.certified_copy_links?.[0] || "#";

                    tableHTML += `
                        <tr>
                            <td class="px-2 py-1 border-b text-md">${index + 1}</td>
                    `;

                    if (isOrderCopy) {
                        tableHTML += `
                            <td class="px-2 py-1 border-b text-md border">${doc.order_number}</td>
                            <td class="px-2 py-1 border-b text-md border">${doc.order_date}</td>
                            <td class="px-2 py-1 border-b text-md border">${doc.new_page_no}</td>
                        `;
                    } else {
                        tableHTML += `
                            <td class="px-2 py-1 border-b text-md border">${doc.document_type}</td>
                            <td class="px-2 py-1 border-b text-md border">${doc.number_of_page}</td>
                        `;
                    }

                    tableHTML += `
                            <td class="px-2 py-1 border-b text-md">
                                <a href="${downloadLink}" download class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-xs">
                                    <img src="/passets/images/icons/download.svg" alt="Download" class="w-4 h-4">
                                </a>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `
                            </tbody>
                        </table>
                    </div>
                `;

                resultContainer.innerHTML = tableHTML;

            } else {
                resultContainer.innerHTML = `<p class="text-sm text-red-600 mt-2">Error: ${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            button.innerHTML = `<img src="/passets/images/icons/download.svg" alt="">Download Certified Copy`;
            button.disabled = false;
            document.getElementById("cc-download-result").innerHTML = `<p class="text-sm text-red-600 mt-2">An error occurred. Please try again.</p>`;
        });
    }

</script> 

<script>
    $(document).ready(function() {
        // function getQueryParam(param) {
        //     let urlParams = new URLSearchParams(window.location.search);
        //     return urlParams.get(param);
        // }
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            const encoded = urlParams.get(param);
            if (encoded) {
                try {
                    const decoded = atob(encoded); // Base64 decode
                    return decoded;
                } catch (e) {
                    console.error('Failed to decode:', e);
                    return null;
                }
            }
            return null;
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
                        // noteButton.setAttribute('onclick', `detailsPayment('${app_no}')`);

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
                Refreshing this page might redirect you to the Sign-In page.
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

        var applicationStatus = data.application_status ? data.application_status.trim() : 'N/A';
       
        var application_no = data.application_number || 'N/A';

        const status = applicationStatus.toLowerCase();

        if (status === "payment pending" || status === "deficit payment pending") {
            const payBtn = document.getElementById("pay-now-button");
            const note_span = document.getElementById("note_span");
            note_span.classList.remove("hidden");
            payBtn.classList.remove("hidden");
            payBtn.setAttribute("onclick", `paymentPending('${application_no}')`);
        }

        if (status === "certified copy is ready to be download") {
            const downloadBtn = document.getElementById("download-cc");
            downloadBtn.classList.remove("hidden");
            downloadBtn.setAttribute("onclick", `downloadCC('${application_no}')`);
        }
        // condition ends here 

        var applicationStatusRow = `
            <tr class="border">
                <td class="px-6 py-2 font-semibold uppercase border tracking-wide">Application Status</td>
                <td class="px-6 py-2 uppercase tracking-wide">${applicationStatus}</td>
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
        <div class="overflow-x-auto">
            <table class="dark_form min-w-full border border-gray-300 text-md text-left">
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
        </div>
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
                            display: none;
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

@endpush
