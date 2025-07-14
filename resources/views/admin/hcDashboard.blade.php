@extends('public_layouts.app')

@section('content')


<!--begin::App Main-->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <div class="container-fluid">
      <!-- Header & Breadcrumb -->
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Dashboard  <span style="color: #D09A3F;">(HIGH COURT)</span></h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
      <hr>

      @php
        $defaultDate = request('date') ?? \Carbon\Carbon::today()->format('Y-m-d');
      @endphp

      <!-- Date Filter -->
      <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end align-items-center gap-2">
          <form method="GET" action="{{ route('index') }}" id="dateFilterForm" class="d-flex align-items-center">
            <label for="date" class="me-2 mb-0 w-100">Select Date:</label>
            <input
              type="date"
              name="date"
              id="date"
              class="form-control form-control-md w-200"
              value="{{ $defaultDate }}"
              max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
            >
          </form>
          <a href="{{ route('index') }}" class="btn btn-success btn-md">Show All</a>
        </div>
      </div>
    </div>
  </div>
  <!--end::App Content Header-->

  <!--begin::App Content-->
  <div class="app-content">
    <div class="container-fluid">

      @php
        $cards = [
          [
            'count' => $hcOrderCopyCount,
            'text' => 'Total Applied Orders & Judgement Copies',
            'route' => 'hc-web-application',
            'bg' => 'bg-soft-applied',
            'icon' => '<path d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.01 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>',
          ],
          [
            'count' => $hcWebCopyCount,
            'text' => 'Total Applied Other Copies',
            'route' => 'hc-other-copy',
            'bg' => 'bg-soft-applied',
            'icon' => '<path d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.01 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>',
          ],
          [
            'count' => $hcOrderDelivered,
            'text' => 'Total Delivered Orders & Judgement Copies',
            'route' => 'hc-web-delivered-application',
            'bg' => 'bg-soft-delivered',
            'icon' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 
                      10-4.48 10-10S17.52 2 12 2zm-1.41 
                      14.59L6.5 12.5l1.41-1.41 2.67 
                      2.67 5.67-5.67L17.66 9l-7.07 
                      7.59z"/>',
          ],
          [
            'count' => $hcWebDelivered,
            'text' => 'Total Delivered Other Copies',
            'route' => 'hc-other-delivered-copy',
            'bg' => 'bg-soft-delivered',
            'icon' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 
                      10-4.48 10-10S17.52 2 12 2zm-1.41 
                      14.59L6.5 12.5l1.41-1.41 2.67 
                      2.67 5.67-5.67L17.66 9l-7.07 
                      7.59z"/>',
          ],
          [
            'count' => $hcOrderPending,
            'text' => 'Total Pending Orders & Judgement Copies',
            'route' => 'hc-web-pending-application',
            'bg' => 'bg-soft-pending',
            'icon' => '<path d="M6 2v6h1l4 4-4 4H6v6h12v-6h-1l-4-4 
                      4-4h1V2H6z"/>',
          ],
           [
            'count' => $hcWebPending,
            'text' => 'Total Pending Others Copies',
            'route' => 'hc-other-pending-copy',
            'bg' => 'bg-soft-pending',
            'icon' => '<path d="M6 2v6h1l4 4-4 4H6v6h12v-6h-1l-4-4 
                      4-4h1V2H6z"/>',
          ],
        ];
      @endphp

      <div class="row">
        <!-- Cards Column (50%) -->
        <div class="col-lg-5">
          <div class="row g-4">
            @foreach ($cards as $card)
              <div class="col-sm-6">
                <a href="{{ url('admin/' . $card['route']) }}" class="text-decoration-none">
                  <div class="small-box {{ $card['bg'] }}">
                    <div class="inner">
                      <h3>{{ $card['count'] }}</h3>
                      <p>{{ $card['text'] }}</p>
                    </div>
                    <div class="icon-wrapper">
                      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        {!! $card['icon'] !!}
                      </svg>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
        <!-- Chart Column (50%) -->
        <div class="col-lg-7">
          <div class="card h-100 shadow-md">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Applications Submitted in Last 15 Days</h5>
              <canvas id="applicationChart" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!--end::App Main-->

@endsection
