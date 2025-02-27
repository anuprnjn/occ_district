@extends('public_layouts.app')

@section('content')

<section class="content-section flex flex-col gap-4">
    <!-- Case Information & Orders -->
    <div class="w-full dark_form rounded-md  -mt-10">
    <div class="flex items-center">
        <h2 class="text-lg font-semibold mb-3 w-[18%]">Case Information</h2>
        <marquee behavior="" direction="left" class="w-full -mt-1">
            <span class="text-red-500 text-md font-extrabold" style="font-weight: 900;">
                ( NOTE: It is advised to pay through Netbanking/BHIM UPI with the SBI epay gateway option )
            </span>
        </marquee>
    </div>
        <table class="w-full border border-gray-300">
            <tbody id="caseInfoTable">
                <!-- Data will be populated dynamically -->
            </tbody>
        </table>

        <!-- Orders Table -->
        <h2 class="text-md font-semibold mt-4 mb-3">Orders</h2>
        <div class="overflow-x-auto rounded-md">
            <table class="w-full border border-gray-300 text-sm" id="ordersTable">
                <thead>
                    <tr class="bg-gray-800 text-white text-left text-md">
                        <th class="py-2 px-2 border">Order No</th>
                        <th class="py-2 px-2 border">Date</th>
                        <th class="py-2 px-2 border">Pages</th>
                        <th class="py-2 px-2 border">Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Applicant Details -->
    <div class="w-full dark_form rounded-md">
   
    <h2 class="text-lg font-semibold mb-3">Applicant Details</h2>

        <table class="w-full border border-gray-300">
            <tbody id="applicantDetails"></tbody>
        </table>
           <div class="flex justify-end items-start w-full gap-3 mt-2">
           <button class="order_btn bg-sky-500 w-[200px] text-white p-3 rounded-md hover:bg-sky-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="editUserDetails()">Edit details</button>
            <button class="order_btn bg-green-500 w-[200px] text-white p-3 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="submitUserDetails(event)">
                Pay now
            </button>
           </div>
    </div>

    <form name="eGrassClient" method="post" action="https://finance.jharkhand.gov.in/jegras/payment.aspx">

        <input type="hidden" name="requestparam" value="">

        <!-- <input type="submit" name="submit" value="Submit"> -->
        <input type="submit" value="Submit" class="hidden">
    </form>


</section>

@endsection

@push('scripts')
   
<script>
    document.addEventListener("DOMContentLoaded", function () {
    fetch('/get-urgent-fee')
        .then(response => response.json())
        .then(data => {
            // console.log(data.urgent_fee);
            const urgent_fee_value = parseFloat(data.urgent_fee)
            const caseInfo = JSON.parse(sessionStorage.getItem('caseInfoDetails'));

            if (caseInfo) {
                let applicantDetailsHtml = `
                    <tr>
                        <td class="border p-2 font-bold">Name</td>
                        <td class="border p-2">${caseInfo.name}</td>
                        <td class="border p-2 font-bold">Mobile: ${caseInfo.mobile}</td>
                    </tr>
                    <tr>
                        <td class="border p-2 font-bold">Email</td>
                        <td class="border p-2">${caseInfo.email}</td>
                        <td class="border p-2 font-bold">Applied By: <span class="capitalize">${caseInfo.selectedValue}</span></td>
                    </tr>
                    ${caseInfo.adv_res ? `
                    <tr>
                        <td class="border p-2 font-bold">Advocate Registration</td>
                        <td class="border p-2">${caseInfo.adv_res}</td>
                        <td class="border p-2 font-bold">Request Mode:
                            <span class="px-3 py-1 rounded-md text-white ${caseInfo.requestMode === 'urgent' ? 'bg-red-500' : 'bg-green-500'}">
                                ${caseInfo.requestMode.charAt(0).toUpperCase() + caseInfo.requestMode.slice(1)}
                            </span>
                            ${caseInfo.requestMode === 'urgent' ? `<span class="text-xs text-gray-600 ml-2">(Urgent Fee ₹${urgent_fee_value})</span>` : ''}
                        </td>
                    </tr>` : `
                    <tr>
                        <td class="border p-2 font-bold">Request Mode</td>
                        <td class="border p-2">
                            <span class="px-3 py-1 rounded-md text-white ${caseInfo.requestMode === 'urgent' ? 'bg-red-500' : 'bg-green-500'}">
                                ${caseInfo.requestMode.charAt(0).toUpperCase() + caseInfo.requestMode.slice(1)}
                            </span>
                            ${caseInfo.requestMode === 'urgent' ? `<span class="text-xs ml-2">(Urgent Fee ₹${urgent_fee_value})</span>` : ''}
                        </td>
                        <td class="border p-2"></td>
                    </tr>`}
                <tr>
                    <td class="border p-2 font-bold">Total Payable Amount</td>
                    <td class="border p-2 text-green-500" colspan="2" id="totalAmountSection"></td>
                </tr>
                `;

                let totalAmount = 0;
                let isUrgent = caseInfo.requestMode === 'urgent' ? urgent_fee_value : 0;

                if (caseInfo.selectedOrders && caseInfo.selectedOrders.length > 0) {
                    caseInfo.selectedOrders.forEach(order => {
                        let amount = parseFloat(order.amount) || 0;
                        totalAmount += amount;

                        let tableRow = `
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-2">${order.orderNumber}</td>
                                <td class="p-2">${order.orderDate}</td>
                                <td class="p-2">${order.numPages}</td>
                                <td class="p-2 font-bold text-green-600">₹${amount.toFixed(2)}</td>
                            </tr>`;
                        
                        document.querySelector("#ordersTable tbody").insertAdjacentHTML('beforeend', tableRow);
                    });
                }

                totalAmount += isUrgent;
                sessionStorage.setItem('paybleAmount', totalAmount);

                document.getElementById("applicantDetails").innerHTML = applicantDetailsHtml;
                document.getElementById("totalAmountSection").innerHTML = `₹${totalAmount.toFixed(2)}`;
            }
        })
        .catch(error => {
            console.error('Error fetching urgent fee:', error);
        });
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const storedData = sessionStorage.getItem("caseInfo");

        if (storedData) {
            const data = JSON.parse(storedData);
            const caseInfoTable = document.querySelector("#caseInfoTable");

            caseInfoTable.innerHTML = `
                <tr>
                    <td class="border p-2 font-bold">Filing Number</td><td class="border p-2">${data.cases[0].fillingno}</td>
                    <td class="border p-2 font-bold">Case Number</td><td class="border p-2">${data.cases[0].caseno}</td>
                </tr>
                <tr>
                 <td class="border p-2 font-bold">CNR Number</td><td class="border p-2">${data.cases[0].cino}</td>
                    <td class="border p-2 font-bold">Case Status</td><td class="border p-2">${data.cases[0].casestatus}</td>
                     
                </tr>
                <tr>
                   <td class="border p-2 font-bold">Petitioner Name</td><td class="border p-2">${data.cases[0].pet_name}</td>
                    <td class="border p-2 font-bold">Respondent Name</td><td class="border p-2">${data.cases[0].res_name}</td>
                </tr>
            `;
        }           
    });
</script>

<script>
   function editUserDetails() {
    window.history.back();
    }
</script>  
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const caseInfo = JSON.parse(sessionStorage.getItem('caseInfoDetails'));

    if (!caseInfo) {
        sessionStorage.removeItem('caseInfo');
        window.location.href = "/"; 
    }
});
</script>  

<script>
  document.addEventListener("DOMContentLoaded", function () {
    let timeoutDuration = 500 * 60 * 1000; // 5 minutes in milliseconds
    let timeout;
    let sessionExpired = false;

    function startSessionTimeout() {
        timeout = setTimeout(() => {
            sessionStorage.removeItem('caseInfo');
            sessionStorage.removeItem('caseInfoDetails');
            sessionStorage.removeItem('uegent_fee');

            sessionExpired = true;

            if (!document.hidden) {
                alert("Session expired! Redirecting to home page.");
                window.location.href = "/";
            }
        }, timeoutDuration);
    }

    function resetSessionTimeout() {
        if (sessionExpired) return; // Prevent reset if session already expired
        clearTimeout(timeout);
        startSessionTimeout();
    }

    // Detect when the user switches back to the tab
    document.addEventListener("visibilitychange", function () {
        if (!document.hidden && sessionExpired) {
            alert("Session expired! Redirecting to home page.");
            window.location.href = "/";
        }
    });

    // Start the session timeout when the page loads
    startSessionTimeout();

    // Attach the resetSessionTimeout function to user interactions
    document.addEventListener("mousemove", resetSessionTimeout);
    document.addEventListener("keydown", resetSessionTimeout);

    async function submitUserDetails(event) {
        event.preventDefault();
        clearTimeout(timeout); // Stop the session timeout

        try {
            const userData = JSON.parse(sessionStorage.getItem('caseInfoDetails'));
            const caseData = JSON.parse(sessionStorage.getItem('caseInfo'));

            var casenoss = caseData.cases[0].caseno;
            var fillingnoss = caseData.cases[0].fillingno;

            const matchCaseNo = casenoss.match(/\/(\d+)\/?/);
            const matchCaseYear = casenoss.match(/\/(\d{4})$/);
            const matchFilingNo = fillingnoss.match(/\/(\d+)\/?/);
            const matchFilingYear = fillingnoss.match(/\/(\d{4})$/);

            const caseNumber = matchCaseNo ? matchCaseNo[1] : "";
            const caseYear = matchCaseYear ? matchCaseYear[1] : "";
            const filingNumber = matchFilingNo ? matchFilingNo[1] : "";
            const filingYear = matchFilingYear ? matchFilingYear[1] : caseYear;

            const requestData = {
                applicant_name: userData.name,
                mobile_number: userData.mobile,
                email: userData.email,
                case_type: caseData.cases[0].casetype,
                filingcase_type: caseData.cases[0].filingcasetype,
                case_number: caseNumber,
                filing_number: filingNumber,
                case_year: caseYear,
                filing_year: filingYear,
                request_mode: userData.requestMode,
                applied_by: userData.selectedValue,
                cino: caseData.cases[0].cino,
                advocate_registration_number: userData.adv_res ? userData.adv_res : null,
                order_details: userData.selectedOrders.map((order, index) => ({
                    order_no: index + 1,
                    order_date: order.orderDate,
                    case_number: caseNumber,
                    filing_number: filingNumber,
                    page_count: order.numPages,
                    amount: order.amount
                }))
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch("/submit-order-copy", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(requestData)
            });

            const responseData = await response.json();

            if (responseData.success) {
                alert(`Success! Application Number: ${responseData.application_number}\nMessage: ${responseData.message}`);
                paymentToMerchant(event, responseData.application_number)
                // sessionStorage.removeItem('caseInfo');
                // sessionStorage.removeItem('caseInfoDetails');   
            } else {
                alert("Error: Data insertion failed.");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while submitting the request.");
        }
    }

    // Attach event listener for form submission
    document.querySelector("button[onclick='submitUserDetails(event)']").onclick = submitUserDetails;
    
});
function paymentToMerchant(event, applicationNumber) {
    event.preventDefault();

    const userData = JSON.parse(sessionStorage.getItem('caseInfoDetails'));
    const paybleAmount = sessionStorage.getItem("paybleAmount");

    fetch('/fetch-merchant-details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ userData, paybleAmount, applicationNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error:", data.error);
        } else {
            console.log("Encrypted Value:", data.enc_val);
            console.log("Application Number:", data.application_number)
            // Find the form correctly
            const form = document.querySelector('form[name="eGrassClient"]');

            if (form) {
                // Set encrypted value
                form.querySelector('input[name="requestparam"]').value = data.enc_val;
                // Use submit correctly
                // form.submit();
            } else {
                console.error("Form eGrassClient not found!");
            }
        }
    })
    .catch(error => console.error("Fetch error:", error));
}
</script> 

@endpush