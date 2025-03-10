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
        <div class="overflow-x-auto">
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
        <tr>
            <td class="border p-2 font-bold">Total Payable Amount</td>
            <td class="border p-2 text-green-500" colspan="2" id="totalAmountSection"></td>
        </tr>
    </table>
           <div class="flex justify-end items-start w-full gap-3 mt-2">
           <button class="order_btn bg-sky-500 w-[200px] text-white p-3 rounded-md hover:bg-sky-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="editUserDetails()">Edit details</button>
            <button class="order_btn bg-green-500 w-[200px] text-white p-3 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="submitUserDetails(event)">
                Pay now
            </button>
           </div>
    </div>

    <form name="eGrassClient" method="POST" action="https://finance.jharkhand.gov.in/jegras/payment.aspx">

        <input type="hidden" name="requestparam" value="">

        <!-- <input type="submit" name="submit" value="Submit"> -->
        <input type="submit" value="Submit" class="hidden">
    </form>


</section>

@endsection

@push('scripts')
   
<script>
    document.addEventListener("DOMContentLoaded", async function () {
        try {
            // Fetch session data asynchronously
            const response = await fetch('/get-caseInformation-data');
            if (!response.ok) {
                throw new Error('Failed to fetch case information data');
            }
            const data = await response.json();
            console.log("Fetched Data:", data.session_data);

            const urgent_fee_value = parseFloat(data.session_data.urgent_fee);

            let caseInfo = typeof data.session_data.caseInfoDetails === "string"
                ? JSON.parse(data.session_data.caseInfoDetails)
                : data.session_data.caseInfoDetails;

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
                `;

                document.getElementById("applicantDetails").innerHTML = applicantDetailsHtml;

                // **Handling Orders Table**
                let totalAmount = 0;
                let isUrgent = caseInfo.requestMode === 'urgent' ? urgent_fee_value : 0;
                let ordersTableBody = document.querySelector("#ordersTable tbody");
                ordersTableBody.innerHTML = ""; // Clear previous data

                if (caseInfo.selectedOrders && caseInfo.selectedOrders.length > 0) {
                    caseInfo.selectedOrders.forEach(order => {
                        let amount = parseFloat(order.amount.replace('₹', '')) || 0;
                        totalAmount += amount;

                        let tableRow = `
                            <tr>
                                <td class="p-2 border">${order.orderNumber}</td>
                                <td class="p-2 border">${order.orderDate}</td>
                                <td class="p-2 border">${order.numPages}</td>
                                <td class="p-2 border font-bold text-green-600">₹${amount.toFixed(2)}</td>
                            </tr>`;
                        
                        ordersTableBody.insertAdjacentHTML('beforeend', tableRow);
                    });
                } else {
                    ordersTableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="p-2 border text-center text-gray-500">No orders available</td>
                        </tr>`;
                }

                totalAmount += isUrgent;

                // **Store Payable Amount in the session via fetch**
                await fetch('/set-paybleAmount', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ paybleAmount: totalAmount })
                });

                // **Update Total Amount Display**
                document.getElementById("totalAmountSection").innerHTML = `₹${totalAmount.toFixed(2)}`;
            }
        } catch (error) {
            console.error('Error fetching case information:', error);
        }
    });
</script>   

<script>
    document.addEventListener("DOMContentLoaded", async function () {
        try {
            // Fetch case information data asynchronously
            const response = await fetch('/get-caseInformation-data');
            if (!response.ok) {
                throw new Error('Failed to fetch case information data');
            }
            const data = await response.json();

            // Ensure responseData exists
            if (data.session_data && data.session_data.responseData) {
                const caseInfo = data.session_data.responseData.cases[0];
                const caseInfoTable = document.querySelector("#caseInfoTable");

                caseInfoTable.innerHTML = `
                    <tr>
                        <td class="border p-2 font-bold">Filing Number</td>
                        <td class="border p-2">${caseInfo.fillingno || 'N/A'}</td>
                        <td class="border p-2 font-bold">Case Number</td>
                        <td class="border p-2">${caseInfo.caseno || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td class="border p-2 font-bold">CNR Number</td>
                        <td class="border p-2">${caseInfo.cino || 'N/A'}</td>
                        <td class="border p-2 font-bold">Case Status</td>
                        <td class="border p-2">${caseInfo.casestatus || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td class="border p-2 font-bold">Petitioner Name</td>
                        <td class="border p-2">${caseInfo.pet_name || 'N/A'}</td>
                        <td class="border p-2 font-bold">Respondent Name</td>
                        <td class="border p-2">${caseInfo.res_name || 'N/A'}</td>
                    </tr>
                `;
            } else {
                console.error("responseData is missing in the fetched data.");
            }
        } catch (error) {
            console.error("Error fetching case information:", error);
        }
    });
</script>

<script>
   function editUserDetails() {
    window.history.back();
    }
</script>  

<script>
    document.addEventListener("DOMContentLoaded", async function () {
    let timeout;
    let timeoutDuration = 500 * 60 * 1000; // 5 minutes
    let sessionExpired = false;

    function startSessionTimeout() {
        timeout = setTimeout(() => {
            sessionExpired = true;
            if (!document.hidden) {
                alert("Session expired! Redirecting to home page.");
                window.location.href = "/";
            }
        }, timeoutDuration);
    }

    function resetSessionTimeout() {
        if (sessionExpired) return;
        clearTimeout(timeout);
        startSessionTimeout();
    }

    document.addEventListener("visibilitychange", function () {
        if (!document.hidden && sessionExpired) {
            alert("Session expired! Redirecting to home page.");
            window.location.href = "/";
        }
    });

    startSessionTimeout();
    document.addEventListener("mousemove", resetSessionTimeout);
    document.addEventListener("keydown", resetSessionTimeout);

    async function fetchSessionData() {
        try {
            const response = await fetch("/get-caseInformation-data");
            const sessionData = await response.json();

            if (!sessionData || !sessionData.session_data) {
                console.error("Invalid session data response:", sessionData);
                return null;
            }

            return sessionData.session_data;
        } catch (error) {
            console.error("Error fetching session data:", error);
            return null;
        }
    }

    async function submitUserDetails(event) {
        event.preventDefault();
        if (timeout) clearTimeout(timeout);

        try {
            const sessionData = await fetchSessionData();
            if (!sessionData) {
                alert("Error fetching session data.");
                return;
            }

            const userData = sessionData.caseInfoDetails;
            console.log('submit order copy details', userData);
            const caseData = sessionData.responseData;

            if (!userData || !caseData) {
                console.error("Session data missing:", { userData, caseData });
                alert("Session data is missing. Please refresh and try again.");
                return;
            }

            const casenoss = caseData.cases[0]?.caseno || "";
            const fillingnoss = caseData.cases[0]?.fillingno || "";

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
                petitioner_name: userData.petitioner_name,
                respondent_name: userData.respondent_name,
                case_type: caseData.cases[0]?.casetype || "",
                filingcase_type: caseData.cases[0]?.filingcasetype || "",
                case_number: caseNumber,
                filing_number: filingNumber,
                case_year: caseYear,
                filing_year: filingYear,
                request_mode: userData.requestMode,
                applied_by: userData.selectedValue,
                cino: caseData.cases[0]?.cino || "",
                advocate_registration_number: userData.adv_res || null,
                order_details: userData.selectedOrders.map((order, index) => ({
                    order_no: index + 1,
                    order_date: order.orderDate,
                    case_number: caseNumber,
                    filing_number: filingNumber,
                    page_count: parseInt(order.numPages, 10) || 0,
                    amount: parseFloat(order.amount?.replace(/[^\d.]/g, '') || "0")
                }))
            };

            console.log("Submitting request data:", requestData);

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
                paymentToMerchant(event, responseData.application_number);
            } else {
                alert("Error: Data insertion failed.");
            }
        } catch (error) {
            console.error("Error in submitUserDetails:", error);
            alert("An error occurred while submitting the request.");
        }
    }

    async function paymentToMerchant(event, applicationNumber) {
        event.preventDefault();

        try {
            const sessionData = await fetchSessionData();
            if (!sessionData) {
                alert("Error fetching session data.");
                return;
            }

            const userData = sessionData.caseInfoDetails;
            if (!userData) {
                alert("Error: User data is missing. Please refresh and try again.");
                return;
            }

            // Fetch payable amount securely from session
            const paybleAmount = await getPaybleAmount();
            console.log(paybleAmount);
            if (!paybleAmount) {
                alert("Error fetching payable amount.");
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch("/fetch-merchant-details", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ userData, paybleAmount, applicationNumber })
            });

            const data = await response.json();

            if (data.error) {
                console.error("Payment Error:", data.error);
                alert("Error processing payment.");
            } else {
                console.log("Encrypted Value:", data.enc_val);
                console.log("Application Number:", data.application_number);

                const form = document.querySelector('form[name="eGrassClient"]');
                if (form) {
                    form.querySelector('input[name="requestparam"]').value = data.enc_val;
                    alert('Entered to transaction details');
                    // form.submit();
                } else {
                    console.error("Form 'eGrassClient' not found!");
                    alert("Payment form not found. Please try again.");
                }
            }
        } catch (error) {
            console.error("Error in paymentToMerchant:", error);
            alert("An error occurred while processing the payment.");
        }
    }

    // Function to fetch payable amount securely from session
    async function getPaybleAmount() {
        try {
            const response = await fetch('/get-paybleAmount', {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            });

            if (!response.ok) {
                throw new Error('Failed to retrieve payable amount from session.');
            }

            const data = await response.json();
            console.log("Payable Amount:", data.paybleAmount);
            return data.paybleAmount; // Return the fetched amount
        } catch (error) {
            console.error('Error fetching payable amount:', error);
            return null;
        }
    }

    const submitButton = document.querySelector("button[onclick='submitUserDetails(event)']");
    if (submitButton) {
        submitButton.onclick = submitUserDetails;
    } else {
        console.error("Submit button not found.");
    }
});
</script>

@endpush