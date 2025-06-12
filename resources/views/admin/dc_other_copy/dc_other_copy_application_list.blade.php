@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Other Copy Application List')

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
          <div class="col-sm-6"><h3 class="mb-0">Other Copy Application</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Other Copy</li>
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
              <div class="card-header"><div class="card-title">Other Copy Application List</div></div>
              <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <table id="myTable" class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Case No/Filing No</th>
                            <th>Date</th>
                            <th>Document Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dcuserdata as $index => $dcuser)
                         @php
                            $typeName = $dcuser->case_type_name ?? ''; // Adjust key as needed
                            $caseType = explode(':', $typeName)[0];
                         @endphp

                        <tr>
                            <td>{{ $index + 1 }}{{ session('user.estd_code') }}</td>
                            <td>{{ $dcuser->application_number }}</td>
                            <td>{{ $dcuser->applicant_name }}</td>
                            <td>{{ $dcuser->mobile_number }}</td>
                            <td>
                                {{ $caseType . '/' . $dcuser->case_filling_number . '/' . $dcuser->case_filling_year }}
                                @if ($dcuser->selected_method == 'F') 
                                    (Filing No) 
                                @elseif ($dcuser->selected_method == 'C')
                                    (Case No)
                                @else 

                                    {{ $dcuser->selected_method }}  {{-- Default fallback --}}
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($dcuser->created_at)->format('d-m-Y H:i:s') }}</td>
                            <td>
                                @if ($dcuser->document_status == 1)
                                    <span class="badge bg-success">Uploaded</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                              <a href="{{ route('dc_other_copy_view', Crypt::encrypt($dcuser->application_number)) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i>View</a>
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