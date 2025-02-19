@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || ADMIN')

@section('content')

  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!--  App Topstrip -->
    <div class="app-topstrip py-2 bg-dark px-3 w-100 d-lg-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center justify-content-center gap-3 mb-2 mb-lg-0">
        <a class="d-flex align-items-center text-decoration-none" href="/admin">
            <img src="{{ asset('passets/images/favicon.png') }}" alt="High Court Logo" width="45" class="ms-2 img-fluid">
            <h6 class="ms-3 mt-2  text-white fw-bold text-uppercase" style="letter-spacing: 0px;">
                ( Admin Panel ) <br><span class="fs-6 fw-normal text-warning">Online Certified Copy</span>
            </h6>
        </a>
    </div>

      <div class="d-lg-flex align-items-center gap-4">
        <div class="dropdown">
          <a class="btn btn-outline-warning d-flex align-items-center gap-2 px-4 py-2 rounded-pill" 
            href="javascript:void(0)" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user-tie fs-5"></i>
            <span class="fw-semibold">Admin Profile</span>
            <i class="fa-solid fa-chevron-down fs-6"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up shadow-lg p-3" 
            aria-labelledby="adminDropdown">
            <li>
              <a target="_blank" href="#" class="dropdown-item d-flex align-items-center gap-2 py-2">
                <i class="fa-solid fa-user fs-5 text-primary"></i> View Profile
              </a>
            </li>
            <li>
              <a target="_blank" href="#" class="dropdown-item d-flex align-items-center gap-2 py-2">
                <i class="fa-solid fa-user-pen fs-5 text-success"></i> Update Admin Details
              </a>
            </li>
            <li>
              <a target="_blank" href="#" class="dropdown-item d-flex align-items-center gap-2 py-2">
                <i class="fa-solid fa-key fs-5 text-warning"></i> Change Password
              </a>
            </li>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
           
            <li>
              <a class="btn btn-danger d-flex align-items-center gap-2 justify-content-center w-100 py-2 rounded-pill" 
                href="javascript:void(0)">
                <i class="fa-solid fa-sign-out-alt fs-5"></i> LOGOUT
              </a>
            </li>
          </ul>
        </div>
      </div>

    </div>
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar mt-4" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Admin Details</span>
            </li>
            <!-- ---------------------------------- -->
            <!-- Dashboard -->
            <!-- ---------------------------------- -->
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <iconify-icon icon="solar:home-angle-line-duotone"></iconify-icon>
                  </span>
                  <span class="hide-menu">Admin details</span>
                </div>
              </a>
              <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" target="_blank"
                    href="#">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">Update Page 1</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" target="_blank"
                    href="#">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">Update Page 2</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" target="_blank"
                    href="#">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">Update page 3</span>
                    </div>
                  </a>
                </li>
              </ul>
            </li>

            <li>
              <span class="sidebar-divider lg"></span>
            </li>
           
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" target="_blank"
                href="#"
                aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <iconify-icon icon="solar:shield-user-line-duotone" class=""></iconify-icon>
                  </span>
                  <span class="hide-menu">User Profile</span>
                </div>
              </a>
            </li>
            
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Update details</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-buttons.html" aria-expanded="false">
                <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                <span class="hide-menu">Example Update 1</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-alerts.html" aria-expanded="false">
                <iconify-icon icon="solar:danger-circle-line-duotone"></iconify-icon>
                <span class="hide-menu">Example Update 2</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-card.html" aria-expanded="false">
                <iconify-icon icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
                <span class="hide-menu">Example Update 3</span>
              </a>
            </li>
            
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
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