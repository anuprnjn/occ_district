@extends('public_layouts.app')

@section('content')

<section class="content-section ">
    <div class="-mt-10 -mb-12 sm:-mt-20 flex sm:justify-end justify-center items-center sm:mb-4 gap-4">
    @php
        $hasHC = session()->has('trackDetailsMobileHC');
        $hasDC = session()->has('trackDetailsMobileDC');
    @endphp
    <button
        onclick="window.location.href='{{ $hasHC ? '/trackStatusMobileHC' : '/trackStatusMobileDC' }}'"
        class="flex gap-2 pl-2 pr-4 p-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg mb-20 sm:mb-4 sm:mt-5">
        <img src="{{ asset('passets/images/icons/back.svg') }}" alt="">
        Back
    </button>
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
    <div id="cc-download-result" class="mt-2 w-full mb-16 sm:mb-5">
    <!-- Table will be injected here -->
    </div>
        <span id="note_span" class="hidden sm:mt-0 am:mb-0 -mt-20 mb-16">
        <strong >Note : </strong>Actual delivery of certified copy is available after making payment on intimation made by copying section !
    </span>
    </div>
    </div>
</section>

@endsection

@push('scripts')


<script>
    
function paymentPending(application_number) {
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
            ajaxForPendingPayment(caseInfoDetails);
        } else {
            alert(response.error || 'Failed to fetch application details !');
        }
        },
        error: function() {
            alert('Application Number not found');
            return;
        }
    });
    }
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
                        const caseInfo = detailsResponse.session_data.PendingCaseInfoDetails.case_info;
                        const transactionInfo = detailsResponse.session_data.PendingCaseInfoDetails.transaction_details;    
                        const paymentStatus = caseInfo.payment_status;
                        const application_number = caseInfo.application_number;
                        const isDCOrderCopy = application_number.length >= 4 && application_number[3].toUpperCase() === 'W';

                        if (paymentStatus === "0" || paymentStatus === "3") {
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
                        }
                    } else {
                        alert(detailsResponse.message || "Error showing pending payment details HC");
                        return;
                    }
                },
                error: function() {
                    alert("An error occurred while setting pending Payment details.");
                    return;
                }
            });
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

    function downloadAsZip(applicationNo) {
        alert(applicationNo);
        return;
        // const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        // fetch('/certified-copy/download-zip', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': csrfToken
        //     },
        //     body: JSON.stringify({ application_number: applicationNo })
        // })
        // .then(response => response.blob())
        // .then(blob => {
        //     const url = window.URL.createObjectURL(blob);
        //     const a = document.createElement('a');
        //     a.href = url;
        //     a.download = `${applicationNo}.zip`;
        //     document.body.appendChild(a);
        //     a.click();
        //     document.body.removeChild(a);
        //     window.URL.revokeObjectURL(url);
        // })
        // .catch(error => {
        //     console.error("ZIP Download error:", error);
        //     alert("Failed to download ZIP file.");
        // });
    }
    
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

                const isOrderCopy = applicationNo.startsWith("HCW") || /^[A-Z]{3}W/.test(applicationNo);
                const isOrderCopyCivil = !applicationNo.startsWith("H");

                let extraButtons = '';
                if (docs.length > 1) {
                    const encodedDocs = encodeURIComponent(JSON.stringify(docs));
                    extraButtons = `
                        <div class="mb-4 w-full flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end items-stretch sm:items-center sm:-mt-16 mt-0">
                            <button onclick="downloadAllDocuments('${encodedDocs}')" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm sm:text-[16px] w-full sm:w-auto">
                                <img src="/passets/images/icons/download.svg" alt="Download All" class="w-5 h-5">
                                Download All Files
                            </button>
                        </div>
                    `;
                }
    //  <button onclick="downloadAsZip('${applicationNo}')" class="flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm sm:text-[16px] w-full sm:w-auto">
    //     <img src="/passets/images/icons/zip.svg" alt="Download ZIP" class="w-5 h-5">
    //     Download as ZIP
    // </button>
                let tableHTML = `
                    ${extraButtons}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 text-sm text-left">
                            <thead class="bg-[#D09A3F] text-md uppercase text-white">
                                <tr>
                                    <th class="px-2 py-2 border-b font-semibold text-md">S.No.</th>
                `;

                if (isOrderCopy) {
                    // Same structure for HCW and Civil Court "Order Copy"
                    tableHTML += `
                        <th class="px-2 py-1 border-b font-semibold text-md">Order No</th>
                        <th class="px-2 py-1 border-b font-semibold text-md">Order Date</th>
                        <th class="px-2 py-1 border-b font-semibold text-md">No. of Pages</th>
                    `;
                } else {
                    // Other documents
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
                            <td class="px-2 py-1 border-b text-md border">${doc.order_number || '-'}</td>
                            <td class="px-2 py-1 border-b text-md border">${doc.order_date || '-'}</td>
                            <td class="px-2 py-1 border-b text-md border">${doc.new_page_no || doc.number_of_page || '-'}</td>
                        `;
                    } else {
                        tableHTML += `
                            <td class="px-2 py-1 border-b text-md border">${doc.document_type || '-'}</td>
                            <td class="px-2 py-1 border-b text-md border">${doc.number_of_page || '-'}</td>
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
        const url_application_number = @json($applicationNumber);

        if (url_application_number) {
            // continue with AJAX logic
            if(url_application_number.startsWith('HC')) {
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
                    application_number: url_application_number,
                },
                success: function(response) {
                    if (response.success) {
                        var color_key = response.data[0].color_key;
                        var app_no = response.data[0].application_number;
                        const noteButton = document.querySelector('#note button');
                        // noteButton.setAttribute('onclick', `detailsPayment('${app_no}')`);
                        const orderDetails = response?.order_details || [];
                        const merchantDetails = response?.merchantdetails;
                        const responseData = response.data[0];
                        const transaction_no = merchantDetails?.transaction_number || null;
                       if(merchantDetails != null && transaction_no != null){
                            doubleVerification(merchantDetails,app_no,responseData,orderDetails,color_key);
                        }else{
                            displayApplicationDetails(responseData,orderDetails,transaction_no,color_key);
                        }
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

    function doubleVerification(merchantDetails, app_no, responseData, orderDetails, color_key) {
        const DEPID = merchantDetails.deptid;
        const DEPTTRANID = 'TR176261120073123';
        const SECURITYCODE = merchantDetails.securitycode;

        // Timeout function (30 seconds)
        const timeout = (ms) => new Promise((_, reject) => {
            setTimeout(() => reject(new Error('Request timed out after ' + ms / 1000 + ' seconds')), ms);
        });

        Promise.race([
            fetch('/double-verification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    depid: DEPID,
                    depttranid: DEPTTRANID,
                    securitycode: SECURITYCODE
                })
            }),
            timeout(30000) // 30 seconds
        ])
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.decrypted_data)) {
                const d = data.decrypted_data;

                const decrypted_jegras_resp_dv_payload = {
                    APPLICATION_NUMBER: app_no,
                    DEPTID: d[0], RECIEPTHEADCODE: d[1], DEPOSITERNAME: d[2], DEPTTRANID: d[3],
                    AMOUNT: d[4], DEPOSITERID: d[5], PANNO: d[6], ADDINFO1: d[7],
                    ADDINFO2: d[8], ADDINFO3: d[9], TREASCODE: d[10], IFMSOFFICECODE: d[11],
                    STATUS: d[12], PAYMENTSTATUSMESSAGE: d[13], GRN: d[14], CIN: d[15],
                    REF_NO: d[16], TXN_DATE: d[17], TXN_AMOUNT: d[18], CHALLAN_URL: d[19],
                    PMODE: d[20], ADDINFO5: d[21], ADDINFO6: d[22]
                };

                console.log("Payload to send: ", decrypted_jegras_resp_dv_payload);

                fetch('/verify-jegras-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(decrypted_jegras_resp_dv_payload)
                })
                .then(res => res.json())
                .then(apiResponse => {
                    if (apiResponse.success) {
                        const status = apiResponse.data.STATUS;
                        console.log('status', status);

                        if (status === "SUCCESS") {
                            responseData.paymentStatus = 1;
                            responseData.application_status = 'Certified Copy is Not Ready Yet';
                        } else if (status === "FAIL") {
                            responseData.payment_status = 0;
                            responseData.application_status = "Payment Pending";
                        } else if (status === "BOOKED") {
                            responseData.payment_status = 2;
                            responseData.application_status = 'Payment is in Process';
                        }

                        displayApplicationDetails(responseData, orderDetails, merchantDetails.transaction_number, color_key);
                    } else {
                        alert("Double Verification failed while verifying payment status.");
                    }
                })
                .catch(error => {
                    console.error('Error during Laravel DV API:', error);
                    alert("Something went wrong while verifying payment. Please try again later.");
                });

            } else {
                alert("Double Verification failed. Decryption unsuccessful or data missing.");
            }
        })
        .catch(error => {
            console.error('Double Verification error:', error);
            alert("Request failed or timed out. Please try again.");
            window.location.href='{{ $hasHC ? '/trackStatusMobileHC' : '/trackStatusMobileDC' }}'
        });
    }


    function displayApplicationDetails(data,orderDetails,transaction_number,color_key) {
        
        document.getElementById('loading-overlay').style.display ='none';
        const print_btn_track = document.getElementById('print_container');
        print_btn_track.classList.remove('hidden');
        // Display the application status
        var applicationStatus = data.application_status ? `Application Status - ( ${data.application_status} )` : '';
       
        var detailsSection = $('#application-details');

        // Format the created_at time as dd-mm-yyyy hh:mm AM/PM
        var createdAt = new Date(data.created_at);
        var formattedCreatedAt = formatDateTime(createdAt);

        var applicationStatus = data.application_status ? data.application_status.trim() : 'N/A';
       
        var application_no = data.application_number || 'N/A';
        var rejection_remarks = data.rejection_remarks || 'REJECTED BY COPYING SECTION';
      
        if (data.payment_status === 0 || (data.deficit_status === 1 && data.deficit_payment_status === 0) ) {
            const payBtn = document.getElementById("pay-now-button");
            const note_span = document.getElementById("note_span");
            note_span.classList.remove("hidden");
            payBtn.classList.remove("hidden");
            payBtn.setAttribute("onclick", `paymentPending('${application_no}')`);
        } else {
            if(transaction_number == null && data.payment_status=== 3 && data.document_status ===1){
                const payBtn = document.getElementById("pay-now-button");
                const note_span = document.getElementById("note_span");
                note_span.classList.remove("hidden");
                payBtn.classList.remove("hidden");
                payBtn.setAttribute("onclick", `paymentPending('${application_no}')`);
            }
        }

        if (data.certified_copy_ready_status === 1) {
            const downloadBtn = document.getElementById("download-cc");
            downloadBtn.classList.remove("hidden");
            downloadBtn.setAttribute("onclick", `downloadCC('${application_no}')`);
        }

        var applicationStatusRow = `
            <div class="w-full mb-4">
                <div class="w-full text-start pl-4 text-white text-sm sm:text-base font-semibold tracking-wide uppercase py-3 rounded-md bg-gradient-to-r ${color_key || 'from-gray-500 to-gray-600'}">
                   Application Status : ${applicationStatus}
                </div>
            </div>
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

        var rejection_remarks = data.rejection_remarks ? `
            <tr class="border">
                <td class="px-6 py-2 font-semibold uppercase border">Rejection Remarks</td>
                <td class="px-6 py-2 uppercase text-red-500 font-bold">${data.rejection_remarks}</td>
            </tr>
        ` : '';

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
            caseDetails_filling = ``;
        } else if (data.selected_method === 'C') {
            caseDetails = `
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Case Number</td>
                    <td class="px-6 py-2">${data.case_type}/${data.case_filling_number}/${data.case_filling_year}</td>
                </tr>
            `;
            caseDetails_filling = ``;
        } else{
            '';
        } if ((data.case_number && data.case_year) || (data.filing_number && data.filing_year) ) {
            if(data.case_number && data.case_year){
            caseDetails = `
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Case Number</td>
                    <td class="px-6 py-2">${data.case_type || ''}/${data.case_number}/${data.case_year}</td>
                </tr>
            `;
            caseDetails_filling = ``;
            }if(data.filing_number && data.filing_year)
            {
                 caseDetails_filling = `
                <tr class="border">
                    <td class="px-6 py-2 font-semibold uppercase border">Filling Number</td>
                    <td class="px-6 py-2">${data.case_type || ''}/${data.filing_number}/${data.filing_year}</td>
                </tr>
            `;
            }
        }


        const orderDetailsList = orderDetails || []; // fallback to empty array
        const hasExtraColumns = orderDetailsList.some(
         item => item.number_of_page < item.new_page_no
        );
         

        const orderDetailsRows = orderDetailsList.map((item, index) => `
            <tr class="border">
            <td class="px-4 py-2 border">${item.order_number || 'N/A'}</td>
            <td class="px-4 py-2 border">${item.order_date || 'N/A'}</td>
            <td class="px-4 py-2 border">${item.number_of_page || 'N/A'}</td>
            <td class="px-4 py-2 border text-green-500">₹${item.amount || 'N/A'}</td>
            ${hasExtraColumns ? `
                <td class="px-4 py-2 border">${item.new_page_no || 'N/A'}</td>
                <td class="px-4 py-2 border">₹${item.new_page_amount || 'N/A'}</td>
                    <td class="px-4 py-2 border text-green-500">₹${ (item.new_page_amount - item.amount).toFixed(2) }</td>
            ` : ''}
            </tr>
            `).join('');


        detailsSection.html(`
        ${applicationStatusRow}
        <div class="overflow-x-auto">
            <table class="dark_form min-w-full border border-gray-300 text-md text-left">
                <thead>
                    <tr class="bg-[#D09A3F] text-white">
                        <th class="px-6 py-2 text-left text-sm sm:text-lg uppercase border-t border-b border-l">Request Details</th>
                        <th class="px-6 py-2 text-left text-sm sm:text-lg uppercase border-t border-b border-r">Request Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Application Number</td>
                        <td class="px-6 py-2 text-teal-500 font-bold text-lg">${data.application_number}</td>
                    </tr>
                    <tr class="border">
                        <td class="px-6 py-2 font-semibold uppercase border">Applicant Name</td>
                        <td class="px-6 py-2 uppercase">${data.applicant_name}</td>
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
                    ${caseDetails_filling}
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
                    ${data.rejection_status === 1 ? `
                        <tr class="border">
                            <td class="px-6 py-2 font-semibold uppercase border">Rejection Remarks</td>
                            <td class="px-6 py-2 uppercase text-red-500 font-bold">${data.rejection_remarks || 'N/A'}</td>
                        </tr>
                    ` : ''}
                </tbody>
            </table>
            
             
            ${orderDetailsList.length > 0 ? `
            <div class="mt-6">
             <h3 class="text-lg font-semibold mb-2">Order Details</h3>
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead>
                    <tr class="bg-[#4B3D2F] text-white text-left text-md">
                        <th class="px-4 py-2 border">Order Number</th>
                        <th class="px-4 py-2 border">Order Date</th>
                        <th class="px-4 py-2 border">No of Page</th>
                        <th class="px-4 py-2 border">Amount</th>
                        ${hasExtraColumns ? `
                        <th class="px-4 py-2 border">New Page No</th>
                        <th class="px-4 py-2 border">New Amount</th>
                        <th class="px-4 py-2 border">Deficit Amount</th>
                        ` : ''}
                    </tr>
                    </thead>
                    <tbody>
                    ${orderDetailsRows}
                    </tbody>
                </table>
                </div>
                ` : ''}
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
