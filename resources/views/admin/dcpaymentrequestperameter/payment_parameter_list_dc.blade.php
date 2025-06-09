@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Payment Parameter List')

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
                <div class="col-sm-6"><h3 class="mb-0">Payment Parameter</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Parameter</li>
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
                        <div class="card-header"><div class="card-title">Payment Parameter List</div></div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <table id="myTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Dept Id</th>
                                        <th>Reciept Head Code</th>
                                        <th>Treas Code</th>
                                        <th>Ifms Office Code</th>
                                        <th>Security Code</th>
                                        <th>District Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payparameterdata as $index => $payparameter)

                                    @if (session('user.role_id') == 1 || session('user.dist_code') == $payparameter['dist_code'])
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $payparameter['deptid'] }}</td>
                                            <td>{{ $payparameter['recieptheadcode'] }}</td>
                                            <td>{{ $payparameter['treascode'] }}</td>
                                            <td>{{ $payparameter['ifmsofficecode'] }}</td>
                                            <td>{{ $payparameter['securitycode'] }}</td>
                                            <td>{{ $payparameter['dist_name'] }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" 
                                                        onclick="openEditModal({{ json_encode($payparameter) }})">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                       @endif 
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Payment Parameter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="{{ route('payment_parameter_update_dc') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dist_code" id="edit_dist_code">

                    <div class="mb-3">
                        <label class="form-label">Dept ID</label>
                        <input type="text" class="form-control" name="deptid" id="edit_deptid" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reciept Head Code</label>
                        <input type="text" class="form-control" name="recieptheadcode" id="edit_recieptheadcode" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Treas Code</label>
                        <input type="text" class="form-control" name="treascode" id="edit_treascode">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ifms Office Code</label>
                        <input type="text" class="form-control" name="ifmsofficecode" id="edit_ifmsofficecode">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Security Code</label>
                        <input type="text" class="form-control" name="securitycode" id="edit_securitycode" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(payparameter) {
    console.log("DC",payparameter);
        document.getElementById('edit_dist_code').value = payparameter.dist_code;
        document.getElementById('edit_deptid').value = payparameter.deptid;
        document.getElementById('edit_recieptheadcode').value = payparameter.recieptheadcode;
        document.getElementById('edit_treascode').value = payparameter.treascode;
        document.getElementById('edit_ifmsofficecode').value = payparameter.ifmsofficecode;
        document.getElementById('edit_securitycode').value = payparameter.securitycode;

        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
</script>

@push('styles')
@endpush

@push('scripts')
@endpush

@endsection
