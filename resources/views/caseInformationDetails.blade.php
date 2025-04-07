@extends('public_layouts.app')

@section('content')

@if(session()->has('PendingCaseInfoDetails'))
    @include('pendingHCPayment')
@else
    <section class="content-section flex flex-col gap-4">
        <!-- Case Information & Orders -->
        <div class="w-full rounded-md -mt-10">
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
                    @php
                        $caseInfo = session('responseData.cases')[0] ?? [];
                    @endphp

                    @if (!empty($caseInfo))
                        <tr>
                            <td class="border p-2 font-bold">Filing Number</td>
                            <td class="border p-2">{{ $caseInfo['fillingno'] ?? 'N/A' }}</td>
                            <td class="border p-2 font-bold">Case Number</td>
                            <td class="border p-2">{{ $caseInfo['caseno'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="border p-2 font-bold">CNR Number</td>
                            <td class="border p-2">{{ $caseInfo['cino'] ?? 'N/A' }}</td>
                            <td class="border p-2 font-bold">Case Status</td>
                            <td class="border p-2">{{ $caseInfo['casestatus'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="border p-2 font-bold">Petitioner Name</td>
                            <td class="border p-2">{{ $caseInfo['pet_name'] ?? 'N/A' }}</td>
                            <td class="border p-2 font-bold">Respondent Name</td>
                            <td class="border p-2">{{ $caseInfo['res_name'] ?? 'N/A' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="p-2 border text-center text-gray-500">No Case Information Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Orders Table -->
            <h2 class="text-md font-semibold mt-4 mb-3">Orders</h2>
            <div class="overflow-x-auto">
                @php
                    $caseInfoDetails = session('caseInfoDetails', []);
                    $totalAmount = 0;
                @endphp

            <table class="w-full border border-gray-300 text-sm" id="ordersTable">
                <thead>
                    <tr class="bg-[#4B3D2F] text-white text-left text-md">
                        <th class="py-2 px-2 border">Order No</th>
                        <th class="py-2 px-2 border">Date</th>
                        <th class="py-2 px-2 border">Pages</th>
                        <th class="py-2 px-2 border">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($caseInfoDetails['selectedOrders']) && is_array($caseInfoDetails['selectedOrders']))
                    @php
                        $test = 0; 
                    @endphp

                    @foreach($caseInfoDetails['selectedOrders'] as $order)
                        @php
                            $amount = isset($order['amount'])
                                ? (float) preg_replace('/[^\d.]/', '', $order['amount'])
                                : 0;
                           
                            $test += $amount; // Accumulate amount
                        @endphp
                        <tr>
                            <td class="border p-2">{{ $order['orderNumber'] ?? 'N/A' }}</td>
                            <td class="border p-2">{{ $order['orderDate'] ?? 'N/A' }}</td>
                            <td class="border p-2">{{ $order['numPages'] ?? 'N/A' }}</td>
                            <td class="border p-2 text-green-500 font-bold">₹{{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                  @else
                        <tr>
                            <td colspan="4" class="p-2 border text-center text-gray-500">No orders available</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            </div>
        </div>

        <!-- Applicant Details -->
        <div class="w-full dark_form rounded-md">
            <h2 class="text-lg font-semibold mb-3">Applicant Details</h2>
            <table class="w-full border border-gray-300">
                <tbody id="applicantDetails">
                    @php
                        $caseInfo = session('caseInfoDetails', []);
                        $urgentFee = session('urgent_fee');
                        $totalAmount = 0;
                    @endphp

                    @if(!empty($caseInfo))
                        <tr>
                            <td class="border p-2 font-bold">Name</td>
                            <td class="border p-2">{{ $caseInfo['name'] ?? 'N/A' }}</td>
                            <td class="border p-2 font-bold">Mobile</td>
                            <td class="border p-2">{{ $caseInfo['mobile'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="border p-2 font-bold">Email</td>
                            <td class="border p-2">{{ $caseInfo['email'] ?? 'N/A' }}</td>
                            <td class="border p-2 font-bold">Applied By</td>
                            <td class="border p-2 capitalize">{{ $caseInfo['selectedValue'] ?? 'N/A' }}</td>
                        </tr>

                        @if(!empty($caseInfo['adv_res']))
                            <tr>
                                <td class="border p-2 font-bold">Advocate Registration</td>
                                <td class="border p-2">{{ $caseInfo['adv_res'] }}</td>
                                <td class="border p-2 font-bold">Request Mode</td>
                                <td class="border p-2">
                                    <span class="px-3 py-1 rounded-md text-white {{ ($caseInfo['requestMode'] ?? '') === 'urgent' ? 'bg-red-500' : 'bg-green-500' }}">
                                        {{ ucfirst($caseInfo['requestMode'] ?? 'N/A') }}
                                    </span>
                                    @if(($caseInfo['requestMode'] ?? '') === 'urgent')
                                        <span class="text-xs ml-2">(Urgent Fee ₹{{ $urgentFee }})</span>
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="border p-2 font-bold">Request Mode</td>
                                <td class="border p-2">
                                    <span class="px-3 py-1 rounded-md text-white {{ ($caseInfo['requestMode'] ?? '') === 'urgent' ? 'bg-red-500' : 'bg-green-500' }}">
                                        {{ ucfirst($caseInfo['requestMode'] ?? 'N/A') }}
                                    </span>
                                    @if(($caseInfo['requestMode'] ?? '') === 'urgent')
                                        <span class="text-xs ml-2">(Urgent Fee ₹{{ $urgentFee }})</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="4" class="p-2 border text-center text-gray-500">No Applicant Details Available</td>
                        </tr>
                    @endif
                </tbody>

                <!-- Total Payable Amount Row -->
                <tr>
                    <td class="border p-2 font-bold">Total Payable Amount</td>
                    <td class="border p-2 text-green-500" colspan="3">
                         @php 
                            if (($caseInfo['requestMode'] ?? '') === 'urgent') {
                                $test += $urgentFee; 
                            }
                        @endphp
                        ₹{{ number_format($test, 2) }}
                    </td>
                </tr>
            </table>

            <div class="flex justify-end items-start w-full gap-3 mt-2 sm:mb-0 mb-20">
                <!-- <button class="order_btn bg-sky-500 w-[200px] text-white p-3 rounded-md hover:bg-sky-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="editUserDetails()">Edit details</button> -->
                <button class="order_btn bg-green-500 w-[200px] text-white p-3 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="submitUserDetails(event)">
                    Pay now
                </button>
            </div>
        </div>
       
        <form name="eGrassClient" method="POST" action="https://jkuberuat.jharkhand.gov.in/jegras/deptuattest/uatpaymentpg.aspx">
            <input type="hidden" name="requestparam" value="">
            <input type="submit" value="Submit" class="hidden">
        </form>
    </section>
@endif

@endsection

@push('scripts')

<script>
    async function submitUserDetails(event) {
    event.preventDefault();
    const sessionData = @json(session()->all());

    try {
        const userData = sessionData;

        // console.log('Submit Order Copy Details:', userData);

        // Check if session data is available
        if (!userData || !userData.responseData || !userData.responseData.cases || userData.responseData.cases.length === 0) {
            console.error("Session data or case data is missing");
            alert("Error: Case data is missing.");
            return;
        }

        const caseData = userData.responseData.cases[0]; 

        // console.log('caseData to send in api', caseData);

        const casenoss = caseData?.caseno || "";
        const fillingnoss = caseData?.fillingno || "";

        const matchCaseNo = casenoss.match(/\/(\d+)\/?/);
        const matchCaseYear = casenoss.match(/\/(\d{4})$/);
        const matchFilingNo = fillingnoss.match(/\/(\d+)\/?/);
        const matchFilingYear = fillingnoss.match(/\/(\d{4})$/);

        const caseNumber = matchCaseNo ? matchCaseNo[1] : "";
        const caseYear = matchCaseYear ? matchCaseYear[1] : "";
        const filingNumber = matchFilingNo ? matchFilingNo[1] : "";
        const filingYear = matchFilingYear ? matchFilingYear[1] : caseYear;

        const requestData = {
            applicant_name: userData.caseInfoDetails.name,
            mobile_number: userData.caseInfoDetails.mobile,
            email: userData.caseInfoDetails.email,
            petitioner_name: userData.caseInfoDetails.petitioner_name,
            respondent_name: userData.caseInfoDetails.respondent_name,
            case_type: caseData?.casetype || "",
            filingcase_type: caseData?.filingcasetype || "",
            case_number: caseNumber,
            filing_number: filingNumber,
            case_year: caseYear,
            filing_year: filingYear,
            request_mode: userData.caseInfoDetails.requestMode,
            applied_by: userData.caseInfoDetails.selectedValue,
            cino: caseData?.cino || "",
            advocate_registration_number: userData.caseInfoDetails.adv_res || null,
            order_details: userData.caseInfoDetails.selectedOrders ? userData.caseInfoDetails.selectedOrders.map((order, index) => ({
                order_no: index + 1,
                order_date: order.orderDate,
                case_number: caseNumber,
                filing_number: filingNumber,
                page_count: parseInt(order.numPages, 10) || 0,
                amount: parseFloat(order.amount?.replace(/[^\d.]/g, '') || "0")
            })) : []
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
            console.error("Error in submit User Details:", error);
            alert("An error occurred while submitting the request.");
        }
        async function paymentToMerchant(event, applicationNumber) {
        event.preventDefault();

        try {
            const sessionData = @json(session()->all());
            // console.log('all session data', sessionData);

            if (!sessionData) {
                alert("Error fetching session data.");
                return;
            }

            const userData = sessionData.caseInfoDetails;
            // console.log('payment userdata',userData)
            if (!userData) {
                alert("Error: User data is missing. Please refresh and try again.");
                return;
            }
            
            let paybleAmount = 0;

            if (userData.selectedOrders && userData.selectedOrders.length > 0) {
                paybleAmount = userData.selectedOrders.reduce((total, order) => {
                    const orderAmount = parseFloat(order.amount?.replace(/[^\d.]/g, '') || "0");
                    return total + orderAmount;
                }, 0);
            

            }
            const urgentFee = sessionData.urgent_fee || 0; 
            const requestMode = userData.requestMode || '';
            
            // console.log('request mode', requestMode,urgentFee)

            if (requestMode === 'urgent') {
                paybleAmount = Number(paybleAmount) + Number(urgentFee);
                // console.log('pay amt',paybleAmount);
            }

            // console.log("Calculated Payable Amount:", paybleAmount);

            if (paybleAmount <= 0) {
                alert("Error calculating payable amount.");
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
                // console.log("Application Number:", data.application_number);

                const form = document.querySelector('form[name="eGrassClient"]');
                if (form) {
                    form.querySelector('input[name="requestparam"]').value = data.enc_val;
                    alert('Entered to transaction details');
                    // form.submit();
                    window.location.href='/api/occ/gras_resp_cc';
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
    }
</script>

@endpush