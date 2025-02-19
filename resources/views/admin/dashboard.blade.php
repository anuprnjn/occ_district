@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || ADMIN')

@section('content')

  <!--  Body Wrapper -->

    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper" style="margin-top: -70px;">
     
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <!--  Row 1 -->
          <div class="row">
            
          <div class="container">
            <div class="row">
              <!-- Row 1 -->

              <div class="col-lg-4 col-md-6">
                <div class="card bg-secondary-subtle shadow-none w-100">
                  <div class="card-body">
                    <div class="d-flex mb-10 pb-1 justify-content-between align-items-center">
                      <div class="d-flex align-items-center gap-6">
                        <div class="rounded-circle-shape bg-secondary px-3 py-2 rounded-pill d-inline-flex align-items-center justify-content-center">
                          <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-7 text-white"></iconify-icon>
                        </div>
                        <h6 class="mb-0 fs-4 fw-medium text-muted">Total Income</h6>
                      </div>
                      <div class="dropdown dropstart">
                        <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown">
                          <i class="ti ti-dots-vertical fs-6"></i>
                        </a>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item d-flex align-items-center gap-3" href="#"><i class="fs-4 ti ti-plus"></i>Add</a></li>
                          <li><a class="dropdown-item d-flex align-items-center gap-3" href="#"><i class="fs-4 ti ti-edit"></i>Edit</a></li>
                          <li><a class="dropdown-item d-flex align-items-center gap-3" href="#"><i class="fs-4 ti ti-trash"></i>Delete</a></li>
                        </ul>
                      </div>
                    </div>
                    <div class="row align-items-center justify-content-between pt-4">
                      <div class="col-5">
                        <h2 class="mb-6 fs-8 text-nowrap">$6,280</h2>
                        <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">+18% last month</span>
                      </div>
                      <div class="col-5">
                        <div id="total-income"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6">
                <div class="card bg-primary-subtle shadow-none w-100">
                  <div class="card-body">
                    <div class="d-flex mb-10 pb-1 justify-content-between align-items-center">
                      <div class="d-flex align-items-center gap-6">
                        <div class="rounded-circle-shape bg-primary px-3 py-2 rounded-pill d-inline-flex align-items-center justify-content-center">
                          <iconify-icon icon="solar:chart-2-line-duotone" class="fs-7 text-white"></iconify-icon>
                        </div>
                        <h6 class="mb-0 fs-4 fw-medium text-muted">Total Sales</h6>
                      </div>
                    </div>
                    <div class="row align-items-center justify-content-between pt-4">
                      <div class="col-5">
                        <h2 class="mb-6 fs-8 text-nowrap">1,320</h2>
                        <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">+12% last month</span>
                      </div>
                      <div class="col-5">
                        <div id="total-sales"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

      
@endsection