@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Web Copy Application Details')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Web Copy Application Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Application Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Application Details</h5> 
                                <a href="{{ route('hc_web_application_list') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr><th>Application No</th><td>{{ $hcuser->application_number }}</td></tr>
                                        <tr><th>Name</th><td>{{ $hcuser->applicant_name }}</td></tr>
                                        <tr><th>Mobile No</th><td>{{ $hcuser->mobile_number }}</td></tr>
                                        <tr><th>Email</th><td>{{ $hcuser->email }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr><th>Case No</th><td>{{ $hcuser->type_name }}/{{ $hcuser->case_number }}/{{ $hcuser->case_year }}</td></tr>
                                        <tr><th>Filing No</th><td>{{ $hcuser->type_name }}/{{ $hcuser->filing_number }}/{{ $hcuser->filing_year }}</td></tr>
                                        <tr><th>Request Mode</th><td>{{ $hcuser->request_mode }}</td></tr>
                                        <tr><th>Status</th><td>{{ $hcuser->status }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr><th>Payment Status</th><td>{{ $hcuser->payment_status }}</td></tr>
                                        <tr><th>Applied By</th><td>{{ $hcuser->applied_by }}</td></tr>
                                        <tr><th>Created At</th><td>{{ $hcuser->created_at }}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@push('scripts')
    
@endpush
@endsection
