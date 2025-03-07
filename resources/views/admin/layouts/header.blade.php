<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'High Court of Jharkhand') | High Court of Jharkhand</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | General Form Elements" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css')}}" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- Correct way to include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-warning-subtle" data-bs-theme="dark">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link" style="color: antiquewhite">Online Certified Copy</a></li>
            
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            
            
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                  <span class="d-none d-md-inline" style="color: antiquewhite">
                      @if(session('user.name'))
                      {{ session('user.name') }}::{{ session('user.role_name') }}
                      @endif
                      
                  </span>
              </a>
              <ul class="dropdown-menu">
                  <li>
                    <button class="dropdown-item" id="logoutButton">
                          <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                  </li>
              </ul>
          </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-dark-subtle" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="../index.html" class="brand-link">
            <!--begin::Brand Image-->
            <!--<img
              src="../../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />--> 
            <!--end::Brand Image-->
            <span>OCC</span>
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">
              @if(session('user.caseType') === 'HC')
                  Highcourt 
              @elseif(session('user.caseType') === 'DC')
                  {{ session('user.dist_name') }} <!-- Assuming 'districtName' contains the district -->
              @endif
          </span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              
              <li class="nav-item">
                <a href="{{ route('index') }}" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>
                    Master Data
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('menu_list') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Main Menu</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('submenu_list') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Sub Menu</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('payment_parameter_list') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Hc Payment Parameter</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('payment_parameter_list_dc') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>DC Payment Parameter</p>
                    </a>
                  </li>
                </ul>
              </li>
              
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>
                    User And Permission
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('role_list') }}" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Role And Permission</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('hc_user_list') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Highcourt User</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('dc_user_list') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>District Court User</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item menu-open">
                <a href="#" class="nav-link" >
                  <i class="nav-icon bi bi-pencil-square"></i>
                  <p>
                    Highcourt Request
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('hc_web_application_list') }}" class="nav-link" >
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Web Copy</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('hc_other_copy') }}" class="nav-link" >
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Other Copy</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link" >
                  <i class="nav-icon bi bi-pc-display-horizontal"></i>
                  <p>
                    District Court Request
                    <!--<span class="nav-badge badge text-bg-secondary me-3">6</span>-->
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('dc_other_copy') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Other Copy</p>
                    </a>
                  </li>
                  
                </ul>
              </li>

              
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

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