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
          <div class="col-sm-6"><h3 class="mb-0">District Other Copy Application</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">DC Other Copy</li>
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
              <div class="card-header"><div class="card-title">District Other Copy Application List</div></div>
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
                                {{ $dcuser->case_type_name . '/' . $dcuser->case_filling_number . '/' . $dcuser->case_filling_year }}
                                @if ($dcuser->selected_method == 'F') 
                                    (Filing No) 
                                @elseif ($dcuser->selected_method == 'C')
                                    (Case No)
                                @else
                                    {{ $dcuser->selected_method }}  {{-- Default fallback --}}
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($dcuser->created_at)->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No DC Users found</td>
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
    
@endpush

@endsection