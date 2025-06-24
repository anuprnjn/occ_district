@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Delivered Report Civil Court')

@section('content')
@php use Carbon\Carbon; @endphp

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0 text-success">Delivered Report Civil Court</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Delivered Report Civil Court</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title text-success">Delivered Report Civil Court</div>
                        </div>
                        <div class="card-body">

                            <!-- Date Filter -->
                            <form method="GET" action="{{ route('admin.delivered.report_dc') }}" class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" class="form-control" value="{{ $from }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" class="form-control" value="{{ $to }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary">Filter</button>
                                    <a href="{{ route('admin.delivered.report_dc') }}" class="btn btn-secondary ms-2">Reset</a>
                                </div>
                            </form>

                            @if($from && $to)

                            <!-- Printable Section -->
                            <div id="print-section">
                                <!-- Summary -->
                                <div class="mb-3">
                                    <h5>Total Delivered Applications:
                                        <span class="badge bg-success fs-6">{{ $totalDelivered }}</span>
                                    </h5>
                                </div>

                                <!-- Table -->
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Application Number</th>
                                                <th>Applicant Name</th>
                                                <th>Mobile Number</th>
                                                <th>Email</th>
                                                <th>Delivered Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($applications as $key => $app)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $app->application_number }}</td>
                                                    <td>{{ $app->applicant_name }}</td>
                                                    <td>{{ $app->mobile_number }}</td>
                                                    <td>{{ $app->email }}</td>
                                                    <td>{{ Carbon::parse($app->updated_at)->format('d-m-Y H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No delivered applications found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                       
                                </div>
                            </div>
                            <!-- Print Button -->
                            <div class="mb-3 text-end">
                                <button class="btn btn-danger d-flex align-items-center justify-content-end gap-2" onclick="printReport()">
                                    <img src="{{ asset('passets/images/icons/print.svg') }}" alt="Print" width="20" height="20">
                                    <span>Print Report</span>
                                </button>
                            </div>
                            @endif

                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
@if($from && $to)

<script>
function printReport() {
    const printContents = document.getElementById('print-section').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h5 { margin-top: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
                    table, th, td { border: 1px solid #ccc; }
                    th, td { padding: 3px; text-align: left; font-size: 12px; }
                    .badge { background-color: green; color: white; padding: 5px 10px; border-radius: 4px; }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
        </html>
    `;

    window.print();

    // Reload page to restore original layout after print
    window.location.reload();
}
</script>
@endif
@endpush
