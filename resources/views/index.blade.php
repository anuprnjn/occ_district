@extends('public_layouts.app')

@section('content')

<section class="content-section relative">
    <!-- Navigation to select pages -->
    <div class="radio-container mb-4">
        <label>
            <input type="radio" name="courtType" value="hcPage" checked onchange="loadContent('hcPage')"> High Court
        </label>
        <label class="ml-4">
            <input type="radio" name="courtType" value="dcPage" onchange="loadContent('dcPage')"> District Court
        </label>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="absolute w-full h-[100vh] bg-white mt-10 inset-0 flex items-start justify-start z-50">
        <div class="spinner flex items-center gap-2 p-2 ml-4 mt-4"> 
            <img class="w-[32px] animate-spin" src="{{ asset('passets/images/icons/loading.png') }}" alt="Loading">
            <span class="text-gray-600 load">Loading...</span>
        </div>
    </div>
    

    <!-- Content Section -->
    <div id="content-area" class="w-full">
        <!-- Default content or a placeholder -->
        <p class="text-lg font-semibold text-rose-800">Please select a court type to view the content.</p>
    </div>
</section>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>
<script>
        function fetchEstablishments(dist_code) {
        if (!dist_code) {
            // If no district is selected, reset the Establishment dropdown
            $('#selectEsta').html('<option value="" selected>Select Establishment</option>');
            return;
        }

        $.ajax({
            url: "{{ route('get-establishments') }}", // Ensure this route exists in your Laravel app
            method: 'POST',
            data: {
                dist_code: dist_code,
                _token: "{{ csrf_token() }}" // Include the CSRF token for Laravel
            },
            success: function (data) {
                // Clear and populate the Establishment dropdown
                let options = '<option value="" selected>Select Establishment</option>';
                data.forEach(function (establishment) {
                    options += `<option value="${establishment.est_code}">${establishment.estname}</option>`;
                });
                $('#selectEsta').html(options);
            },
            error: function () {
                alert('Unable to fetch establishments. Please try again later.');
            }
        });
    }
</script>

@endpush
