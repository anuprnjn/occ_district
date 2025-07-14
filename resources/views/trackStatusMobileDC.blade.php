@extends('public_layouts.app')

@section('content')
@push('head')
<meta name="viewport" content="width=device-width, initial-scale=1">
@endpush

<section class="min-h-screen px-4 py-6 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start gap-4 mb-6 w-full">
       <h3 class="text-lg sm:text-xl font-bold text-[#4B3E2F] text-center sm:text-left w-full sm:w-auto -mt-4 sm:mt-0">
            Application Details - Civil Court
        </h3>
        
        @if(session()->has('trackDetailsMobileDC'))
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('refresh.track.status.dc') }}"
               class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm sm:text-base shadow transition-colors">
               <img src="{{ asset('passets/images/icons/refresh.svg')}}" alt="" class="w-5 h-5 hover:animate-spin">
                <span>Refresh Application Status</span>
            </a>
            
            @php
                $hcData = session('trackDetailsMobileDC')['data'];
                $allCopies = collect($hcData['order_copy'] ?? [])
                    ->merge($hcData['other_copy'] ?? [])
                    ->sortByDesc('created_at')
                    ->values();
                
                $previousApplications = $allCopies->filter(fn($item) =>
                    $item['application_status'] === 'CERTIFIED COPY IS READY TO BE DOWNLOADED'
                )->values();
            @endphp
            
            @if($previousApplications->isNotEmpty())
            <button id="toggleButton" onclick="toggleAllRows(this)"
                class="flex items-center justify-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm sm:text-base shadow transition-colors">
                <img src="{{ asset('passets/images/icons/history.svg')}}" alt="" class="w-5 h-5">
                <span>Show Previous & Delivered Applications</span>
            </button>
            @endif
        </div>
        @endif
    </div>

    <!-- Loader -->
    <div id="common-loader" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white px-8 py-3 border rounded-md">
            <div class="relative w-8 h-8">
                <div class="absolute inset-0 rounded-full border-4 border-teal-400 border-t-transparent animate-spin"></div>
            </div>
            <span class="text-base text-gray-800">Loading previous applications...</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mb-12">
        @if(session()->has('trackDetailsMobileDC'))
            @php
                $latestDateApplications = $allCopies->filter(fn($item) =>
                    $item['application_status'] !== 'CERTIFIED COPY IS READY TO BE DOWNLOADED'
                )->values();
                
                $hasApplications = $allCopies->isNotEmpty();
            @endphp
            
            @if($hasApplications)
            <div class="w-full text-yellow-600 rounded mb-4 text-xs sm:text-sm">
                <strong>Note:</strong> Click on the <span class="font-semibold text-yellow-700">Application Number</span> to view full application details.
            </div>
            
            <!-- Mobile View (Cards) -->
            <div class="block sm:hidden space-y-4">
                @foreach($latestDateApplications as $copy)
                <div class="border rounded-lg shadow p-4 ">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-sm font-medium">S.No.</div>
                        <div class="text-sm font-semibold bg-[#D09A3F] w-6 text-center rounded-md text-white">{{ $loop->iteration }}</div>
                        
                        <div class="text-sm font-medium">Application No.</div>
                        <div class="text-sm font-semibold">
                            <form method="POST" action="{{ url('trackStatusDetails') }}" id="form-{{ $copy['application_number'] }}">
                                @csrf
                                <input type="hidden" name="application_number" value="{{ base64_encode($copy['application_number']) }}">
                                <a href="#" 
                                onclick="event.preventDefault(); document.getElementById('form-{{ $copy['application_number'] }}').submit();"
                                class="font-semibold text-teal-600 hover:text-teal-700 hover:underline">
                                    {{ $copy['application_number'] }}
                                </a>
                            </form>
                        </div>
                        
                        <div class="text-sm font-medium">Name</div>
                        <div class="text-sm uppercase">
                            {{ is_array($copy['applicant_name']) ? implode(', ', $copy['applicant_name']) : strtoupper($copy['applicant_name']) }}
                        </div>
                        
                        <div class="text-sm font-medium">Status</div>
                        <div class="text-sm capitalize">
                            {{ is_array($copy['application_status']) ? implode(', ', array_map('ucwords', $copy['application_status'])) : ucwords(strtolower($copy['application_status'])) }}
                        </div>
                        
                        @php
                            $fillingType = !empty($copy['filling_type']) 
                                ? (is_array($copy['filling_type']) ? implode(', ', $copy['filling_type']) : $copy['filling_type']) 
                                : 'N/A';
                            if ($fillingType !== 'N/A' && preg_match('/^([A-Z\.]+)\s*:\s*.*?\/(\d+\/\d+)/', $fillingType, $matches)) {
                                $fillingType = $matches[1] . ' :/' . $matches[2];
                            }
                        @endphp
                        
                        <div class="text-sm font-medium">Filing No.</div>
                        <div class="text-sm">{{ $fillingType }}</div>
                        
                        <div class="text-sm font-medium">Case No.</div>
                        <div class="text-sm">
                            {{ !empty($copy['case_type']) ? (is_array($copy['case_type']) ? implode(', ', $copy['case_type']) : $copy['case_type']) : 'N/A' }}
                        </div>
                        
                        <div class="text-sm font-medium">Applied Date</div>
                        <div class="text-sm">
                            {{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}
                        </div>
                    </div>
                </div>
                @endforeach
                
                @foreach($previousApplications as $copy)
                <div class="border rounded-lg shadow p-4 hidden previous-row">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-sm font-medium">S.No.</div>
                        <div class="text-sm font-semibold bg-[#D09A3F] w-6 text-center rounded-md text-white">{{ $loop->iteration + $latestDateApplications->count() }}</div>
                        
                        <div class="text-sm font-medium">Application No.</div>
                        <div class="text-sm font-semibold">
                            <form method="POST" action="{{ url('trackStatusDetails') }}" id="form-{{ $copy['application_number'] }}">
                                @csrf
                                <input type="hidden" name="application_number" value="{{ base64_encode($copy['application_number']) }}">
                                <a href="#" 
                                onclick="event.preventDefault(); document.getElementById('form-{{ $copy['application_number'] }}').submit();"
                                class="font-semibold text-teal-600 hover:text-teal-700 hover:underline">
                                    {{ $copy['application_number'] }}
                                </a>
                            </form>
                        </div>
                        
                        <div class="text-sm font-medium">Name</div>
                        <div class="text-sm uppercase">
                            {{ is_array($copy['applicant_name']) ? implode(', ', $copy['applicant_name']) : strtoupper($copy['applicant_name']) }}
                        </div>
                        
                        <div class="text-sm font-medium">Status</div>
                        <div class="text-sm capitalize">
                            {{ is_array($copy['application_status']) ? implode(', ', array_map('ucwords', $copy['application_status'])) : ucwords(strtolower($copy['application_status'])) }}
                        </div>
                        
                        @php
                            $fillingType = !empty($copy['filling_type']) 
                                ? (is_array($copy['filling_type']) ? implode(', ', $copy['filling_type']) : $copy['filling_type']) 
                                : 'N/A';
                            if ($fillingType !== 'N/A' && preg_match('/^([A-Z\.]+)\s*:\s*.*?\/(\d+\/\d+)/', $fillingType, $matches)) {
                                $fillingType = $matches[1] . ' :/' . $matches[2];
                            }
                        @endphp
                        
                        <div class="text-sm font-medium">Filing No.</div>
                        <div class="text-sm">{{ $fillingType }}</div>
                        
                        <div class="text-sm font-medium">Case No.</div>
                        <div class="text-sm">
                            {{ !empty($copy['case_type']) ? (is_array($copy['case_type']) ? implode(', ', $copy['case_type']) : $copy['case_type']) : 'N/A' }}
                        </div>
                        
                        <div class="text-sm font-medium">Applied Date</div>
                        <div class="text-sm">
                            {{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Desktop View (Table) -->
            <div class="hidden sm:block overflow-x-auto border">
                <table class="w-full text-sm sm:text-base">
                    <thead class="bg-[#4B3E2F] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">#</th>
                            <th class="px-4 py-3 text-left font-medium">Application No.</th>
                            <th class="px-4 py-3 text-left font-medium">Name</th>
                            <th class="px-4 py-3 text-left font-medium">Status</th>
                            <th class="px-4 py-3 text-left font-medium">Filing No.</th>
                            <th class="px-4 py-3 text-left font-medium">Case No.</th>
                            <th class="px-4 py-3 text-left font-medium">Applied Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($latestDateApplications as $copy)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-semibold">
                                <form method="POST" action="{{ url('trackStatusDetails') }}" id="form-{{ $copy['application_number'] }}">
                                    @csrf
                                    <input type="hidden" name="application_number" value="{{ base64_encode($copy['application_number']) }}">
                                    <a href="#" 
                                    onclick="event.preventDefault(); document.getElementById('form-{{ $copy['application_number'] }}').submit();"
                                    class="font-semibold text-teal-600 hover:text-teal-700 hover:underline">
                                        {{ $copy['application_number'] }}
                                    </a>
                                </form>
                            </td>
                            <td class="px-4 py-3 uppercase">
                                {{ is_array($copy['applicant_name']) ? implode(', ', $copy['applicant_name']) : strtoupper($copy['applicant_name']) }}
                            </td>
                            <td class="px-4 py-3 capitalize">
                                {{ is_array($copy['application_status']) ? implode(', ', array_map('ucwords', $copy['application_status'])) : ucwords(strtolower($copy['application_status'])) }}
                            </td>
                            @php
                                $fillingType = !empty($copy['filling_type']) 
                                    ? (is_array($copy['filling_type']) ? implode(', ', $copy['filling_type']) : $copy['filling_type']) 
                                    : 'N/A';
                                if ($fillingType !== 'N/A' && preg_match('/^([A-Z\.]+)\s*:\s*.*?\/(\d+\/\d+)/', $fillingType, $matches)) {
                                    $fillingType = $matches[1] . ' :/' . $matches[2];
                                }
                            @endphp
                            <td class="px-4 py-3">{{ $fillingType }}</td>
                            <td class="px-4 py-3">
                                {{ !empty($copy['case_type']) ? (is_array($copy['case_type']) ? implode(', ', $copy['case_type']) : $copy['case_type']) : 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                        
                        @foreach($previousApplications as $copy)
                        <tr class=" transition-colors hidden previous-row">
                            <td class="px-4 py-3">{{ $loop->iteration + $latestDateApplications->count() }}</td>
                            <td class="px-4 py-3 font-semibold">
                                <form method="POST" action="{{ url('trackStatusDetails') }}" id="form-{{ $copy['application_number'] }}">
                                    @csrf
                                    <input type="hidden" name="application_number" value="{{ base64_encode($copy['application_number']) }}">
                                    <a href="#" 
                                    onclick="event.preventDefault(); document.getElementById('form-{{ $copy['application_number'] }}').submit();"
                                    class="font-semibold text-teal-600 hover:text-teal-700 hover:underline">
                                        {{ $copy['application_number'] }}
                                    </a>
                                </form>
                            </td>
                            <td class="px-4 py-3 uppercase">
                                {{ is_array($copy['applicant_name']) ? implode(', ', $copy['applicant_name']) : strtoupper($copy['applicant_name']) }}
                            </td>
                            <td class="px-4 py-3 capitalize">
                                {{ is_array($copy['application_status']) ? implode(', ', array_map('ucwords', $copy['application_status'])) : ucwords(strtolower($copy['application_status'])) }}
                            </td>
                            @php
                                $fillingType = !empty($copy['filling_type']) 
                                    ? (is_array($copy['filling_type']) ? implode(', ', $copy['filling_type']) : $copy['filling_type']) 
                                    : 'N/A';
                                if ($fillingType !== 'N/A' && preg_match('/^([A-Z\.]+)\s*:\s*.*?\/(\d+\/\d+)/', $fillingType, $matches)) {
                                    $fillingType = $matches[1] . ' :/' . $matches[2];
                                }
                            @endphp
                            <td class="px-4 py-3">{{ $fillingType }}</td>
                            <td class="px-4 py-3">
                                {{ !empty($copy['case_type']) ? (is_array($copy['case_type']) ? implode(', ', $copy['case_type']) : $copy['case_type']) : 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($copy['created_at'])->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="mt-6 flex items-center gap-3 text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-8-4a.75.75 0 00-.75.75v2.5a.75.75 0 001.5 0v-2.5A.75.75 0 0010 6zm0 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm sm:text-base font-semibold">No application details found.</span>
            </div>
            @endif
        @else
        <div class="mt-6 flex items-center gap-3 text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-8-4a.75.75 0 00-.75.75v2.5a.75.75 0 001.5 0v-2.5A.75.75 0 0010 6zm0 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm sm:text-base font-semibold">No tracking data available. Please check your application.</span>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Redirect if no tracking data
        const trackData = @json(session('trackDetailsMobileDC'));
        if (!trackData) {
            window.location.href = "/trackStatus";
        }
    });

    function toggleAllRows(button) {
        const previousRows = document.querySelectorAll(".previous-row");
        const loader = document.getElementById("common-loader");

        if (loader) loader.classList.remove('hidden');

        setTimeout(() => {
            previousRows.forEach(row => {
                row.classList.remove('hidden');
            });
            
            if (loader) loader.classList.add('hidden');
            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');
        }, 500);
    }
</script>
@endpush

@endsection