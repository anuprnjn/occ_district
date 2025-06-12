@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Web Copy Application List')

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
                    <h3 class="mb-0 text-success">Highcourt Others Copy Delivered Application</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hc Delivered Others Copy</li>
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
                        <div class="card-header">
                            <div class="card-title text-success">Highcourt Others Copy Delivered Application List</div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <table id="myTable" class="table table-bordered">
                                <thead class="table-success">
                                    <tr>
                                        <th>#</th>
                                        <th>Application No</th>
                                        <th>Name</th>
                                        <th>Mobile No</th>
                                        <th>Case No/Filing No</th>
                                        <th>Date</th>
                                        <th>Certified Copy Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $serial = 1; @endphp
                                    @forelse ($hcuserdata as $hcuser)
                                        @if ($hcuser->certified_copy_ready_status == 1)
                                            <tr>
                                                <td>{{ $serial++ }}</td>
                                                <td>{{ $hcuser->application_number }}</td>
                                                <td>{{ $hcuser->applicant_name }}</td>
                                                <td>{{ $hcuser->mobile_number }}</td>
                                                <td>
                                                    {{ $hcuser->case_type_name . '/' . $hcuser->case_filling_number . '/' . $hcuser->case_filling_year }}
                                                    @if ($hcuser->selected_method == 'F') 
                                                        (Filing No) 
                                                    @elseif ($hcuser->selected_method == 'C')
                                                        (Case No)
                                                    @else
                                                        {{ $hcuser->selected_method }} {{-- Default fallback --}}
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($hcuser->created_at)->format('d-m-Y') }}</td>
                                                <td><span class="badge bg-success">Delivered</span></td>
                                                <td>
                                                    <a href="{{ route('hc_other_copy_view', Crypt::encrypt($hcuser->application_number)) }}" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Delivered Applications Found</td>
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

@push('styles')
@endpush

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
