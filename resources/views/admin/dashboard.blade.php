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

@push('styles')
<style>
  .small-box {
    position: relative;
    border-radius: 1rem;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    padding: 2rem 1.5rem;
    transition: all 0.3s ease;
    color: #fff;
    overflow: hidden;
  }

  .small-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
  }

  .small-box .inner h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
  }

  .small-box .inner p {
    font-size: 1rem;
    font-weight: 400;
    margin: 0.5rem 0 0;
    opacity: 0.9;
  }
.bg-soft-applied {
  background: linear-gradient(135deg, #D09A3F, #A06E24); /* Golden Amber to Deep Bronze */
  color: #ffffff;
}
.bg-soft-pending {
  background: linear-gradient(135deg, #ef5350, #c62828); /* Warm Red to Soft Crimson */
  color: #ffffff;
}

.bg-soft-delivered {
  background: linear-gradient(135deg, #4B3D2F, #2E241A); /* Deep Brown to Dark Espresso */
  color: #ffffff;
}
</style>
@endpush

@endsection