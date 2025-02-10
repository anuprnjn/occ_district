@extends('public_layouts.app')

@section('content')

<section class="content-section flex flex-wrap sm:flex-nowrap items-start justify-between gap-6 p-4 border-t">
    <!-- First Section -->
    <div class="w-full sm:w-2/3 dark_form p-4 rounded-md">
        
        <h2 class="text-xl font-semibold mt-6 mb-4">Case Information</h2>
        <div id="caseDetails" class=" rounded p-4 bg-slate-100/70">
            <p class="text-gray-600">Loading case details...</p>
        </div>

        <h2 class="text-lg font-semibold mt-6 mb-4">Orders</h2>
        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full border border-gray-200" id="ordersTable">
                <thead>
                    <tr class="text-left">
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
    <div class=" dark_form w-full bg-slate-100/70 sm:w-1/3 p-4 rounded-md mt-10">
        <h3 class="text-xl font-bold mb-4">Payment</h3>
        
        <form action="#" class=" space-y-4">
            @csrf

            <div class="form-field">
                <label for="name" class="block font-medium">Name: <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" placeholder="ENTER YOUR NAME" required class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
            <label for="mobile" id="mobileLabel">Mobile No: <span>*</span></label>
            <span id="otpTimer"class="text-md text-rose-600 " ></span>
                <div class="flex items-center gap-2">
                    <input type="text" id="mobileInput" name="mobile" placeholder="Enter Your Mobile No"
                        class="flex-1 p-2 border rounded-md" required maxlength="10" onkeydown="return isNumber(event)">
                    <button type="button" id="otpButton" value="HC" onclick="sendOtp(value)"
                        class="bg-[#4B3E2F] w-[150px] p-2 rounded-md text-white hover:bg-[#D09A3F]">
                        Send OTP
                    </button>
                </div>
            </div>

            <div class="form-field">
                <label for="email" class="block font-medium">Email: <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email" required class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
                <label for="confirm-email" class="block font-medium">Confirm Email: <span class="text-red-500">*</span></label>
                <input type="email" id="confirm-email" name="confirm_email" placeholder="Enter Your Confirm Email" required class="w-full p-2 border rounded-md">
            </div>

            <div class="form-field">
                <label for="apply-by" class="block font-medium">Applied By: <span class="text-red-500">*</span></label>
                <select id="apply-by" name="apply_by" required class="w-full p-2 border rounded-md" onchange="toggleAdvocateField()">
                    <option value="">--Select--</option>
                    <option value="petitioner">Petitioner</option>
                    <option value="respondent">Respondent</option>
                    <option value="advocate">Advocate</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div class="form-field">
                <label class="block font-medium">Request Mode: <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-4">
                    <input type="radio" id="urgent" name="request_mode" value="urgent" required>
                    <label for="urgent">Urgent</label>
                    <input type="radio" id="ordinary" name="request_mode" value="ordinary" required class="ml-2">
                    <label for="ordinary">Ordinary</label>
                </div>
            </div>

            <div class="form-field">
                <button type="submit" id="submitBtn" class="w-full bg-[#4B3E2F] text-white p-3 rounded-md hover:bg-[#D09A3F]" 
                    onclick="handleFormSubmitForHighCourt(event)">
                    Submit
                </button>
            </div>
        </form>
    </div>
    <!-- Second Section Ends -->
</section>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const storedData = sessionStorage.getItem("caseInfo");
        console.log(storedData);
        if (storedData) {
            const data = JSON.parse(storedData);

            // Display case details
            const caseDetailsDiv = document.getElementById("caseDetails");
            caseDetailsDiv.innerHTML = `
            <p><strong>Filing Number:</strong> ${data.cases[0].fillingno}</p>
                <p><strong>Case Number:</strong> ${data.cases[0].caseno}</p>
                <p><strong>CNR Number:</strong> ${data.cases[0].cino}</p>
                <p><strong>Petitioner Name:</strong> ${data.cases[0].pet_name}</p>
                <p><strong>Respondent Name:</strong> ${data.cases[0].res_name}</p>
            `;

            // Display orders in table
            const ordersTable = document.querySelector("#ordersTable tbody");
            ordersTable.innerHTML = ""; // Clear existing content

            data.orders.forEach(order => {
                const row = `
                    <tr>
                        <td class="py-2 px-4 border"><input type="checkbox"/></td>
                        <td class="py-2 px-4 border">${order.order_no}</td>
                        <td class="py-2 px-4 border">${order.order_dt}</td>
                        <td class="py-2 px-4 border">${order.no_of_pages}</td>
                        <td class="py-2 px-4 border">${order.amount}</td>
                    </tr>
                `;
                ordersTable.innerHTML += row;
            });
        } else {
            document.getElementById("caseDetails").innerHTML = `<p class="text-red-500">Unable to load case details, please try again.</p>`;
        }
    });
</script>
@endpush