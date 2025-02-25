@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Payment Parameter List')

@section('content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Payment Parameter</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-semibold">Payment Parameter List</h5>
                    </div>

                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4 table-primary">
                            <tr>
                                <th>#</th>
                                <th>Dept Id</th>
                                <th>Reciept Head Code</th>
                                <th>Treas Code</th>
                                <th>Ifms Office Code</th>
                                <th>Security Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @forelse ($payparameterdata as $index => $payparameter)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payparameter['deptid'] }}</td>
                                    <td>{{ $payparameter['recieptheadcode'] }}</td>
                                    <td>{{ $payparameter['treascode'] }}</td>
                                    <td>{{ $payparameter['ifmsofficecode'] }}</td>
                                    <td>{{ $payparameter['treascode'] }}</td>
                                    
                                    <td>
                                        <button class="btn btn-warning btn-sm" 
                                                onclick="openEditModal({{ json_encode($payparameter) }})">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Records Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Payment Parameter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="{{ route('payment_parameter_update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-3">
                        <label class="form-label">Dept ID</label>
                        <input type="text" class="form-control" name="deptid" id="edit_deptid">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reciept Head Code</label>
                        <input type="text" class="form-control" name="recieptheadcode" id="edit_recieptheadcode">
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
                        <input type="text" class="form-control" name="securitycode" id="edit_securitycode">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(payparameter) {
        document.getElementById('edit_id').value = payparameter.id;
        document.getElementById('edit_deptid').value = payparameter.deptid;
        document.getElementById('edit_recieptheadcode').value = payparameter.recieptheadcode;
        document.getElementById('edit_treascode').value = payparameter.treascode;
        document.getElementById('edit_ifmsofficecode').value = payparameter.ifmsofficecode;
        document.getElementById('edit_securitycode').value = payparameter.treascode; // Security Code is missing, so using treascode

        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
</script>

@endsection