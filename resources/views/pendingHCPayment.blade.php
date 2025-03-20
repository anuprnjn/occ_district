@extends('public_layouts.app')

@section('content')
<section class="content-section">
    <!-- Case Information Section -->
    <div class="w-full dark_form rounded-md -mt-8">
        <span class="text-xl">Payment is Pending for the application number <span class="text-green-500">({{ session('PendingCaseInfoDetails.case_info.application_number') ?? 'N/A' }})</span> </span>
        <div class="flex items-center">
                <h2 class="text-lg font-semibold mb-3 w-[18%]">Case Information</h2>
                <marquee behavior="" direction="left" class="w-full -mt-1">
                    <span class="text-red-500 text-md font-extrabold" style="font-weight: 900;">
                        ( NOTE: It is advised to pay through Netbanking/BHIM UPI with the SBI epay gateway option )
                    </span>
                </marquee>
            </div>
        <!-- <pre>{{ print_r(session('PendingCaseInfoDetails'))}}</pre> -->
        <table class="w-full border border-gray-300">
            <tr>
                <td class="border p-2 font-bold">Application Number</td>
                <td class="border p-2" colspan=3>{{ session('PendingCaseInfoDetails.case_info.application_number') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-bold">Case Number</td>
                <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.CASENO') ?? 'N/A' }}</td>
                <td class="border p-2 font-bold">Filling Number</td>
                <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.FILLINGNO') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-bold">Petitioner Name</td>
                <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.petitioner_name') ?? 'N/A' }}</td>
                <td class="border p-2 font-bold">Respondent Name</td>
                <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.respondent_name') ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
    <!-- Orders Table -->
    <h2 class="text-md font-semibold mt-4 mb-3">Orders</h2>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm" id="ordersTable">
            <thead>
                <tr class="bg-[#4B3D2F] text-white text-left text-md">
                    <th class="py-2 px-2 border">Order No</th>
                    <th class="py-2 px-2 border">Order Date</th>
                    <th class="py-2 px-2 border">Pages</th>
                    <th class="py-2 px-2 border">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse(session('PendingCaseInfoDetails.order_details') ?? [] as $order)
                <tr>
                    <td class="py-2 px-2 border">{{ $order['order_number'] ?? 'N/A' }}</td>
                    <td class="py-2 px-2 border">{{ $order['order_date'] ?? 'N/A' }}</td>
                    <td class="py-2 px-2 border">{{ $order['number_of_page'] ?? '0' }}</td>
                    <td class="py-2 px-2 border">{{ $order['amount'] ?? '0.00' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-2 px-2 border text-center">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- User Details Section -->
    <h2 class="text-lg font-semibold mt-4 mb-3">Applicant Details</h2>
    <table class="w-full border border-gray-300">
        <tr>
            <td class="border p-2 font-bold">Name</td>
            <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.applicant_name') ?? 'N/A' }}</td>
            <td class="border p-2 font-bold">Email</td>
            <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.email') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="border p-2 font-bold">Mobile</td>
            <td class="border p-2">{{ session('PendingCaseInfoDetails.case_info.mobile_number') ?? 'N/A' }}</td>
            <td class="border p-2 font-bold">Applied By</td>
            <td class="border p-2 capitalize">{{ session('PendingCaseInfoDetails.case_info.applied_by') ?? 'N/A' }}</td>
        </tr>
        @if(session('PendingCaseInfoDetails.case_info.applied_by') === 'advocate')
            <tr>
                <td class="border p-2 font-bold">Advocate Registration Number</td>
                <td class="border p-2" colspan="3">{{ session('PendingCaseInfoDetails.case_info.advocate_registration_number') ?? 'N/A' }}</td>
            </tr>
        @endif
        <tr>
            <td class="border p-2 font-bold">Request Mode</td>
            <td class="border p-2">
    <span class="py-1 rounded  
        @if(session('PendingCaseInfoDetails.case_info.request_mode') == 'ordinary') capitalize
        @elseif(session('PendingCaseInfoDetails.case_info.request_mode') == 'urgent') capitalize
        @endif">
        {{ session('PendingCaseInfoDetails.case_info.request_mode') ?? 'N/A' }}
    </span>

    @if(session('PendingCaseInfoDetails.case_info.request_mode') == 'urgent')
        <span class="ml-2 text-sm text-gray-600">
            (Urgent Fee: ₹{{ session('PendingCaseInfoDetails.case_info.urgent_fee') ?? 'N/A' }})
        </span>
    @endif
</td>
            <td class="border p-2 font-bold">Total Payble Amount</td>
            <td class="border p-2">
                <span class="bg-green-600 text-white rounded-md px-4 py-1">₹{{ session('PendingCaseInfoDetails.case_info.payable_amount') ?? 'N/A' }}</span>
            </td>
        </tr>
    </table>

    

    <!-- Pay Now Button -->
    <div class="flex justify-end items-center mt-4">
        <button class="order_btn bg-green-500 w-[200px] text-white p-3 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 mt-4 uppercase">
            Pay Now
        </button>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add any necessary JS scripts here
</script>   
@endpush