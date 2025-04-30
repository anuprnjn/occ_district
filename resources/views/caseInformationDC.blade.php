@extends('public_layouts.app')

@section('content')

<section class="content-section flex flex-wrap sm:flex-nowrap items-start justify-between gap-6 p-4 border-t">
    <!-- First Section -->
    <div class="w-full sm:w-2/3 dark_form p-4 rounded-md">
        
        <h2 class="text-xl font-semibold mt-0 mb-2">Case Information</h2>
        <div id="caseDetails">
            <p class="text-green-500 animate-pulse">Loading case details...</p>
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
                    onclick="alert('Clicked button)">
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
            const responseData = data?.session_data?.responseData;
            if (!responseData) {
                throw new Error('Missing responseData!');
            }

            // ** Display Case Details **
            const caseDetailsDiv = document.getElementById("caseDetails");
            const caseInfo = responseData.cases?.[0];

            if (caseInfo) {
                caseDetailsDiv.innerHTML = `
                <div class="rounded-xl caseInfoShowCaseDetails p-4 ">
                    ${caseInfo ? `
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Filing Number</h6>
                                    <h6 class="font-semibold">${caseInfo.fillingno || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Case Number</h6>
                                    <h6 class="font-semibold">${caseInfo.caseno || 'N/A'}</h6>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">CNR Number</h6>
                                    <h6 class="font-semibold break-all">${caseInfo.cino || 'N/A'}</h6>
                                </div>
                                <div>
                                    <h6 class="text-sm text-gray-500 mb-1">Case Status</h6>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${
                                        caseInfo.casestatus?.toLowerCase() === 'disposed' 
                                        ? 'bg-red-100 text-red-800' 
                                        : 'bg-blue-100 text-blue-800'
                                    }">
                                        ${caseInfo.casestatus || 'N/A'}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

@endpush