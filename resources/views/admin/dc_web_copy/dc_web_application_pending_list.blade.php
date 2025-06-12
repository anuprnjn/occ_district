@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Web Copy Application List')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon; // Import Carbon for date formatting
@endphp

<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0">Web Copy Request List</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Web Copy</li>
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
              <div class="card-header"><div class="card-title">Web Copy Request List</div></div>
              <div class="card-body">
                <table id="myTable" class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Case No</th>
                            <th>Filing No</th>
                            <th>Date</th>
                            <th>Document Status</th> <!-- New Column for Document Status -->
                            <th>View</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dcuserdata as $index => $dcuser)
                        <tr> 
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dcuser->application_number }}</td>
                            <td>{{ $dcuser->applicant_name }}</td>
                            <td>{{ $dcuser->mobile_number }}</td>
                            <td>
                                @if ($dcuser->case_number)
                                    {{ $dcuser->type_name }}/{{ $dcuser->case_number }}/{{ $dcuser->case_year }}
                                @else
                                   
                                @endif
                            </td>
                            <td>
                                @if ($dcuser->filing_number)
                                    {{ $dcuser->type_name }}/{{ $dcuser->filing_number }}/{{ $dcuser->filing_year }}
                                @else
                                    {{-- Show nothing --}}
                                @endif
                            </td>
                            <td>{{ Carbon::parse($dcuser->created_at)->format('d-m-Y') }}</td> <!-- Format the date -->
                            <td>
                                @if ($dcuser->document_status == 1)
                                    <span class="badge bg-success">Uploaded</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dc-web-application.view', Crypt::encrypt($dcuser->application_number)) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i>View</a>
                            </td>
                        </tr>
                    @empty
                       
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