@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Web Copy Delivered Application List')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
@endphp

<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0 text-success">Highcourt Web Copy Delivered Copies List</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">HC Delivered Web Copy</li>
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
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header"><div class="card-title text-success">Highcourt Web Copy Delivered List</div></div>
                        <div class="card-body">
                            <table id="myTable" class="table table-bordered">
                                <thead class="table-success">
                                    <tr>
                                        <th>#</th>
                                        <th>Application No</th>
                                        <th>Name</th>
                                        <th>Mobile No</th>
                                        <th>Case No</th>
                                        <th>Filing No</th>
                                        <th>Date</th>
                                        <th>Document Status</th>
                                        <th>View</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @forelse ($hcuserdata as $hcuser)
                                        @if ($hcuser->certified_copy_ready_status == 1)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>{{ $hcuser->application_number }}</td>
                                                <td>{{ $hcuser->applicant_name }}</td>
                                                <td>{{ $hcuser->mobile_number }}</td>
                                                <td>
                                                    @if ($hcuser->case_number)
                                                        {{ $hcuser->type_name }}/{{ $hcuser->case_number }}/{{ $hcuser->case_year }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($hcuser->filing_number)
                                                        {{ $hcuser->type_name }}/{{ $hcuser->filing_number }}/{{ $hcuser->filing_year }}
                                                    @endif
                                                </td>
                                                <td>{{ Carbon::parse($hcuser->created_at)->format('d-m-Y') }}</td>
                                                <td>
                                                    <span class="badge bg-success">Delivered</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('hc-web-application.view', Crypt::encrypt($hcuser->application_number)) }}" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr><td colspan="9" class="text-center">No Delivered Copies Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            pageLength: 100 // Show 100 entries by default
        });
    });
</script>
@endpush

@endsection
