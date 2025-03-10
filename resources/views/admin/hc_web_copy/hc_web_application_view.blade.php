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
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0 me-2">Application Details</h5>
                                    <button onclick="printDiv('printablearea')" class="btn btn-success">
                                        <i class="bi bi-printer"></i> Print
                                    </button>
                                </div>
                                <a href="{{ route('hc_web_application_list') }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i>Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="printablearea">
                            <div class="row">
                                <!-- Applicant Details -->
                                <h4 class="text-center">
                                    <strong><u>Orders And Judgement Copy</u></strong>
                                </h4>
                                <div class="col-md-4">
                                    <p class="fw-bold text-success">Applicant Details</p>
                                    <table class="table table-bordered">
                                        <tr><th class="fw-bold">Application No</th><td>{{ $hcuser->application_number }}</td></tr>
                                        <tr><th class="fw-bold">Name</th><td>{{ $hcuser->applicant_name }}</td></tr>
                                        <tr><th class="fw-bold">Mobile No</th><td>{{ $hcuser->mobile_number }}</td></tr>
                                        <tr><th class="fw-bold">Email</th><td>{{ $hcuser->email }}</td></tr>
                                    </table>
                                </div>

                                <!-- Case Details -->
                                <div class="col-md-4">
                                    <p class="fw-bold text-success">Case Details</p>
                                    <table class="table table-bordered">
                                        <tr><th class="fw-bold">Case No</th><td>{{ $hcuser->type_name }}/{{ $hcuser->case_number }}/{{ $hcuser->case_year }}</td></tr>
                                        <tr><th class="fw-bold">Filing No</th><td>{{ $hcuser->type_name }}/{{ $hcuser->filing_number }}/{{ $hcuser->filing_year }}</td></tr>
                                        <tr><th class="fw-bold">Request Mode</th><td>{{ $hcuser->request_mode }}</td></tr>
                                        <tr><th class="fw-bold">Status</th><td>{{ $hcuser->status }}</td></tr>
                                    </table>
                                </div>

                                <!-- Payment Details -->
                                <div class="col-md-4">
                                    <p class="fw-bold text-success">Payment Details</p>
                                    <table class="table table-bordered">
                                        <tr><th class="fw-bold">Payment Status</th><td>{{ $hcuser->payment_status }}</td></tr>
                                        <tr><th class="fw-bold">Applied By</th><td>{{ $hcuser->applied_by }}</td></tr>
                                        <tr><th class="fw-bold">Created At</th><td>{{ $hcuser->created_at }}</td></tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="fw-bold text-success">Order Details</p>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">Order No</th>
                                                <th class="fw-bold">Order Date</th>
                                                <th class="fw-bold">Page No</th>
                                                <th class="fw-bold">Amount</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ordersdata as $key => $order)
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->order_date }}</td>
                                                    <td>{{ $order->number_of_page }}</td>
                                                    <td>{{ number_format($order->amount, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No orders found for this application.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>   
                            </div> 
                        </div>   

                        </div>
                    </div>

                    <!-- New Section: Order And Judgment Copy (Web Copy) -->
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Upload Document (Web Copy)</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Order No</th>
                                        <th class="fw-bold">Order Date</th>
                                        <th class="fw-bold">Page No</th>
                                        <th class="fw-bold">Upload</th>
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordersdata as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->order_date }}</td>
                                            <td>{{ $order->number_of_page }}</td>
                                            <td>
                                                <form action="{{ route('admin.uploadOrderCopy') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="order_number" value="{{ $order->order_number }}">
                                                    <input type="file" name="pdf_file" class="form-control mb-2" required>
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-upload"></i> Upload
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                @if($order->upload_status)
                                                    <span class="badge bg-success">Uploaded</span>
                                                @else
                                                    <span class="badge bg-danger">Not Uploaded</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->upload_status)
                                                    <a href="{{ route('admin.downloadOrderCopy', $order->file_name) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('admin.deleteOrderCopy', $order->order_number) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                @else
                                                    <span class="text-muted">No File</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    

                   

                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents; // Replace body with printable content
        window.print();
        document.body.innerHTML = originalContents; // Restore original content after printing
        location.reload(); // Reload the page to restore event handlers
    }
</script>
@endpush
@endsection
