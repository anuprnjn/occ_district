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
          <div class="col-sm-6"><h3 class="mb-0 text-danger">Web Copy Pending Request List</h3></div>
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
            <div class="card card-danger card-outline mb-4">
              <div class="card-header"><div class="card-title text-danger">Web Copy Request List</div></div>
              <div class="card-body">
                <table id="myTable" class="table table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Case No</th>
                            <th>Filing No</th>
                            <th>Date</th>
                            <th>Document Status</th>
                            <th>Certified Copy Status</th>
                            <th>View</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dcuserdata as $index => $dcuser)
                         @php
                            $typeName = $dcuser->type_name ?? ''; // Adjust key as needed
                            $caseType = explode(':', $typeName)[0];
                         @endphp
                        <tr> 
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dcuser->application_number }}</td>
                            <td>{{ $dcuser->applicant_name }}</td>
                            <td>{{ $dcuser->mobile_number }}</td>
                            <td>
                                @if ($dcuser->case_number)
                                    {{ $caseType }}/{{ $dcuser->case_number }}/{{ $dcuser->case_year }}
                                @else
                                   
                                @endif
                            </td>
                            <td>
                                @if ($dcuser->filing_number)
                                    {{ $caseType }}/{{ $dcuser->filing_number }}/{{ $dcuser->filing_year }}
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
                                @if ($dcuser->certified_copy_ready_status == 1)
                                    <span class="badge bg-success">Delivered</span>
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