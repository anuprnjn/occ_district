@extends('public_layouts.app')

@section('content')
<section class="content-section">
    <!-- Case Information Section -->
    <div class="w-full rounded-md -mt-8">
        <!-- <span class="mb-2 flex flex-col sm:flex-row items-start sm:items-center gap-2 py-2 px-3 rounded-xl border uppercase border-red-300 bg-red-50 text-red-600 shadow-sm text-sm sm:text-base leading-snug">
        Payment is pending for the CINO 
        <span class="text-teal-500 font-semibold">
                ({{ session('dc_review_form_userDetails.case_details.cino') ?? 'N/A' }})
            </span>
        </span> -->
        <div class="flex items-center">
                <span class="text-lg font-semibold mb-3 w-[30%] mt-2 text-[#D09A3F]">Case Information (Civil Court)</span>
                <marquee behavior="" direction="left" class="w-full -mt-1">
                    <span class="text-red-500 text-md font-extrabold" style="font-weight: 900;">
                        ( NOTE: It is advised to pay through Netbanking/BHIM UPI with the SBI epay gateway option )
                    </span>
                </marquee>
            </div>
            
        <!-- <pre>{{ print_r(session('PendingCaseInfoDetails'))}}</pre> -->
        @if(session('dc_review_form_userDetails.orders'))
        <table class="w-full border border-gray-300">
             <tr>
                <td class="border p-2 font-bold">District Name</td>
                <td class="border p-2">{{ session('dc_review_form_userDetails.case_details.dist_name') ?? 'N/A' }}</td>
                <td class="border p-2 font-bold">Establishment Name</td>
                <td class="border p-2">{{ session('dc_review_form_userDetails.case_details.establishment_name') ?? 'N/A' }}</td>
            </tr>
           
            <tr>
                @php
                    $filNo = session('dc_review_form_userDetails.case_details.fil_no');
                    $filYear = session('dc_review_form_userDetails.case_details.fil_year');
                    $isFilingAvailable = !empty($filNo) && !empty($filYear);
                @endphp
                    <td class="border p-2 font-bold">
                        {{ $isFilingAvailable ? 'Filing Number' : 'Case Number' }}
                    </td>
                    <td class="border p-2">
                        {{
                            (session('dc_review_form_userDetails.case_details.type_name') ?? 'N/A') . '/' .
                            ($isFilingAvailable ? $filNo : (session('dc_review_form_userDetails.case_details.case_no') ?? 'N/A')) . '/' .
                            ($isFilingAvailable ? $filYear : (session('dc_review_form_userDetails.case_details.case_year') ?? 'N/A'))
                        }}
                    </td>
                    <td class="border p-2 font-bold">CINO</td>
                    <td class="border p-2">{{ session('dc_review_form_userDetails.case_details.cino') ?? 'N/A' }}
                    </td>
            </tr>
            <tr>
                <td class="border p-2 font-bold">Petitioner Name</td>
                <td class="border p-2">{{ session('dc_review_form_userDetails.case_details.pet_name') ?? 'N/A' }}</td>
                <td class="border p-2 font-bold">Respondent Name</td>
                <td class="border p-2">{{ session('dc_review_form_userDetails.case_details.res_name') ?? 'N/A' }}</td>
            </tr>
        </table>
        @else
        <table class="w-full border border-gray-300">
            <tr>
                <td class="border p-2 font-bold">
                    {{ session('PendingCaseInfoDetails.case_info.selected_method') == 'C' ? 'Case Number' : 'Filling Number' }}
                </td>
                <td class="border p-2">
                    {{ session('PendingCaseInfoDetails.case_info.CASENO') ?? 'N/A' }}
                </td>
            </tr>
        </table>
        @endif
    </div>
    <!-- Orders Table -->
    @if(session('dc_review_form_userDetails.orders'))
    <h2 class="text-md font-semibold mt-4 mb-3">Order Details</h2>
    @else
        <h2 class="text-md font-semibold mt-4 mb-3">Documents</h2>
    @endif

    <div class="overflow-x-auto">
           @if(session('dc_review_form_userDetails.orders'))
            <table class="w-full border border-gray-300 text-sm" id="ordersTable">
                <thead>
                    <tr class="bg-[#4B3D2F] text-white text-left text-md">
                        <th class="py-2 px-2 border">Order No</th>
                        <th class="py-2 px-2 border">Order Date</th>
                        <th class="py-2 px-2 border">Pages</th>
                        <th class="py-2 px-2 border">Amount</th>
                    </tr>
                </thead>
                <!-- code to loop the orders  -->
                <tbody>
                    @forelse(session('dc_review_form_userDetails.orders') ?? [] as $order)
                        <tr>
                            <td class="py-2 px-2 border font-bold">{{ $order['order_no'] ?? 'N/A' }}</td>
                            <td class="py-2 px-2 border font-bold">{{ $order['order_date'] ?? 'N/A' }}</td>
                            <td class="py-2 px-2 border font-bold">{{ $order['pages'] ?? '0' }}</td>
                            <td class="py-2 px-2 border font-bold text-green-500">₹{{ $order['amount'] ?? '0.0' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-2 px-2 border text-center mt-2 text-red-500">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @elseif(session('dc_review_form_userDetails.document_details'))
            <!-- <table class="w-full border border-gray-300 text-sm" id="documentsTable">
                <thead>
                    <tr class="bg-[#4B3D2F] text-white text-left text-md">
                        <th class="py-2 px-2 border">Document Type</th>
                        <th class="py-2 px-2 border">Pages</th>
                        <th class="py-2 px-2 border">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(session('PendingCaseInfoDetails.document_details') ?? [] as $document)
                        <tr>
                            <td class="py-2 px-2 border">{{ $document['document_type'] ?? 'N/A' }}</td>
                            <td class="py-2 px-2 border">{{ $document['number_of_page'] ?? '0' }}</td>
                            <td class="py-2 px-2 border text-green-500">₹{{ $document['amount'] ?? '0.0' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-2 px-2 border text-center">No documents found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table> -->
        @else
            <p class="text-center text-gray-500 py-4">No pending case information available.</p>
        @endif
    </div>

    <!-- User Details Section -->
    <h2 class="text-lg font-semibold mt-4 mb-3">Applicant Details</h2>
    <table class="w-full border border-gray-300">
        <tr>
            <td class="border p-2 font-bold">Name</td>
            <td class="border p-2">{{ session('dc_review_form_userDetails.user_info.name') ?? 'N/A' }}</td>
            <td class="border p-2 font-bold">Email</td>
            <td class="border p-2">{{ session('dc_review_form_userDetails.user_info.email') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="border p-2 font-bold">Mobile</td>
            <td class="border p-2">{{ session('dc_review_form_userDetails.user_info.mobile') ?? 'N/A' }}</td>
            <td class="border p-2 font-bold">Applied By</td>
            <td class="border p-2 capitalize">{{ session('dc_review_form_userDetails.user_info.applied_by') ?? 'N/A' }}</td>
        </tr>
        @if(session('dc_review_form_userDetails.user_info.applied_by') === 'advocate')
            <tr>
                <td class="border p-2 font-bold">Advocate Registration Number</td>
                <td class="border p-2" colspan="3">{{ session('dc_review_form_userDetails.user_info.adv_reg_no') ?? 'N/A' }}</td>
            </tr>
        @endif
        <tr>
            <td class="border p-2 font-bold">Request Mode</td>
            <td class="border p-2">
        <span class="py-1 rounded  
            @if(session('dc_review_form_userDetails.user_info.request_mode') == 'ordinary') capitalize
            @elseif(session('dc_review_form_userDetails.user_info.request_mode') == 'urgent') capitalize
            @endif">
            {{ session('dc_review_form_userDetails.user_info.request_mode') ?? 'N/A' }}
        </span>

    @if(session('dc_review_form_userDetails.user_info.request_mode') == 'urgent')
    <span class="ml-2 text-sm text-red-500 font-bold">
        (Urgent Fee: ₹{{ session('dc_review_form_userDetails.urgent_fee') ?? 'N/A' }})
    </span>
    @endif
    <td class="border p-2 font-bold select-none">Total Payable Amount</td>
    <td class="border p-2">
        <span id="totalAmount" class="bg-green-600 text-white rounded-md px-4 py-1">
            ₹ Calculating...
        </span>
    </td>
    </tr>
    </table>

    <!-- Pay Now Button -->
    <div class="flex justify-end items-center mt-4">
        <button class="order_btn bg-green-500 w-[200px] text-white p-3 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 mt-4 uppercase" onclick="saveDCOrderDetailsPay(event)">
            Pay Now
        </button>
    </div>
    <form name="eGrassClient" method="POST" action="https://jkuberuat.jharkhand.gov.in/jegras/deptuattest/uatpaymentpg.aspx">
        <input type="hidden" name="requestparam" value="">
        <input type="submit" value="Submit" class="hidden">
    </form>
</section>
@endsection

@push('scripts')

<!-- function to calculate the total amount and showing it  -->

<script>
    document.addEventListener("DOMContentLoaded", async function () {
        const selectedOrders = @json(session('dc_review_form_userDetails.orders'));
        const request_mode = @json(session('dc_review_form_userDetails.user_info.request_mode'));

        if (!selectedOrders || selectedOrders.length === 0) {
            document.getElementById("totalAmount").textContent = "₹ N/A";
            return;
        }

        try {
            const response = await fetch("{{ route('calculate-dc-final-amount') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ orders: selectedOrders, request_mode: request_mode })
            });

            const result = await response.json();

            if (result.status === 'success') {
                document.getElementById("totalAmount").textContent = `₹ ${result.data.final_total_amount_DC}`;
            } else {
                document.getElementById("totalAmount").textContent = "₹ Error";
            }

        } catch (error) {
            console.error("Error sending orders to controller:", error);
            document.getElementById("totalAmount").textContent = "₹ Failed";
        }
    });
</script> 

<!-- function to send the user details into the dc_order_copy_applicant_registration table  -->

<script>
    function saveDCOrderDetailsPay(event) {
        event.preventDefault();

        // Get all values from session
        const name = @json(session('dc_review_form_userDetails.user_info.name'));
        const email = @json(session('dc_review_form_userDetails.user_info.email'));
        const mobile = @json(session('dc_review_form_userDetails.user_info.mobile'));
        const adv_reg_no = @json(session('dc_review_form_userDetails.user_info.adv_reg_no'));
        const applied_by = @json(session('dc_review_form_userDetails.user_info.applied_by'));
        const request_mode = @json(session('dc_review_form_userDetails.user_info.request_mode'));

        const case_type = @json(session('dc_review_form_userDetails.case_details.case_type'));
        // const case_no = @json(session('dc_review_form_userDetails.case_details.case_no')) || "";
        // const case_year = @json(session('dc_review_form_userDetails.case_details.case_year')) || "";
        // const fil_no = @json(session('dc_review_form_userDetails.case_details.fil_no')) || "";
        // const fil_year = @json(session('dc_review_form_userDetails.case_details.fil_year')) || "";
        const cino = @json(session('dc_review_form_userDetails.case_details.cino'));
        const dist_code = @json(session('dc_review_form_userDetails.case_details.dist_code'));
        const est_code = @json(session('dc_review_form_userDetails.case_details.establishment_code'));
        const pet_name = @json(session('dc_review_form_userDetails.case_details.pet_name'));
        const res_name = @json(session('dc_review_form_userDetails.case_details.res_name'));
        const case_status = @json(session('DcCaseDetailsNapix.case_status'));
        const case_no = @json(session('DcCaseDetailsNapix.case_number'));
        const case_year = @json(session('DcCaseDetailsNapix.case_year'));
        const fil_no = @json(session('DcCaseDetailsNapix.filling_number'));
        const fil_year = @json(session('DcCaseDetailsNapix.filling_year'));

        const orderDetails = @json(session('dc_review_form_userDetails.orders'));
        const urgent_fee = @json(session('dc_review_form_userDetails.urgent_fee'));

        // Transform orderDetails to match API schema
        const transformedOrders = orderDetails.map(order => ({
            order_no: order.order_no,
            order_date: order.order_date,
            case_number: order.case_no,
            filing_number: order.fil_no || "",
            page_count: order.pages,
            amount: order.amount
        }));

        // Prepare final data payload
        const data = {
            applicant_name: name,
            mobile_number: mobile,
            email: email,
            case_type: case_type,
            case_number: case_no,
            case_year: case_year,
            filing_number: fil_no,
            filing_year: fil_year,
            case_status: case_status,
            request_mode: request_mode,
            district_code: dist_code,
            establishment_code: est_code,
            cino: cino,
            applied_by: applied_by,
            advocate_registration_number: adv_reg_no || "",
            petitioner_name: pet_name,
            respondent_name: res_name,
            order_details: transformedOrders,
            urgent_fee: urgent_fee
        };

        // Send to Laravel route
        fetch("{{ route('dc-order.submit') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            // console.log('API Response:', res);
            if (res.success) {
                alert(`Success! Application No: ${res.application_number}`);
                // return;
                paymentToMerchant(event, res.application_number,res.district_code,res.establishment_code);
            } else {
                alert(`Failed: ${res.message || 'Unexpected error'}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the data.');
        });
    }
</script> 

<!-- funtion to secure the total amount before sending into the jegras payment merchant  -->

<script>
    async function paymentToMerchant(event, dc_application_number_oc,district_code, establishment_code) {
        event.preventDefault();

        try {
            const initiateResponse = await fetch("{{ route('initiate.dc.payment') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    dc_application_number: dc_application_number_oc,
                })
            });

            const data = await initiateResponse.json();

            const Dc_totalAmount = data.amount;
            const Dc_userName = @json(session('dc_review_form_userDetails.user_info.name'));
            const Dc_application_number = dc_application_number_oc;

            const payload = {
                Dc_userName,
                Dc_totalAmount,
                Dc_application_number,
                paybleAmount: Dc_totalAmount,
                applicationNumber: Dc_application_number,
                district_code: district_code,
                establishment_code: establishment_code
                // sending dist_code and est_code 
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const merchantResponse = await fetch("/fetch-merchant-details", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(payload)
            });

            const merchantData = await merchantResponse.json();

            if (merchantData.enc_val && merchantData.application_number) {
                const form = document.querySelector('form[name="eGrassClient"]');
                if (form) {
                    form.querySelector('input[name="requestparam"]').value = merchantData.enc_val;
                    alert('Entered to transaction details');
                    // form.submit();
                    window.location.href='/api/occ/gras_resp_cc';
                } else {
                    console.error("Form 'eGrassClient' not found!");
                    alert("Payment form not found. Please try again.");
                }
            } else {
                console.error("Invalid merchant response:", merchantData);
            }

        } catch (err) {
            console.error("Payment init error:", err);
        }
    }
</script>  

@endpush