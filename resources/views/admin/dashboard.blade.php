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
<script src="{{ asset('passets/js/chart.js')}}"></script>
<script>
  const ctx = document.getElementById('applicationChart').getContext('2d');

  const labels = @json($dates);

  const datasets = [
    @if(session('user.dist_code') && session('user.estd_code'))
      {
        label: 'District Court Orders and Judgement Copy',
        data: @json($dcOrderCounts),
        backgroundColor: 'rgba(188, 135, 52, 0.6)',
        borderColor: '#BC8734',
        borderWidth: 1
      },
      {
        label: 'District Court Others Copy',
        data: @json($dcOtherCounts),
        backgroundColor: 'rgba(62, 49, 37, 0.6)',
        borderColor: '#3E3125',
        borderWidth: 1
      }
    @else
      {
        label: 'High Court Order and Judgement Copy',
        data: @json($hcOrderCounts),
        backgroundColor: 'rgba(188, 135, 52, 0.6)',
        borderColor: '#BC8734',
        borderWidth: 1
      },
      {
        label: 'High Court Others Copy',
        data: @json($hcOtherCounts),
        backgroundColor: 'rgba(62, 49, 37, 0.6)',
        borderColor: '#3E3125',
        borderWidth: 1
      }
    @endif
  ];

  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: datasets
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0
          }
        }
      },
      plugins: {
        title: {
          display: false
        },
        legend: {
          position: 'top'
        }
      }
    }
  });
</script>
@endpush

@push('styles')

<style>
  .small-box {
    position: relative;
    border-radius: 0.5rem;
    padding: 0.1 0.5rem;
    transition: all 0.3s ease;
    color: #fff;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
  }

  .small-box:hover {
    transform: translateY(-5px);
  }

  .small-box .inner h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
  }

  .small-box .inner p {
    font-size: 1rem;
    font-weight: 400;
    margin: 0.5rem 0 0;
    opacity: 0.95;
  }

  .small-box-icon {
    position: absolute;
    right: 1rem;
    bottom: 1rem;
    width: 40px;
    height: 40px;
    opacity: 1;
  }

  .bg-soft-applied {
    background: linear-gradient(135deg, #D09A3F, #A06E24);
  }

  .bg-soft-pending {
    background: linear-gradient(135deg, #ef5350, #c62828);
  }

  .bg-soft-delivered {
    background: linear-gradient(135deg, #4B3D2F, #2E241A);
  }
</style>

@endpush

@endsection