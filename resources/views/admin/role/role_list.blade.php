@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Role List')

@section('content')
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Role Master</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('role_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="role_name" placeholder="Enter Role Name" name="role_name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Role</button>
                        </form>
                    </div>
                </div>

                <!-- Display Role List -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Role List</h5>

                        <table class="table text-nowrap mb-0 align-middle ">
                            <thead class="text-dark fs-4 table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                @forelse ($roledata as $index => $role)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $role['role_name'] }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editRole({{ $role['role_id'] }}, '{{ $role['role_name'] }}')">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No Roles found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Edit Role Modal -->
                <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('role_update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="role_id" id="edit_role_id">
                                    <div class="mb-3">
                                        <label for="edit_role_name" class="form-label">Role Name</label>
                                        <input type="text" class="form-control" id="edit_role_name" name="role_name" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function editRole(id, name) {
    document.getElementById('edit_role_id').value = id;
    document.getElementById('edit_role_name').value = name;
    new bootstrap.Modal(document.getElementById('editRoleModal')).show();
}
</script>

@endsection
