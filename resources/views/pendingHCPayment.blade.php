@extends('public_layouts.app')

@section('content')
<section class="content-section">
    <!-- Case Information Section -->
    <div class="w-full rounded-md -mt-8">
        
        <span class="text-xl text-red-500 uppercase font-extrabold">Payment is Pending for the application number &nbsp;<span class="text-teal-500">( {{ session('PendingCaseInfoDetails.case_info.application_number') ?? 'N/A' }} )</span> </span>
        <div class="flex items-center">
                <span class="text-lg font-semibold mb-3 w-[18%] mt-4 text-[#D09A3F]">Case Information</span>
                <marquee behavior="" direction="left" class="w-full -mt-1">
                    <span class="text-red-500 text-md font-extrabold" style="font-weight: 900;">
                        ( NOTE: It is advised to pay through Netbanking/BHIM UPI with the SBI epay gateway option )
                    </span>
                </marquee>
            </div>
        <!-- <pre>{{ print_r(session('PendingCaseInfoDetails'))}}</pre> -->
        @if(session('PendingCaseInfoDetails.order_details'))
        <table class="w-full border border-gray-300">
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
        @else
        <table class="w-full border border-gray-300">
            <tr>
                <td class="border p-2 font-bold">
                    {{ session('PendingCaseInfoDetails.case_info.selected_method') == 'C' ? 'Case Number' : 'Filling Number' }}
                </td>
                <td class="border p-2">
                    {{ session('PendingCaseInfoDetails.case_info.selected_method') == 'C' 
                        ? session('PendingCaseInfoDetails.case_info.CASENO') ?? 'N/A' 
                        : session('PendingCaseInfoDetails.case_info.FILLINGNO') ?? 'N/A' 
                    }}
                </td>
            </tr>
        </table>
        @endif
    </div>
    <!-- Orders Table -->
    @if(session('PendingCaseInfoDetails.order_details'))
    <h2 class="text-md font-semibold mt-4 mb-3">Orders</h2>
    @else
        <h2 class="text-md font-semibold mt-4 mb-3">Documents</h2>
    @endif

    <div class="overflow-x-auto">
            @if(session('PendingCaseInfoDetails.order_details'))
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
                            <td class="py-2 px-2 border text-green-500">₹{{ $order['amount'] ?? '0.0' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-2 px-2 border text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @elseif(session('PendingCaseInfoDetails.document_details'))
            <table class="w-full border border-gray-300 text-sm" id="documentsTable">
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
            </table>
        @else
            <p class="text-center text-gray-500 py-4">No pending case information available.</p>
        @endif
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