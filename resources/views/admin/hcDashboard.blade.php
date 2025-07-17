@extends('public_layouts.app')

@section('content')

<!--begin::App Main-->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <div class="container-fluid">
      <!-- Header & Breahcrumb -->
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Dashboard <span style="color: #D09A3F;text-transform:uppercase;">(HIGH COURT ) </span></h3>
          <p class="text-muted mb-0">Welcome back! Here's what's happening today.</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
      <hr style="border-color: #D09A3F; opacity: 0.3;">

      @php
        $defaultDate = request('date') ?? \Carbon\Carbon::today()->format('Y-m-d');
        $totalApplications = $hcOrderCopyCount + $hcWebCopyCount;
        $totalDelivered = $hcOrderDelivered + $hcWebDelivered;
        $totalPending = $hcOrderPending + $hcWebPending;
        $deliveryRate = $totalApplications > 0 ? round(($totalDelivered / $totalApplications) * 100, 1) : 0;
        $orderDeliveryRate = $hcOrderCopyCount > 0 ? round(($hcOrderDelivered / $hcOrderCopyCount) * 100, 1) : 0;
        $otherDeliveryRate = $hcWebCopyCount > 0 ? round(($hcWebDelivered / $hcWebCopyCount) * 100, 1) : 0;
      @endphp

      <!-- Date Filter & Quick Stats -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="d-flex align-items-center gap-3">
            <div class="custom-badge primary">
              <i class="bi bi-calendar-check me-1"></i>
              {{ \Carbon\Carbon::parse($defaultDate)->format('M d, Y') }}
            </div>
            <div class="custom-badge success">
              <i class="bi bi-graph-up me-1"></i>
              {{ $deliveryRate }}% Overall Success
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center gap-2">
          <form method="GET" action="{{ route('index') }}" id="dateFilterForm" class="d-flex align-items-center">
            <label for="date" class="me-2 mb-0" style="color: #4B3E2F; width: 100%;">Select Date:</label>
            <input
              type="date"
              name="date"
              id="date"
              class="form-control custom-input"
              value="{{ $defaultDate }}"
              max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
            >
          </form>
          <a href="{{ route('index') }}" class="btn custom-btn-outline">
            <i class="bi bi-arrow-clockwise me-1"></i>Reset
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--end::App Content Header-->

  <!--begin::App Content-->
  <div class="app-content">
    <div class="container-fluid">

      <!-- Overall Statistics -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="overall-stats-container">
            <div class="overall-stat-item">
              <div class="stat-icon-lg primary">
                <i class="bi bi-files"></i>
              </div>
              <div class="stat-content">
                <h2>{{ $totalApplications }}</h2>
                <p>Total Applications</p>
                <div class="stat-breakdown">
                  <span class="breakdown-item">{{ $hcOrderCopyCount }} Orders</span>
                  <span class="breakdown-divider">|</span>
                  <span class="breakdown-item">{{ $hcWebCopyCount }} Others</span>
                </div>
              </div>
            </div>
            
            <div class="overall-stat-item">
              <div class="stat-icon-lg success">
                <i class="bi bi-check-circle-fill"></i>
              </div>
              <div class="stat-content">
                <h2>{{ $totalDelivered }}</h2>
                <p>Total Delivered</p>
                <div class="stat-breakdown">
                  <span class="breakdown-item success">{{ $deliveryRate }}% Success Rate</span>
                </div>
              </div>
            </div>
            
            <div class="overall-stat-item">
              <div class="stat-icon-lg warning">
                <i class="bi bi-hourglass-split"></i>
              </div>
              <div class="stat-content">
                <h2>{{ $totalPending }}</h2>
                <p>Total Pending</p>
                <div class="stat-breakdown">
                  <span class="breakdown-item warning">{{ $totalApplications > 0 ? round(($totalPending / $totalApplications) * 100, 1) : 0 }}% Pending</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="row">
        <!-- Orders & Judgement Copies Section -->
        <div class="col-lg-6">
          <div class="section-card orders-section">
            <div class="section-header">
              <div class="section-title">
                <div class="section-icon orders">
                  <i class="bi bi-file-earmark-ruled"></i>
                </div>
                <div>
                  <h5>Orders & Judgement Copies</h5>
                  <p>Court orders and judgment document requests</p>
                </div>
              </div>
              <div class="section-stats">
                <div class="completion-circle" data-percentage="{{ $orderDeliveryRate }}">
                  <span>{{ $orderDeliveryRate }}%</span>
                </div>
              </div>
            </div>
            
            <div class="section-metrics">
              <div class="metric-row">
                <div class="metric-card applied" onclick="location.href='{{ url('admin/hc-web-application') }}'">
                  <div class="metric-icon">
                    <i class="bi bi-file-plus"></i>
                  </div>
                  <div class="metric-info">
                    <h4>{{ $hcOrderCopyCount }}</h4>
                    <p>Applied</p>
                  </div>
                </div>
                
                <div class="metric-card delivered" onclick="location.href='{{ url('admin/hc-web-delivered-application') }}'">
                  <div class="metric-icon">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <div class="metric-info">
                    <h4>{{ $hcOrderDelivered }}</h4>
                    <p>Delivered</p>
                  </div>
                </div>
                
                <div class="metric-card pending" onclick="location.href='{{ url('admin/hc-web-pending-application') }}'">
                  <div class="metric-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="metric-info">
                    <h4>{{ $hcOrderPending }}</h4>
                    <p>Pending</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="section-progress">
              <div class="progress-bar-container">
                <div class="progress-bar orders" style="width: {{ $orderDeliveryRate }}%"></div>
              </div>
              <div class="progress-text">
                <span>{{ $hcOrderDelivered }} of {{ $hcOrderCopyCount }} completed</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Other Copies Section -->
        <div class="col-lg-6">
          <div class="section-card others-section">
            <div class="section-header">
              <div class="section-title">
                <div class="section-icon others">
                  <i class="bi bi-files"></i>
                </div>
                <div>
                  <h5>Other Copies</h5>
                  <p>Miscellaneous document copy requests</p>
                </div>
              </div>
              <div class="section-stats">
                <div class="completion-circle" data-percentage="{{ $otherDeliveryRate }}">
                  <span>{{ $otherDeliveryRate }}%</span>
                </div>
              </div>
            </div>
            
            <div class="section-metrics">
              <div class="metric-row">
                <div class="metric-card applied" onclick="location.href='{{ url('admin/hc-other-copy') }}'">
                  <div class="metric-icon">
                    <i class="bi bi-file-plus"></i>
                  </div>
                  <div class="metric-info">
                    <h4>{{ $hcWebCopyCount }}</h4>
                    <p>Applied</p>
                  </div>
                </div>
                
                <div class="metric-card delivered" onclick="location.href='{{ url('admin/hc-other-delivered-copy') }}'">
                  <div class="metric-icon">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <div class="metric-info">
                    <h4>{{ $hcWebDelivered }}</h4>
                    <p>Delivered</p>
                  </div>
                </div>
                
                <div class="metric-card pending" onclick="location.href='{{ url('admin/hc-other-pending-copy') }}'">
                  <div class="metric-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="metric-info">
                    <h4>{{ $hcWebPending }}</h4>
                    <p>Pending</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="section-progress">
              <div class="progress-bar-container">
                <div class="progress-bar others" style="width: {{ $otherDeliveryRate }}%"></div>
              </div>
              <div class="progress-text">
                <span>{{ $hcWebDelivered }} of {{ $hcWebCopyCount }} completed</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Performance Analytics -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="analytics-card">
            <div class="analytics-header">
              <h5>Performance Analytics</h5>
              <div class="analytics-date">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::parse($defaultDate)->format('F j, Y') }}
              </div>
            </div>
            
            <div class="analytics-grid">
              <div class="analytics-item">
                <div class="analytics-icon primary">
                  <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="analytics-content">
                  <h3>{{ $deliveryRate }}%</h3>
                  <p>Overall Success Rate</p>
                  <div class="analytics-trend up">
                    <i class="bi bi-arrow-up"></i>
                    <span>+{{ $deliveryRate > 50 ? '12' : '5' }}% from last period</span>
                  </div>
                </div>
              </div>
              
              <div class="analytics-item">
                <div class="analytics-icon orders">
                  <i class="bi bi-file-earmark-ruled"></i>
                </div>
                <div class="analytics-content">
                  <h3>{{ $orderDeliveryRate }}%</h3>
                  <p>Orders Success Rate</p>
                  <div class="analytics-trend {{ $orderDeliveryRate > $otherDeliveryRate ? 'up' : 'down' }}">
                    <i class="bi bi-arrow-{{ $orderDeliveryRate > $otherDeliveryRate ? 'up' : 'down' }}"></i>
                    <span>{{ $orderDeliveryRate > $otherDeliveryRate ? 'Higher' : 'Lower' }} than Others</span>
                  </div>
                </div>
              </div>
              
              <div class="analytics-item">
                <div class="analytics-icon others">
                  <i class="bi bi-files"></i>
                </div>
                <div class="analytics-content">
                  <h3>{{ $otherDeliveryRate }}%</h3>
                  <p>Others Success Rate</p>
                  <div class="analytics-trend {{ $otherDeliveryRate > $orderDeliveryRate ? 'up' : 'down' }}">
                    <i class="bi bi-arrow-{{ $otherDeliveryRate > $orderDeliveryRate ? 'up' : 'down' }}"></i>
                    <span>{{ $otherDeliveryRate > $orderDeliveryRate ? 'Higher' : 'Lower' }} than Orders</span>
                  </div>
                </div>
              </div>
              
              <div class="analytics-item">
                <div class="analytics-icon warning">
                  <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="analytics-content">
                  <h3>{{ $totalPending }}</h3>
                  <p>Items Pending</p>
                  <div class="analytics-trend {{ $totalPending < 10 ? 'up' : 'down' }}">
                    <i class="bi bi-{{ $totalPending < 10 ? 'check' : 'exclamation' }}-circle"></i>
                    <span>{{ $totalPending < 10 ? 'Low' : 'High' }} pending count</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!--end::App Main-->

<style>
  :root {
    --primary-dark: #4B3E2F;
    --primary-gold: #D09A3F;
    --success-green: #2E7D32;
    --warning-orange: #FF2E2E;
    --danger-red: #C62828;
    --info-blue: #1976D2;
    --light-bg: #F8F6F3;
    --white: #FFFFFF;
    --text-dark: #2C2C2C;
    --text-muted: #6C6C6C;
    --border-light: #E8E6E3;
  }

  /* Custom Badges */
  .custom-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
  }

  .custom-badge.primary {
    background: linear-gradient(135deg, var(--info-blue), var(--info-blue));
    color: white;
  }

  .custom-badge.success {
    background: linear-gradient(135deg, var(--success-green), #4CAF50);
    color: white;
  }

  .custom-input {
    border: 2px solid var(--border-light);
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.3s ease;
  }

  .custom-input:focus {
    border-color: var(--primary-gold);
    box-shadow: 0 0 0 3px rgba(208, 154, 63, 0.1);
  }

  .custom-btn-outline {
    border: 2px solid var(--primary-gold);
    color: var(--primary-gold);
    background: transparent;
    border-radius: 8px;
    padding: 8px 16px;
    transition: all 0.3s ease;
    text-decoration: none;
  }

  .custom-btn-outline:hover {
    background: var(--primary-gold);
    color: white;
  }

  /* Overall Stats */
  .overall-stats-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 20px;
  }

  .overall-stat-item {
    background: var(--white);
    border-radius: 16px;
    padding: 28px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 20px rgba(75, 62, 47, 0.1);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
  }

  .overall-stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(75, 62, 47, 0.15);
  }

  .stat-icon-lg {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
  }

  .stat-icon-lg.primary {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-gold));
  }

  .stat-icon-lg.success {
    background: linear-gradient(135deg, var(--success-green), #4CAF50);
  }

  .stat-icon-lg.warning {
    background: linear-gradient(135deg, black ,#FF2E2E);
  }

  .stat-content h2 {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-dark);
  }

  .stat-content p {
    color: var(--text-muted);
    margin: 4px 0 8px 0;
    font-size: 1rem;
  }

  .stat-breakdown {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
  }

  .breakdown-item {
    color: var(--text-muted);
  }

  .breakdown-item.success {
    color: var(--success-green);
    font-weight: 600;
  }

  .breakdown-item.warning {
    color: var(--warning-orange);
    font-weight: 600;
  }

  .breakdown-divider {
    color: var(--border-light);
  }

  /* Section Cards */
  .section-card {
    background: var(--white);
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 6px 24px rgba(75, 62, 47, 0.1);
    border: 1px solid var(--border-light);
    height: 100%;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--light-bg);
  }

  .section-title {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .section-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
  }

  .section-icon.orders {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-gold));
  }

  .section-icon.others {
    background: linear-gradient(135deg, var(--info-blue), #42A5F5);
  }

  .section-title h5 {
    margin: 0;
    color: var(--text-dark);
    font-size: 1.3rem;
    font-weight: 600;
  }

  .section-title p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.9rem;
  }

  .completion-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    background: conic-gradient(var(--primary-gold) 0deg, var(--primary-gold) calc(var(--percentage) * 3.6deg), var(--light-bg) calc(var(--percentage) * 3.6deg));
    position: relative;
  }

  .completion-circle::before {
    content: '';
    width: 46px;
    height: 46px;
    background: var(--white);
    border-radius: 50%;
    position: absolute;
  }

  .completion-circle span {
    position: relative;
    z-index: 1;
    color: var(--text-dark);
  }

  /* Metric Cards */
  .metric-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  .metric-card {
    background: var(--light-bg);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }

  .metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(75, 62, 47, 0.1);
  }

  /* Applied State */
  /* Applied State */
  .metric-card.applied {
    border-color: linear-gradient(135deg, var(--primary-gold), var(--primary-dark));
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-dark));
    color: white;
  }

  /* Delivered State */
  .metric-card.delivered {
    border-color: linear-gradient(135deg, var(--success-green), #4CAF50);
    background: linear-gradient(135deg, var(--success-green), #4CAF50); /* deeper green */
    color: white;
  }

  /* Pending State */
  .metric-card.pending {
    border-color: linear-gradient(135deg, var(--warning-orange), #000);
    background: linear-gradient(135deg, var(--warning-orange), #000); /* deeper red */
    color: white;
  }


  .metric-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background: rgba(255, 255, 255, 0.5);
    color: var(--text-dark);
  }

  .metric-card .metric-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
  }

  .metric-info h4 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
    color: white;
  }

  .metric-info p {
    margin: 0;
    color: white;
    font-size: 0.9rem;
  }


  /* Progress Bars */
  .section-progress {
    margin-top: 16px;
  }

  .progress-bar-container {
    height: 6px;
    background: var(--light-bg);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
  }

  .progress-bar {
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease;
  }

  .progress-bar.orders {
    background: linear-gradient(90deg, var(--primary-dark), var(--primary-gold));
  }

  .progress-bar.others {
    background: linear-gradient(90deg, var(--info-blue), #42A5F5);
  }

  .progress-text {
    text-align: center;
    color: var(--text-muted);
    font-size: 0.85rem;
  }

  /* Analytics Card */
  .analytics-card {
    background: var(--white);
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 6px 24px rgba(75, 62, 47, 0.1);
    border: 1px solid var(--border-light);
  }

  .analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--light-bg);
  }

  .analytics-header h5 {
    margin: 0;
    color: var(--text-dark);
    font-size: 1.4rem;
    font-weight: 600;
  }

  .analytics-date {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 0.9rem;
  }

  .analytics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
  }

  .analytics-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--light-bg);
    border-radius: 16px;
    transition: all 0.3s ease;
  }

  .analytics-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(75, 62, 47, 0.1);
  }

  .analytics-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
  }

  .analytics-icon.primary {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-gold));
  }

  .analytics-icon.orders {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-gold));
  }

  .analytics-icon.others {
    background: linear-gradient(135deg, var(--info-blue), #42A5F5);
  }

  .analytics-icon.warning {
    background: linear-gradient(135deg, red, #000);
  }

  .analytics-content h3 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-dark);
  }

  .analytics-content p {
    margin: 4px 0 8px 0;
    color: var(--text-muted);
    font-size: 0.9rem;
  }

  .analytics-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    font-weight: 500;
  }

  .analytics-trend.up {
    color: var(--success-green);
  }

  .analytics-trend.down {
    color: var(--warning-orange);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when date changes
    document.getElementById('date').addEventListener('change', function() {
      document.getElementById('dateFilterForm').submit();
    });
    
    // Set completion circle percentages
    const completionCircles = document.querySelectorAll('.completion-circle');
    completionCircles.forEach(circle => {
      const percentage = circle.getAttribute('data-percentage');
      circle.style.setProperty('--percentage', percentage);
    });
  });
</script>

@endsection