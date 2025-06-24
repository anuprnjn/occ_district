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
          <div class="col-sm-6"><h3 class="mb-0">Highcourt Web Copy Request List</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Hc All Web Copy</li>
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
                            <th>Certified Copy Status</th> 
                            <th>Payment Status</th>
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
                            @php
                                $shortType = explode(':', $hcuser->type_name)[0];
                            @endphp

                            <td>
                                @if ($hcuser->case_number)
                                    {{ trim($shortType) }} :/{{ implode('/', [$hcuser->case_number, $hcuser->case_year]) }}
                                @endif
                            </td>

                            <td>
                                @if ($hcuser->filing_number)
                                    {{ trim($shortType) }} :/{{ implode('/', [$hcuser->filing_number, $hcuser->filing_year]) }}
                                @endif
                            </td>
                            <td>{{ Carbon::parse($hcuser->created_at)->format('d-m-Y') }}</td>
                             <td>
                                @if ($hcuser->document_status == 1)
                                    <span class="badge bg-success">Uploaded</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if ($hcuser->certified_copy_ready_status == 1)
                                    <span class="badge bg-success">Delivered</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                              @if($hcuser->payment_status == 1)
                                <span class="badge bg-success">Cleared</span>
                              @else
                                <span class="badge bg-danger">Pending</span>
                              @endif    
                            </td>  
                            @if($hcuser->payment_status === 1)
                            <td>
                                <a href="{{ route('hc-web-application.view', Crypt::encrypt($hcuser->application_number)) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i>View</a>
                            </td>
                            @else
                            <td>
                              <a href="{{ route('hc-web-application.view', Crypt::encrypt($hcuser->application_number)) }}" class="btn btn-primary btn-sm disabled"><i class="bi bi-eye"></i>View</a>
                            </td>  
                            @endif
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
        $('#myTable').DataTable({
            pageLength: 100 // Show 100 entries by default
        });
    });
</script>
    
@endpush

@endsection