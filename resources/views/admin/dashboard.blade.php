@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Dasboard')

@section('content')

   <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row ">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
            <hr>
            <!--end::Row-->
            @php
                $defaultDate = request('date') ?? \Carbon\Carbon::today()->format('Y-m-d');
            @endphp

            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-end align-items-center gap-2">
                    <form method="GET" action="{{ route('index') }}" id="dateFilterForm" class="d-flex align-items-center">
                        <label for="date" class="me-2 mb-0">Select Date:</label>
                        <input
                          type="date"
                          name="date"
                          id="date"
                          class="form-control form-control-sm w-auto"
                          value="{{ $defaultDate }}"
                          max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                        >
                    </form>
                    <a href="{{ route('index') }}" class="btn btn-secondary btn-sm">Show All</a>
                </div>
            </div>
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row mt-n3">
              <!--begin::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box bg-soft-applied text-white">
                  <div class="inner">
                    <h3>{{ $hcOrderCopyCount }}</h3>
                    <p>Total Applied Orders & Judgement Copies</p>
                  </div>
                   <svg
                    class="small-box-icon"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                  >
                    <path
                      d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"
                    ></path>
                  </svg>
                  <a
                    href="hc-web-application"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box bg-soft-delivered text-white">
                  <div class="inner">
                    <h3>{{ $hcOrderDelivered }}</h3>
                    <p>Total Delivered Orders & Judgement Copies</p>
                  </div>
                  <svg
                    class="small-box-icon"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                  >
                    <path
                      d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 
                        10-4.48 10-10S17.52 2 12 2zm-1.41 
                        14.59L6.5 12.5l1.41-1.41 2.67 
                        2.67 5.67-5.67L17.66 9l-7.07 
                        7.59z"
                    ></path>
                  </svg>
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 2-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box bg-soft-pending text-white">
                  <div class="inner">
                    <h3>{{ $hcOrderPending }}</h3>
                    <p>Total Pending Orders & Judgement Copies</p>
                  </div>
                  <svg
                    class="small-box-icon"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                  >
                    <path
                      d="M6 2v6h1l4 4-4 4H6v6h12v-6h-1l-4-4 
                        4-4h1V2H6z"
                    ></path>
                  </svg>
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 2-->
              </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
              <!--begin::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box bg-soft-applied text-white">
                  <div class="inner">
                    <h3>{{ $hcWebCopyCount }}</h3>
                    <p>Total Applied Other Copies</p>
                  </div>
                   <svg
                    class="small-box-icon"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                  >
                    <path
                      d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"
                    ></path>
                  </svg>
                  <a
                    href="hc-other-copy"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box bg-soft-delivered text-white">
                  <div class="inner">
                    <h3>{{ $hcWebDelivered }}</h3>
                    <p>Total Delivered Other Copies</p>
                  </div>
                  <svg
                    class="small-box-icon"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                  >
                    <path
                      d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 
                        10-4.48 10-10S17.52 2 12 2zm-1.41 
                        14.59L6.5 12.5l1.41-1.41 2.67 
                        2.67 5.67-5.67L17.66 9l-7.07 
                        7.59z"
                    ></path>
                  </svg>
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 2-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box bg-soft-pending text-white">
                  <div class="inner">
                    <h3>{{ $hcWebPending }}</h3>
                    <p>Total Pending Others Copies</p>
                  </div>
                  <svg
                    class="small-box-icon"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                  >
                    <path
                      d="M6 2v6h1l4 4-4 4H6v6h12v-6h-1l-4-4 
                        4-4h1V2H6z"
                    ></path>
                  </svg>
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 2-->
              </div>
            </div>
            <!-- /.row (main row) -->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
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
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    padding: 1rem;
    transition: transform 0.2s ease;
  }

  .small-box:hover {
    transform: translateY(-4px);
  }

  .small-box .inner h3 {
    font-size: 2rem;
    font-weight: 600;
  }

  .small-box .inner p {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    font-weight: 500;
  }

  .small-box-icon {
    width: 50px;
    height: 50px;
  }

  /* Updated Modern Card Background Gradients */
  .bg-soft-applied {
    background: linear-gradient(135deg, #00acc1, #007c91); /* Vibrant Cyan-Teal */
    color: #fff;
  }

  .bg-soft-delivered {
    background: linear-gradient(135deg, #43a047, #2e7d32); /* Fresh Green */
    color: #fff;
  }

  .bg-soft-pending {
    background: linear-gradient(135deg, #e53935, #b71c1c); /* Warm Red */
    color: #fff;
  }
</style>
@endpush

@endsection