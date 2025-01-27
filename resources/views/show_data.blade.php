@extends('public_layouts.app')

@section('content')

<section class="content-section "  >
    <h1>Application No : </h1>
    <h1 id="application-number"></h1>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const applicationNumber = sessionStorage.getItem('application_number');
    if (applicationNumber) {
        // Display application number on the page
        document.getElementById('application-number').textContent = applicationNumber;
    }
});
</script>    
@endpush
