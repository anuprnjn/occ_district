@extends('public_layouts.app')

@section('content')

<section class="content-section flex flex-wrap sm:flex-nowrap items-start justify-between gap-6 p-4 border-t">
    <!-- First Section -->
    <div class="w-full sm:w-2/3 dark_form p-4 rounded-md" id="main-content">
        
        <h2 class="text-xl font-semibold mt-0 mb-2">Case Information ( Civil Court )</h2>
        <div id="caseDetails">
            <p class="text-green-500 animate-pulse">Loading case details...</p>
        </div>

        <h2 class="text-lg font-semibold mt-2 mb-2">Order Details</h2>
        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full border border-gray-200" id="ordersTableDC">
                <thead>
                    <tr class="bg-[#4B3D2F] text-white text-left text-md">
                        <th class="py-2 px-4 border">Select</th>
                        <th class="py-2 px-4 border">Order Number</th>
                        <th class="py-2 px-4 border">Order Date</th>
                        <th class="py-2 px-4 border">Number of pages</th>
                        <th class="py-2 px-4 border">Amount</th>
                    </tr>
                </thead>
                <tbody id="interimOrdersBody"></tbody>
            </table>
        </div>
    </div>
    <!-- First Section Ends -->

    <!-- Second Section -->
    <div class=" dark_form w-full bg-slate-100/70 sm:w-1/3 p-4 rounded-lg mt-10 sm:mb-0 mb-[100px]">
        <h2 class="text-xl font-bold mb-4">User Details</h2>
        <form action="#" class=" space-y-4">
            @csrf
            <div class="form-field">
                <label for="name" class="block font-medium">Name: <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required  class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
            <label for="mobile" id="mobileLabel">Mobile No: <span>*</span></label>
            <span class="text-sm font-medium text-green-500 hidden" id="mobile_indicator"></span>
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
                <input type="text" id="adv_res" name="adv_res" class="mt-3" placeholder="Enter Advocate Registration No">
            </div>


            <div class="form-field">
                <label class="block font-medium mt-2">Request Mode: <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-4">
                    <input type="radio" id="urgent" name="request_mode" value="urgent" required >
                    <label for="urgent">Urgent</label>
                    <input type="radio" id="ordinary" name="request_mode" value="ordinary" required  class="ml-2">
                    <label for="ordinary">Ordinary</label>
                </div>
            </div>

            <div class="form-field">
            <button type="submit" id="submitBtn" class="hidden mt-4 order_btn w-full bg-[#4B3E2F] text-white p-3 rounded-md hover:bg-[#D09A3F] flex items-center justify-center gap-2"
                    onclick="submitDcUserOrderDetails(event)">
                <span id="btnText">Submit</span>
                <span id="btnspinnerDc" class="hidden loader"></span>
            </button>
            </div>
        </form>
    </div>
    <!-- Second Section Ends -->
</section>

@endsection

@push('scripts')

<!-- importing extra script for OTP  -->
<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>

<!-- script to show the case details  -->
<script>
    document.addEventListener("DOMContentLoaded", async function () {
        try {
            const response = await fetch('/get-case-data');
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();

            const responseDataDC = data?.session_data?.DcCaseDetailsNapix;
            console.log('Case Info:', responseDataDC);

            const caseDetailsDiv = document.getElementById("caseDetails");
            if (!caseDetailsDiv) {
                console.warn('Missing #caseDetails element.');
                return;
            }

            // Display Case Info
            if (responseDataDC) {
                caseDetailsDiv.innerHTML = `
                <div class="rounded-xl caseInfoShowCaseDetails p-4">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                            <div>
                                    <h6 class="text-sm text-gray-500 mb-1">District Name</h6>
                                    <h6 class="font-semibold uppercase">${responseDataDC.establishment_name || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">CNR Number</h6>
                                    <h6 class="font-semibold break-all">${responseDataDC.cino || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Case Number</h6>
                                    <h6 class="font-semibold">${responseDataDC.type_name || 'N/A'}/${responseDataDC.case_number || 'N/A'}/${responseDataDC.case_year || 'N/A'}</h6>
                                </div>
                                 <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Filling Number</h6>
                                    <h6 class="font-semibold">${responseDataDC.type_name || 'N/A'}/${responseDataDC.filling_number || 'N/A'}/${responseDataDC.filling_year || 'N/A'}</h6>
                                </div>
                            
                            
                            </div>
                            <div class="space-y-4">
                            <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Establishment Name</h6>
                                    <h6 class="font-semibold">${responseDataDC.district_name || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Petitioner Name</h6>
                                    <h6 class="font-semibold">${responseDataDC.pet_name || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Respondent Name</h6>
                                    <h6 class="font-semibold">${responseDataDC.res_name || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Case Status</h6>
                                    <span class="-ml-1 inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold tracking-wide ${
                                        responseDataDC.case_status?.toUpperCase() === 'P' 
                                            ? 'bg-blue-100 text-blue-800' 
                                            : responseDataDC.case_status?.toUpperCase() === 'D'
                                                ? 'bg-red-100 text-red-800' 
                                                : 'bg-gray-100 text-gray-800'
                                    }">
                                        ${
                                            responseDataDC.case_status?.toUpperCase() === 'P'
                                                ? 'PENDING'
                                                : responseDataDC.case_status?.toUpperCase() === 'D'
                                                    ? 'DISPOSED'
                                                    : responseDataDC.case_status || 'N/A'
                                        }
                                    </span>
                                </div>

                                
                            </div>
                        </div>
                    </div>
                </div>`;
            } else {
                caseDetailsDiv.innerHTML = `<p class="text-red-500">No case information found!</p>`;
            }

        } catch (error) {
            alert('Error fetching or rendering case data:', error);
        }
    });
</script>

<!-- script to get the pdf from the napix api and order details  -->
<script>
    document.addEventListener("DOMContentLoaded", async function () {
        try {
            // Step 1: Fetch case data
            const response = await fetch('/get-case-data');
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();
            const responseDataDC = data?.session_data?.DcCaseDetailsNapix;

            if (!responseDataDC) throw new Error('No case info available');
            const cino = responseDataDC.cino;
            const interimOrders = responseDataDC.interim || {};
            const dist_name = responseDataDC.establishment_name;
    

            // Step 2: Render Orders Table
            const ordersTable = document.getElementById("ordersTableDC")?.querySelector("tbody");
            if (!ordersTable) {
                console.warn("ordersTableDC tbody not found");
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
                            <input type="checkbox" class="order-checkbox" onchange="handleCheckboxChange(this)"/>
                        </td>
                        <td class="py-2 px-4 border order-no">${order.order_no || 'N/A'}</td>
                        <td class="py-2 px-4 border order-date">${order.order_date || 'N/A'}</td>
                        <td class="py-2 px-4 border pages-cell">
                            ${order.pages || '<div class="spinnerDc"></div>'}
                        </td>
                        <td class="py-2 px-4 border font-bold amount-cell">
                            ${order.amount || '<div class="spinnerDc"></div>'}
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
                        cino: cino,
                        dist_name : dist_name
                    };

                    const currentRow = tableRows[index];
                    const pageCell = currentRow.querySelector(".pages-cell");
                    const amountCell = currentRow.querySelector(".amount-cell");

                    async function fetchPdfAndUpdateUI() {
                        pageCell.innerHTML = `<div class="spinnerDc"></div>`;
                        amountCell.innerHTML = `<div class="spinnerDc"></div>`;

                        try {
                            const res = await fetch('/get-order-pdf-napix', {
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

<!-- toggle function to to show and hide the advocate input based on select  -->

<script>
  function toggleAdvocateField() {
    const appliedBy = document.getElementById("apply-by").value;
    const advocateField = document.getElementById("advocateField");
    const advResInput = document.getElementById("adv_res");

    if (appliedBy === "advocate") {
      advocateField.style.display = "block";
    } else {
      advocateField.style.display = "none";
      advResInput.value = ""; 
    }
  }
</script>

<!-- sending the user details and order details script  -->

<script>
    async function submitDcUserOrderDetails(event) {
        event.preventDefault(); 

        // Get form input values
        const name = document.getElementById("name").value.trim().toUpperCase();
        const mobile = document.getElementById("mobileInput").value.trim();
        const email = document.getElementById("email").value.trim();
        const cnfEmail = document.getElementById("confirm-email").value.trim();
        const appliedBy = document.getElementById("apply-by").value;
        const advRes = document.getElementById("adv_res").value.trim();
        const requestModeElement = document.querySelector('input[name="request_mode"]:checked');
        const requestMode = requestModeElement ? requestModeElement.value : null;

        // Check required fields
        if (name === '') return alert("Please enter your name.");
        if (mobile === '') return alert("Please enter your mobile number.");
        if (!/^\d{10}$/.test(mobile)) return alert("Mobile number must be exactly 10 digits.");
        if (email === '') return alert("Please enter your email.");
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) return alert("Please enter a valid email address.");
        if (cnfEmail === '') return alert("Please confirm your email.");
        if (email !== cnfEmail) return alert("Email and confirm email do not match.");
        if (appliedBy === '') return alert("Please select 'Applied By'.");
        if (appliedBy === 'advocate' && advRes === '') return alert("Please enter Advocate Registration No.");
        if (!requestMode) return alert("Please select a request mode (Urgent or Ordinary).");

        // Get checked order checkboxes
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        if (checkedBoxes.length === 0) return alert("Please select at least one order.");

        // Get session data (Blade-rendered)
        const dcCaseDetailsNapix = @json(session('DcCaseDetailsNapix'));

        // Build selectedOrders array
        const selectedOrders = [];
        checkedBoxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            selectedOrders.push({
                order_no: row.cells[1]?.textContent.trim(),
                order_date: row.cells[2]?.textContent.trim(),
                case_no: dcCaseDetailsNapix.reg_no,
                fil_no: dcCaseDetailsNapix.fil_no
            });
        });

        // Combine all data
        const formData = {
            name,
            mobile,
            email,
            confirm_email: cnfEmail,
            applied_by: appliedBy,
            adv_reg_no: advRes,
            request_mode: requestMode,
            selected_orders: selectedOrders, 

            // From session
            case_details: {
                case_no: dcCaseDetailsNapix.reg_no,
                case_year: dcCaseDetailsNapix.reg_year,
                fil_no: dcCaseDetailsNapix.fil_no,
                fil_year: dcCaseDetailsNapix.fil_year,
                case_type: dcCaseDetailsNapix.case_type,
                type_name: dcCaseDetailsNapix.type_name,
                pet_name: dcCaseDetailsNapix.pet_name,
                res_name: dcCaseDetailsNapix.res_name,
                dist_code: dcCaseDetailsNapix.dist_code,
                dist_name: dcCaseDetailsNapix.establishment_name,
                establishment_code: dcCaseDetailsNapix.establishment_code,
                establishment_name: dcCaseDetailsNapix.district_name,
                cino: dcCaseDetailsNapix.cino 
            }
        };

        // Send to controller to calculate and store in session
        const response = await fetch("{{ route('dc.store.session') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const result = await response.json();
            // console.log(result.location);
            window.location.href = result.location;
        } else {
            const error = await response.text();
            console.error("Server error:", error);
            // alert("Something went wrong. Please try again.");
        }
    }
</script>


@endpush