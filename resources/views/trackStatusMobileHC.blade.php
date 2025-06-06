@extends('public_layouts.app')

@section('content')

<section class="content-section min-h-[65vh] px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-center sm:-mb-4">
        <h3 class="font-semibold text-xl mb-6 -mt-6 sm:-mt-10 text-center sm:text-left w-full sm:w-auto">
            Application Details - High Court
        </h3>



        @if(session()->has('trackDetailsMobileHC'))
            @php
                $hcData = session('trackDetailsMobileHC')['data'];

                // Merge all copies
                $allCopies = collect($hcData['order_copy'] ?? [])
                    ->merge($hcData['other_copy'] ?? [])
                    ->sortByDesc('created_at')
                    ->values();

                // Group by created_at date
                $groupedByDate = $allCopies->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d');
                });

                $latestDate = $groupedByDate->keys()->first();
                $latestDateApplications = $groupedByDate[$latestDate] ?? collect();
                $previousApplications = $groupedByDate->except($latestDate)->flatten(1);

                $hasApplications = $allCopies->isNotEmpty();
            @endphp

            @if($hasApplications && $previousApplications->isNotEmpty())
                <div class="w-full sm:w-auto text-center sm:text-right mb-6 sm:mb-4">
                    <button id="toggleButton" onclick="toggleAllRows(this)" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-base shadow">
                        <img src="{{ asset('passets/images/icons/history.svg')}}" alt="" class="w-5 h-5"> Show Previous Applications
                    </button>
                </div>
            @endif
    </div>

    <!-- Loader -->
    <div id="common-loader" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white px-6 py-4 border rounded-lg shadow-lg">
            <div class="relative w-8 h-8">
                <div class="absolute inset-0 rounded-full border-4 border-teal-400 border-t-transparent animate-spin"></div>
            </div>
            <span class="text-base text-gray-800">Loading previous applications...</span>
        </div>
    </div>

    <div class="mt-4 mb-16">
        @if($hasApplications)
        <!-- Instruction Message -->
           <div class="w-full text-yellow-600 rounded mb-4 text-xs sm:text-sm">
                <strong>Note:</strong> Click on the <span class="font-semibold text-yellow-700">Application Number</span> to view full application details.
            </div>
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full table-auto text-base">
                    <thead class="bg-[#4B3E2F] text-white text-base">
                        <tr>
                            <th class="px-3 py-2 border text-left font-medium whitespace-nowrap">S.No.</th>
                            <th class="px-3 py-2 border text-left font-medium whitespace-nowrap">Application Number</th>
                            <th class="px-3 py-2 border text-left font-medium whitespace-nowrap">Name</th>
                            <th class="px-3 py-2 border text-left font-medium whitespace-nowrap">Status of the Application</th>
                            <th class="px-3 py-2 border text-left font-medium whitespace-nowrap">Case Type</th>
                            <th class="px-3 py-2 border text-left font-medium whitespace-nowrap">Applied Date</th>
                        </tr>
                    </thead>
                    <tbody id="application-table">
                        @foreach($latestDateApplications as $index => $copy)
                            <tr class="text-base">
                                <td class="px-3 py-2 border">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2 border break-words">
                                    @php $encodedAppNo = base64_encode($copy['application_number']); @endphp
                                    <a href="{{ url('trackStatusDetails') }}?application_number={{ $encodedAppNo }}" 
                                       class="text-md text-emerald-600 font-semibold border-b border-emerald-500 hover:text-emerald-700">
                                       {{ $copy['application_number'] }}
                                    </a>
                                </td>
                                <td class="px-3 py-2 border break-words">
                                    {{ is_array($copy['applicant_name']) ? implode(', ', $copy['applicant_name']) : $copy['applicant_name'] }}
                                </td>
                                <td class="px-3 py-2 border break-words">
                                    {{ is_array($copy['application_status']) ? implode(', ', $copy['application_status']) : $copy['application_status'] }}
                                </td>
                                <td class="px-3 py-2 border break-words">
                                    {{ is_array($copy['case_type']) ? implode(', ', $copy['case_type']) : $copy['case_type'] }}
                                </td>
                                <td class="px-3 py-2 border break-words">{{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}</td>
                            </tr>
                        @endforeach

                        @foreach($previousApplications as $index => $copy)
                            <tr class="text-base previous-row hidden">
                                <td class="px-3 py-2 border">{{ $loop->iteration + $latestDateApplications->count() }}</td>
                                <td class="px-3 py-2 border break-words">
                                    @php $encodedAppNo = base64_encode($copy['application_number']); @endphp
                                    <a href="{{ url('trackStatusDetails') }}?application_number={{ $encodedAppNo }}" 
                                       class="text-md text-emerald-600 font-semibold border-b border-emerald-500 hover:text-emerald-700">
                                       {{ $copy['application_number'] }}
                                    </a>
                                </td>
                                <td class="px-3 py-2 border break-words">
                                    {{ is_array($copy['applicant_name']) ? implode(', ', $copy['applicant_name']) : $copy['applicant_name'] }}
                                </td>
                                <td class="px-3 py-2 border break-words">
                                    {{ is_array($copy['application_status']) ? implode(', ', $copy['application_status']) : $copy['application_status'] }}
                                </td>
                                <td class="px-3 py-2 border break-words">
                                    {{ is_array($copy['case_type']) ? implode(', ', $copy['case_type']) : $copy['case_type'] }}
                                </td>
                                <td class="px-3 py-2 border break-words">{{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
               
            </div>
        @else
            <div class="mt-6 flex items-center gap-3 text-rose-600 bg-rose-50 border border-rose-200 px-4 py-3 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-8-4a.75.75 0 00-.75.75v2.5a.75.75 0 001.5 0v-2.5A.75.75 0 0010 6zm0 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <span class="text-lg font-semibold">404 No details found.</span>
            </div>
        @endif
    </div>
            

    @else
        <div class="mt-6 flex items-center gap-3 text-rose-600 bg-rose-50 border border-rose-200 px-4 py-3 rounded-lg shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-8-4a.75.75 0 00-.75.75v2.5a.75.75 0 001.5 0v-2.5A.75.75 0 0010 6zm0 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            <span class="text-lg font-semibold">404 No details found.</span>
        </div>
    @endif
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const trackData = @json(session('trackDetailsMobileHC'));
        if (!trackData) {
            window.location.href = "/trackStatus";
        }
    });

    function toggleAllRows(button) {
        const previousRows = document.querySelectorAll(".previous-row");
        const loader = document.getElementById("common-loader");

        if (loader) loader.classList.remove('hidden');

        setTimeout(() => {
            previousRows.forEach(row => row.classList.remove('hidden'));
            if (loader) loader.classList.add('hidden');
            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');
        }, 1000);
    }
</script>
@endpush
