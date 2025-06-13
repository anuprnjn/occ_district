
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
                        <label for="date" class="me-2 mb-0 w-100">Select Date:</label>
                        <input
                          type="date"
                          name="date"
                          id="date"
                          class="form-control form-control-md w-100"
                          value="{{ $defaultDate }}"
                          max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                        >
                    </form>
                    <a href="{{ route('index') }}" class="btn btn-secondary btn-md">Show All</a>
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
                <a href="dc-web-application" class="link-light link-underline-opacity-0">
                  <div class="small-box bg-soft-applied text-white">
                  <div class="inner">
                    <h3>{{ $dcOrderCopyCount }}</h3>
                    <p>Total Applied Orders & Judgement Copies</p>
                  </div>
                  <svg 
                    class="small-box-icon" 
                    fill="currentColor" 
                    viewBox="0 0 24 24" 
                    xmlns="http://www.w3.org/2000/svg" 
                    aria-hidden="true"
                  >
                    <path d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.01 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                  </svg>
                </div>
                </a>
                <!--end::Small Box Widget 1-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <a href="dc-web-deliver-application" class="link-light link-underline-opacity-0">
                  <div class="small-box bg-soft-delivered text-white">
                  <div class="inner">
                    <h3>{{ $dcOrderDelivered }}</h3>
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
                </div>
                </a>
                <!--end::Small Box Widget 2-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <a href="dc-web-pending-application" class="link-light link-underline-opacity-0">
                  <div class="small-box bg-soft-pending text-white">
                  <div class="inner">
                    <h3>{{ $dcOrderPending }}</h3>
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
                </div>
                </a>
                <!--end::Small Box Widget 2-->
              </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
              <!--begin::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 1-->
                 <a href="dc-other-copy" class="link-light link-underline-opacity-0">
                   <div class="small-box bg-soft-applied text-white">
                  <div class="inner">
                    <h3>{{ $dcWebCopyCount }}</h3>
                    <p>Total Applied Other Copies</p>
                  </div>
                  <svg 
                    class="small-box-icon" 
                    fill="currentColor" 
                    viewBox="0 0 24 24" 
                    xmlns="http://www.w3.org/2000/svg" 
                    aria-hidden="true"
                  >
                    <path d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.01 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                  </svg>  
                </div>
                 </a>
                <!--end::Small Box Widget 1-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <a href="dc-other-delivered-copy" class="link-light link-underline-opacity-0">
                  <div class="small-box bg-soft-delivered text-white">
                  <div class="inner">
                    <h3>{{ $dcWebDelivered }}</h3>
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
                </div>
                </a>
                <!--end::Small Box Widget 2-->
              </div>
              <!--end::Col-->
              <div class="col-lg-4 col-6">
                <!--begin::Small Box Widget 2-->
                <a href="dc-other-pending-copy" class="link-light link-underline-opacity-0">
                  <div class="small-box bg-soft-pending text-white">
                  <div class="inner">
                    <h3>{{ $dcWebPending }}</h3>
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
                </div>
                </a>
                <!--end::Small Box Widget 2-->
              </div>
            </div>
            <!-- /.row (main row) -->
             <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">District Applications Submitted in Last 15 Days</h5>
        <canvas id="applicationChart" height="80"></canvas>
    </div>
</div>
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
   <!--end::App Main-->

   
