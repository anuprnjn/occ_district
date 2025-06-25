@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Payment Report')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
@endphp

<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0 text-secondary">Payment Report</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-secondary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title text-secondary">Payment Report</div>
                        </div>
                        <div class="card-body">

                            <!-- Date Range Filter -->
                            <form method="GET" action="{{ route('payment_report') }}" class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" class="form-control" value="{{ $from }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" class="form-control" value="{{ $to }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-info text-white">
                                        <i class="bi bi-search me-1"></i> Search
                                    </button>
                                     <a href="{{ route('payment_report') }}" class="btn btn-dark ms-2">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
                            <!-- End Filter -->

                            <!-- Summary Cards: 50% each -->
                            <div class="row g-4 mt-4">

                                <div class="col-md-6">
                                    <div class="card h-100 shadow" style="background-color: #4B3E2F; color: #ffffff;">
                                        <div class="card-body">
                                            <h6 class="text-uppercase mb-2">Total Applied Applications</h6>
                                            <h3 class="fw-bold">{{ $totalApplications }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card h-100 shadow" style="background-color: #D09A3F; color: #fff;">
                                        <div class="card-body">
                                            <h6 class="text-uppercase mb-2">Total Collection</h6>
                                            <h3 class="fw-bold">
                                                ₹{{ number_format($totalAmount + $deficitAmount, 2) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- row -->
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>
</main>

@push('styles')
@endpush

@push('scripts')
@endpush

@endsection
