<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>High Court of Jharkhand || ADMIN PANNEL</title>
  <link rel="shortcut icon" href="{{ asset('passets/images/favicon.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css')}}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!--  App Topstrip -->
    <div class="app-topstrip py-2 bg-dark px-3 w-100 d-lg-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center justify-content-center gap-3 mb-2 mb-lg-0">
      <ul class="navbar-nav">
        <li class="nav-item d-block d-xl-none">
          <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
          <i class="fa-solid fa-bars text-white" style="font-size:18px;"></i>
          </a>
        </li>
      </ul>
        <a class="d-flex align-items-center text-decoration-none" href="/admin">
            <img src="{{ asset('passets/images/favicon.png') }}" alt="High Court Logo" width="45" class="ms-2 img-fluid">
            <h6 class="ms-3 mt-2 text-white fw-bold text-uppercase" style="letter-spacing: 0px;">
                <span class="fs-6 fw-normal text-warning">Online Certified Copy</span>
            </h6>
        </a>
        <span class="text-white">
          @if(session('user.name'))
              ( Welcome, <span class="text-success text-uppercase">{{ session('user.name') }}</span> )
          @endif
      </span>
    </div>
    <div class="d-flex flex-wrap gap-6">
      <div class="d-lg-flex align-items-center gap-4">
     
        <!-- establishmant dropdown  -->
        <div class="btn-group">
                <button class="btn bg-info-subtle text-info dropdown-toggle @if(session('user.caseType') !== 'DC') d-none @endif" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Select Establishment
        </button>
              </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <li><a class="dropdown-item" href="javascript:void(0)">Action</a></li>
                      <li>
                          <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                      </li>
                      <li>
                          <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                      </li>
                  </ul>
              </div> 
          </div>
    <!-- establishment dropdown ends  -->
      
        <div class="dropdown">
          <a class="btn btn-outline-warning d-flex align-items-center gap-2 px-4 py-2 rounded-pill" 
            href="javascript:void(0)" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user-shield"></i>
            <span class="fw-semibold">Admin</span>
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
                <i class="fa-solid fa-user-pen fs-5 text-success"></i> Update profile
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
              <button class="btn btn-danger d-flex align-items-center gap-2 justify-content-center w-100 py-2 rounded-pill" id="logoutButton">
                  <i class="fa-solid fa-sign-out-alt fs-5"></i> LOGOUT
              </button>
            </li>
          </ul>
        </div>
      </div>

    </div>
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-end">
          <!-- <a href="/admin" class="text-nowrap logo-img" style="margin-top: 20px;margin-bottom: -20px;">
            <img src="{{ asset('passets/images/HC-main.png') }}" alt="" width="200" />
          </a> -->
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="fa-solid fa-xmark" style="font-size:20px;"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar mt-4" data-simplebar="">
          <ul id="sidebarnav">
            
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('index') }}" aria-expanded="false">
                <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <iconify-icon icon="solar:home-angle-line-duotone"></iconify-icon>
                  </span>
                  <span class="hide-menu">Master Data</span>
                </div>
              </a>
              <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" 
                  href="{{ route('menu_list') }}">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">Main Menu</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" 
                  href="{{ route('submenu_list') }}">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">Sub Menu</span>
                    </div>
                  </a>
                </li>
      
              </ul>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <iconify-icon icon="solar:home-angle-line-duotone"></iconify-icon>
                  </span>
                  <span class="hide-menu">User And Permission</span>
                </div>
              </a>
              <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" 
                  href="{{ route('role_list') }}">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">Role And Permission </span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" 
                  href="{{ route('submenu_list') }}">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">User List</span>
                    </div>
                  </a>
                </li>

                
                
              </ul>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <iconify-icon icon="solar:home-angle-line-duotone"></iconify-icon>
                  </span>
                  <span class="hide-menu">Payment Details</span>
                </div>
              </a>
              <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                  <a class="sidebar-link justify-content-between" 
                  href="{{ route('payment_parameter_list') }}">
                    <div class="d-flex align-items-center gap-3">
                      <span class="d-flex">
                        <span class="icon-small"></span>
                      </span>
                      <span class="hide-menu">HC Payment Parameter</span>
                    </div>
                  </a>
                </li>
      
              </ul>
            </li>
            
           
            
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!-- Logout Confirmation Modal -->
<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white"> <!-- Updated header color -->
        <h5 class="modal-title" id="logoutModalLabel" style="color:white !important;"><i class="fas fa-sign-out" style="margin-right: 10px;"></i>Confirm Logout</h5>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <p>Are you sure you want to log out?</p>
          <div class="progress" style="height: 10px;">
            <div id="logoutProgressBar" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <p id="timerText" class="mt-3">Redirecting in <span id="countdown">10</span> seconds...</p>
        </div>
      </div>
      <div class="modal-footer" style="margin-top: -20px;">
        <button type="button" class="btn btn-secondary btn-md" id="cancelLogout" data-bs-dismiss="modal" style="width: 120px;">Cancel</button>
        <button type="button" class="btn btn-primary btn-md" id="confirmLogout" style="width: 120px;">OK</button>
      </div>
    </div>
  </div>
</div>

    <div class="body-wrapper">