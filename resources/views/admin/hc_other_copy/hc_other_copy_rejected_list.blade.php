@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Rejected Application List')

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
          <div class="col-sm-6"><h3 class="mb-0">Highcourt Other Copy Rejected Application</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">HC Other Copy</li>
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
              <div class="card-header"><div class="card-title">Highcourt Other Copy Rejected Application List</div></div>
              <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <table id="myTable" class="table table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Case No/Filing No</th>
                            <th>Apply Date</th>
                            <th>Rejected Date</th>
                            <th>View Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hcuserdata as $index => $hcuser)
                        <tr>
                            <td>{{ $index + 1 }}</td>
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
                                    {{ $hcuser->selected_method }}  {{-- Default fallback --}}
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($hcuser->created_at)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($hcuser->rejection_date)->format('d-m-Y') }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm view-details-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectionModal" 
                                    data-reason="{{ $hcuser->rejection_remarks }}"
                                    data-rejection-date="{{ $hcuser->rejection_date ? \Carbon\Carbon::parse($hcuser->rejection_date)->format('d-m-Y') : 'N/A' }}"
                                    data-required-documents="{{ $hcuser->required_document }}">
                                    <i class="bi bi-eye"></i>&nbsp;View Reason
                                </button>
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

<!-- Rejection Details Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="rejectionModalLabel">Rejection Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Required Documents at the Top -->
        <div class="mb-3">
            <label for="requiredDocumentsText" class="form-label"><strong>Required Documents:</strong></label>
            <p id="requiredDocumentsText" class="alert alert-light"></p>
        </div>
    
        <hr> <!-- Separator -->
    
        <div class="mb-3">
            <label for="rejectionReasonText" class="form-label"><strong>Rejection Reason:</strong></label>
            <p id="rejectionReasonText" class="alert alert-warning"></p>
        </div>
    
        <div class="mb-3">
            <label for="rejectionDateText" class="form-label"><strong>Rejection Date:</strong></label>
            <p id="rejectionDateText" class="alert alert-info"></p>
        </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable(); // Initialize DataTables
            
            // Handle Rejection Details Modal
            $('.view-details-btn').on('click', function () {
                var requiredDocuments = $(this).data('required-documents') || 'N/A';
                var reason = $(this).data('reason') || 'No reason provided';
                var rejectionDate = $(this).data('rejection-date') || 'N/A';

                $('#requiredDocumentsText').text(requiredDocuments);
                $('#rejectionReasonText').text(reason);
                $('#rejectionDateText').text(rejectionDate);
            });
        });
    </script>
@endpush
@endsection
