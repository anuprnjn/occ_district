@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Web Copy Application List')

@section('content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">District Other Copy Application</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Display Role List -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-semibold">District Other Copy Application List</h5>
                    </div>
                    
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4 table-primary">
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
                                    <td>{{ $dcuser->case_type_name . '/' . $dcuser->case_filling_number . '/' . $dcuser->case_filling_year }}
                                        @if ($dcuser->selected_method == 'F') (Filing No) 
                                        @elseif ($dcuser->selected_method == 'C') (Case No)
                                        @else
                                            {{ $dcuser->selected_method }} {{-- Default fallback --}}
                                        @endif
                                    </td>
                                    <td>{{ $dcuser->created_at }}</td>
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
@endsection
