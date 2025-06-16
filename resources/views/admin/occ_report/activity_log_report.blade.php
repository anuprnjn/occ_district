@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Activity Logs Report')

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
                <div class="col-sm-6"><h3 class="mb-0 text-info">Activity Logs Report</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Activity Logs Report</li>
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
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title text-info">Activity Logs Report</div>
                        </div>
                        <div class="card-body">

                            <!-- Date Range Filter -->
                            <form method="GET" action="" class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" class="form-control" value="">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary">Filter</button>
                                    <a href="" class="btn btn-secondary ms-2">Reset</a>
                                </div>
                            </form>
                            <!-- End Filter -->

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
