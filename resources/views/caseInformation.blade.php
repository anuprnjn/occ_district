@extends('public_layouts.app')

@section('content')

<section class="content-section flex flex-wrap sm:flex-nowrap items-start justify-between gap-6 p-4 border-t">
    <!-- First Section -->
     
    <div class="w-full sm:w-2/3 dark_form p-4 rounded-md">
        
        <h2 class="text-xl font-semibold mt-0 mb-2">Case Information</h2>
        <div id="caseDetails">
            <p class="text-green-600">Loading case details...</p>
        </div>

        <h2 class="text-lg font-semibold mt-2 mb-2">Orders</h2>
        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full border border-gray-200" id="ordersTable">
                <thead>
                    <tr class="bg-[#4B3D2F] text-white text-left text-md">
                        <th class="py-2 px-4 border">Select</th>
                        <th class="py-2 px-4 border">Order Number</th>
                        <th class="py-2 px-4 border">Order Date</th>
                        <th class="py-2 px-4 border">Number of pages</th>
                        <th class="py-2 px-4 border">Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!-- First Section Ends -->

    <!-- Second Section -->
    <div class=" dark_form w-full bg-slate-100/70 sm:w-1/3 p-4 rounded-md mt-10 sm:mb-0 mb-[100px]">
        <h2 class="text-xl font-bold mb-4">User Details</h2>
        
        <form action="#" class=" space-y-4">
            @csrf

            <div class="form-field">
                <label for="name" class="block font-medium">Name: <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required  class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
            <label for="mobile" id="mobileLabel">Mobile No: <span>*</span></label>
            <span id="otpTimer"class="text-md text-rose-600 " ></span>
                <div class="flex items-center gap-2">
                    <input type="text" id="mobileInput" name="mobile" placeholder="Enter Your Mobile No"
                        class="flex-1 p-2 border rounded-md" required  maxlength="10" onkeydown="return isNumber(event)">
                    <button type="button" id="otpButton" value="HC" onclick="sendOtp(value)"
                        class="bg-[#4B3E2F] w-[150px] p-[11px] rounded-md text-white hover:bg-[#D09A3F]">
                        Send OTP
                    </button>
                </div>
            </div>

            <div class="form-field">
                <label for="email" class="block font-medium">Email: <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email" required  class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
                <label for="confirm-email" class="block font-medium">Confirm Email: <span class="text-red-500">*</span></label>
                <input type="email" id="confirm-email" name="confirm_email" placeholder="Enter Your Confirm Email" required  class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
                <label for="apply-by" class="block font-medium">Applied By: <span class="text-red-500">*</span></label>
                <select id="apply-by" name="apply_by" required  class="w-full p-3 border rounded-md" onchange="toggleAdvocateField()">
                    <option value="">--Select--</option>
                    <option value="petitioner">Petitioner</option>
                    <option value="respondent">Respondent</option>
                    <option value="advocate">Advocate</option>
                    <option value="others">Others</option>
                </select>
            </div>
            <div class="form-field" id="advocateField" style="display: none;">
                <label for="adv_res" class="mt-2">Advocate Registration No <span>*</span></label>
                <input type="text" id="adv_res" name="adv_res" class="mt-5" placeholder="Enter Advocate Registration No">
            </div>


            <div class="form-field">
                <label class="block font-medium">Request Mode: <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-4">
                    <input type="radio" id="urgent" name="request_mode" value="urgent" required >
                    <label for="urgent">Urgent</label>
                    <input type="radio" id="ordinary" name="request_mode" value="ordinary" required  class="ml-2">
                    <label for="ordinary">Ordinary</label>
                </div>
            </div>

            <div class="form-field">
            <button type="submit" id="submitBtn" class="hidden mt-4 order_btn w-full bg-[#4B3E2F] text-white p-3 rounded-md hover:bg-[#D09A3F] flex items-center justify-center gap-2"
                    onclick="handleCaseInformationSubmit(event)">
                <span id="btnText">Submit</span>
                <span id="btnSpinner" class="hidden loader"></span>
            </button>
            </div>
        </form>
    </div>
    <!-- Second Section Ends -->
</section>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>

<!-- to show case details  -->

<script>
    document.addEventListener("DOMContentLoaded", async function () {
        try {
            // Fetch case data asynchronously
            const response = await fetch('/get-case-data');
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            const data = await response.json();

            // Ensure responseData exists
            const responseData = data?.session_data?.HcCaseDetailsNapix;
            console.log(responseData);
            if (!responseData) {
                throw new Error('Missing responseData!');
            }
            console.log(responseData);

            // ** Display Case Details **
            const caseDetailsDiv = document.getElementById("caseDetails");
            const caseInfo = responseData;

            if (caseInfo) {
                
                caseDetailsDiv.innerHTML = `
                    <div class="rounded-xl caseInfoShowCaseDetails p-4">
                        ${caseInfo ? `
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div>
                                        <h6 class="text-sm text-gray-500 mb-1">CNR Number</h6>
                                        <h6 class="font-semibold break-all">${caseInfo.cino || 'N/A'}</h6>
                                    </div>
                                    <div>
                                        <h6 class="text-sm text-gray-500 mb-1">Case Number</h6>
                                        <h6 class="font-semibold">${caseInfo.type_name || 'N/A'}/${caseInfo.reg_no || 'N/A'}/${caseInfo.reg_year || 'N/A'}</h6>
                                    </div>
                                                               <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Case Status</h6>
                                    <span class="-ml-1 inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold tracking-wide ${
                                        caseInfo.case_status?.toUpperCase() === 'P' 
                                            ? 'bg-blue-100 text-blue-800' 
                                            : caseInfo.case_status?.toUpperCase() === 'D'
                                                ? 'bg-red-100 text-red-800' 
                                                : 'bg-gray-100 text-gray-800'
                                    }">
                                        ${
                                            caseInfo.case_status?.toUpperCase() === 'P'
                                                ? 'PENDING'
                                                : caseInfo.case_status?.toUpperCase() === 'D'
                                                    ? 'DISPOSED'
                                                    : caseInfo.case_status || 'N/A'
                                        }
                                    </span>
                                </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <h6 class="text-sm text-gray-500 mb-1">Petitioner</h6>
                                        <h6 class="font-semibold">${caseInfo.pet_name || 'N/A'}</h6>
                                    </div>
                                    <div>
                                        <h6 class="text-sm text-gray-500 mb-1">Respondent</h6>
                                        <h6 class="font-semibold">${caseInfo.res_name || 'N/A'}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : `
                        <div class="text-center py-8">
                            <div class="text-red-500 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="font-medium">No case information found!</span>
                            </div>
                        </div>
                        `}
                    </div>
                `;
            } else {
                caseDetailsDiv.innerHTML = `<p class="text-red-500">No case information found!</p>`;
            }

        } catch (error) {
            console.error('Fetch Error:', error);
            document.getElementById("caseDetails").innerHTML = `
                <p class="text-red-500">Error: ${error.message}</p>
            `;
        }
    });
</script>

<!-- show orders script  -->
<script>
    document.addEventListener("DOMContentLoaded", async function () {
        try {
            // Step 1: Fetch case data
            const response = await fetch('/get-case-data');
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();
            const responseDataHC = data?.session_data?.HcCaseDetailsNapix;

            if (!responseDataHC) throw new Error('No case info available');
            const cino = responseDataHC.cino;
            const interimOrders = responseDataHC.interim || {};

            // Step 2: Render Orders Table
            const ordersTable = document.getElementById("ordersTable")?.querySelector("tbody");
            if (!ordersTable) {
                console.warn("ordersTable tbody not found");
                return;
            }

            ordersTable.innerHTML = "";

            const interimEntries = Object.values(interimOrders);
            if (interimEntries.length > 0) {
                interimEntries.forEach(order => {
                    const row = document.createElement("tr");
                    row.classList.add("border-b", "cursor-pointer", "caseInfoTable");

                    row.innerHTML = `
                        <td class="py-2 px-4 border">
                            <input type="checkbox" class="order-checkbox"/>
                        </td>
                        <td class="py-2 px-4 border">${order.order_no || 'N/A'}</td>
                        <td class="py-2 px-4 border">${order.order_date || 'N/A'}</td>
                        <td class="py-2 px-4 border pages-cell">
                            <div class="spinnerDc"></div>
                        </td>
                        <td class="py-2 px-4 border font-bold amount-cell">
                            <div class="spinnerDc"></div>
                        </td>
                    `;

                    row.addEventListener("click", function (event) {
                        if (event.target.type === "checkbox" || event.target.tagName.toLowerCase() === "button") return;

                        // Prevent selection if any button is present in the row
                        if (row.querySelector("button")) return;

                        const checkbox = row.querySelector(".order-checkbox");
                        if (checkbox) checkbox.checked = !checkbox.checked;
                    });

                    ordersTable.appendChild(row);
                });

                // Step 3: Fetch PDF info for each order row
                const tableRows = ordersTable.querySelectorAll("tr");

                interimEntries.forEach((order, index) => {
                    const payload = {
                        order_no: order.order_no,
                        order_date: order.order_date,
                        cino: cino
                    };

                    const currentRow = tableRows[index];
                    const pageCell = currentRow.querySelector(".pages-cell");
                    const amountCell = currentRow.querySelector(".amount-cell");

                    async function fetchPdfAndUpdateUI() {
                        pageCell.innerHTML = `<div class="spinnerDc"></div>`;
                        amountCell.innerHTML = `<div class="spinnerDc"></div>`;

                        try {
                            const res = await fetch('/get-hc-order-pdf-napix', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(payload)
                            });

                            const result = await res.json();

                            if (result.status === 'success') {
                                pageCell.innerHTML = result.pages || '0';
                                amountCell.innerHTML = `<span class="text-green-500">₹&nbsp;</span>${result.amount || 0}`;
                            } else {
                                showRetryButton();
                                console.error(`Order #${order.order_no} PDF fetch failed: ${result.message}`);
                            }
                        } catch (error) {
                            showRetryButton();
                            console.error(`Order #${order.order_no} PDF fetch error:`, error);
                        }
                    }

                    function showRetryButton() {
                        // Show "-" for pages
                        pageCell.innerHTML = `-`;

                        // Show Reload Amount button
                        amountCell.innerHTML = `
                            <button class="flex bg-[#D09A3F] text-white get-pdf-btn text-white text-sm px-3 py-1 rounded">
                                Click to get amount
                            </button>
                        `;

                        const retryBtn = amountCell.querySelector('.get-pdf-btn');
                        if (retryBtn) {
                            retryBtn.addEventListener('click', async function () {
                                retryBtn.disabled = true;
                                retryBtn.innerText = 'Loading...';
                                await fetchPdfAndUpdateUI();
                            });
                        }
                    }

                    // Initial attempt
                    fetchPdfAndUpdateUI();
                });

            } else {
                ordersTable.innerHTML = `<tr><td colspan="5" class="text-center py-2">No orders available</td></tr>`;
            }

        } catch (error) {
            console.error('Fetch Error:', error);
            const caseDetailsDiv = document.getElementById("caseDetails");
            if (caseDetailsDiv) {
                caseDetailsDiv.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
            }
        }
    });
</script>

<!-- toggle adv input  -->
<script>
    function toggleAdvocateField() {
    const advocateField = document.getElementById('advocateField');
    const advocateInput = document.getElementById('adv_res');
    const applyBy = document.getElementById('apply-by').value;

    if (applyBy === 'advocate') {
        advocateField.style.display = 'block';
        advocateInput.setAttribute('required', 'required'); // Make it required
    } else {
        advocateField.style.display = 'none';
        advocateInput.removeAttribute('required'); // Remove required when hidden
        advocateInput.value = ''; // Clear the field to prevent unwanted submission
    }
    }
</script>

<!-- submit user details to session  -->

<script>
    async function handleCaseInformationSubmit(event) {
    
        event.preventDefault();

        const submitBtn = document.getElementById("submitBtn");
        const btnText = document.getElementById("btnText");
        const btnSpinner = document.getElementById("btnSpinner");

        // Get form field values
        const name = document.getElementById("name").value.trim();
        const mobile = document.getElementById("mobileInput").value.trim();
        const email = document.getElementById("email").value.trim();
        const cnfemail = document.getElementById("confirm-email").value.trim();
        const selectedValue = document.getElementById("apply-by").value;
        const requestMode = document.querySelector('input[name="request_mode"]:checked')?.value || "";
        const adv_res = document.getElementById("adv_res")?.value.trim() || "";

        // Validation checks
        if (name === "") {
            alert("Please enter your name.");
            return;
        }

        const mobileRegex = /^[6-9]\d{9}$/;
        if (!mobileRegex.test(mobile)) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (email === "" || !emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return;
        }

        if (cnfemail === "" || cnfemail !== email) {
            alert("Email and Confirm Email must match.");
            return;
        }

        if (selectedValue === "") {
            alert("Please select 'Applied By'.");
            return;
        }

        if (selectedValue === "advocate" && adv_res === "") {
            alert("Please enter the advocate's registration number.");
            return;
        }

        if (requestMode === "") {
            alert("Please select a request mode.");
            return;
        }

        // Get checked order checkboxes
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        if (checkedBoxes.length === 0) return alert("Please select at least one order.");

        // Get session data (Blade-rendered)
        const hcCaseDetailsNapix = @json(session('HcCaseDetailsNapix'));
        // console.log('details', hcCaseDetailsNapix);
         // Build selectedOrders array
         const selectedOrders = [];
          checkedBoxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            selectedOrders.push({
                order_no: row.cells[1]?.textContent.trim(),
                order_date: row.cells[2]?.textContent.trim(),
                case_no: hcCaseDetailsNapix.reg_no,
                fil_no: hcCaseDetailsNapix.fil_no
            });
        });

        // Show spinner, hide text & disable button
        btnText.style.display = "none";
        btnSpinner.classList.remove("hidden");
        submitBtn.disabled = true;

        const petitioner_name = hcCaseDetailsNapix.pet_name;
        const respondent_name = hcCaseDetailsNapix.res_name;

        const formData = {
            name, mobile, email, selectedValue, adv_res, requestMode, selectedOrders, petitioner_name, respondent_name
        };

        // Prepare fetch request
        const response = await fetch('/set-caseInformation-data', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({ caseInfoDetails: formData })
        });
        console.log("response",response)

        if (!response.ok) {
            throw new Error('Failed to store data in session');
        }

        const data = await response.json();
        console.log('controller resp',data.message);
        window.location.href=data.location;
    }

    // Ensure advocate field updates on dropdown change
    document.getElementById("apply-by").addEventListener("change", toggleAdvocateField);
</script>    

@endpush