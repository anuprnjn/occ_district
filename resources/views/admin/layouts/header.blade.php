<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>High Court of Jharkhand || ADMIN PANNEL</title>
  <link rel="shortcut icon" href="{{ asset('passets/images/favicon.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css')}}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                  <span class="hide-menu">Master Data</span>
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
                      <span class="hide-menu">Role List</span>
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
    <!-- <header class="app-header">
      <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item d-block d-xl-none">
            <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
            <i class="fa-solid fa-bars"></i>
            </a>
          </li>
        </ul>
      </nav>
    </header> -->
    <div class="body-wrapper">
      <!--  Header Start -->
    