@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Dasboard')

@section('content')

   <!--begin::App Main-->
  @if(session('user.dist_code') && session('user.estd_code'))
    @include('admin.dcDashboard')
  @else
    @include('admin.hcDashboard')
  @endif
   <!--end::App Main-->

   
  @push('scripts')
  <script>
    $(document).ready(function () { 
        $("#highcourt_request").addClass("active");
        $("#web_copy").addClass("active");
    });
  </script>
  <script>
    document.getElementById('date').addEventListener('change', function () {
        document.getElementById('dateFilterForm').submit();
    });
  </script>
@endpush

@endsection