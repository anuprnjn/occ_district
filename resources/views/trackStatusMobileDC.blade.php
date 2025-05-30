@extends('public_layouts.app')

@section('content')

<section class="content-section min-h-[65vh]">
    <h3 class="font-semibold text-xl mb-6 -mt-6">Application History - Civil Court</h3>

    @if(session()->has('trackDetailsMobileDC'))
        @php
            $hcData = session('trackDetailsMobileDC')['data'];
            $orderCopies = $hcData['order_copy'];
            $otherCopies = $hcData['other_copy'];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-{{ (count($orderCopies) && count($otherCopies)) ? '2' : '1' }} gap-6">

            @if(count($otherCopies))
            <!-- Other Copies -->
            <div>
                <h4 class="text-lg font-semibold mb-6 text-[#D09A3F]">Applied other copies</h4>
                <div class="overflow-x-auto rounded-lg border border-gray-300">
                    <table class="min-w-full table-auto">
                        <thead class="bg-[#4B3E2F] text-white text-sm">
                            <tr>
                                <th class="px-3 py-1 border text-left font-normal">S.No.</th>
                                <th class="px-3 py-1 border text-left font-normal">Application No</th>
                                <th class="px-3 py-1 border text-left font-normal">Name</th>
                                <th class="px-3 py-1 border text-left font-normal">Status</th>
                                <th class="px-3 py-1 border text-left font-normal">Case Type</th>
                                <th class="px-3 py-1 border text-left font-normal">Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($otherCopies as $copy)
                                <tr class="text-sm">
                                    <td class="px-3 py-2 border">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2 border">
                                        @php
                                            $encodedAppNo = base64_encode($copy['application_number']);
                                        @endphp
                                        <a href="{{ url('trackStatusDetails') }}?application_number={{ $encodedAppNo }}" 
                                        class="text-emerald-500 font-semibold border-b border-emerald-500">
                                        {{ $copy['application_number'] }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 border">{{ $copy['applicant_name'] }}</td>
                                    <td class="px-3 py-2 border">{{ $copy['application_status'] }}</td>
                                    <td class="px-3 py-2 border">{{ $copy['case_type'] }}</td>
                                    <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(count($orderCopies))
            <!-- Order Copies -->
            <div>
                <h4 class="text-lg font-semibold mb-6 text-[#D09A3F]">Applied order copies</h4>
                <div class="overflow-x-auto rounded-lg border border-gray-300">
                    <table class="min-w-full table-auto">
                        <thead class="bg-[#4B3E2F] text-white text-sm">
                            <tr>
                                <th class="px-3 py-1 border text-left font-normal">S.No.</th>
                                <th class="px-3 py-1 border text-left font-normal">Application No</th>
                                <th class="px-3 py-1 border text-left font-normal">Name</th>
                                <th class="px-3 py-1 border text-left font-normal">Status</th>
                                <th class="px-3 py-1 border text-left font-normal">Case Type</th>
                                <th class="px-3 py-1 border text-left font-normal">Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderCopies as $copy)
                                <tr class="text-sm">
                                    <td class="px-3 py-2 border">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2 border">
                                        @php
                                            $encodedAppNo = base64_encode($copy['application_number']);
                                        @endphp
                                        <a href="{{ url('trackStatusDetails') }}?application_number={{ $encodedAppNo }}" 
                                        class="text-emerald-500 font-semibold border-b border-emerald-500">
                                        {{ $copy['application_number'] }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 border">{{ $copy['applicant_name'] }}</td>
                                    <td class="px-3 py-2 border">{{ $copy['application_status'] }}</td>
                                    <td class="px-3 py-2 border">{{ $copy['case_type'] }}</td>
                                    <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

    @else
        <p class="text-[#D09A3F] text-2xl mt-4 font-bold">404 Data not found!</p>
    @endif

</section>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const trackData = @json(session('trackDetailsMobileDC'));
        if (!trackData) {
            window.location.href = "/";
        }
    });
</script>
@endpush
