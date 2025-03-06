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
          <div class="col-sm-6"><h3 class="mb-0">Highcourt Web Copy Request List</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Hc Web Copy</li>
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
              <div class="card-header"><div class="card-title">Highcourt Web Copy Request List</div></div>
              <div class="card-body">
                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Case No</th>
                            <th>Filing No</th>
                            <th>Date</th>
                            <th>View</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hcuserdata as $index => $hcuser)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $hcuser->application_number }}</td>
                            <td>{{ $hcuser->applicant_name }}</td>
                            <td>{{ $hcuser->mobile_number }}</td>
                            <td>{{ $hcuser->type_name }}/{{ $hcuser->case_number }}/{{ $hcuser->case_year }}</td>
                            <td>{{ $hcuser->type_name }}/{{ $hcuser->filing_number }}/{{ $hcuser->filing_year }}</td>
                            <td>{{ $hcuser->created_at }}</td>
                            <td>
                                <a href="{{ route('hc-web-application.view', Crypt::encrypt($hcuser->application_number)) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No HC Users found</td>
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
            $('#myTable').DataTable(); // Initialize DataTables
        });
    </script>
    
@endpush



@endsection
