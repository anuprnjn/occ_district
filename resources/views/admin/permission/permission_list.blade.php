@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Sub Menu List')

@section('content')
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Role And Permission</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('submenu_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="menu_id" class="form-label">Select Role</label>
                                <select class="form-select" id="menu_id" name="menu_id" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach($roledata as $role)
                                        <option value="{{ $role['role_id'] }}">{{ $role['role_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            

                            <button type="submit" class="btn btn-primary">Add Permission</button>
                        </form>
                    </div>
                </div>

                <!-- Display Sub Menu List -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Sub Menu List</h5>

                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th>#</th>
                                    <th>Menu Name</th>
                                    <th>Sub Menu Name</th>
                                    <th>URL</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                @php $lastMenuName = null; @endphp
                        
                                @forelse ($submenudata as $index => $submenu)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                        
                                        <!-- Display Menu Name only if it changes -->
                                        <td>
                                            @if ($lastMenuName !== $submenu['menu_name'])
                                                <strong>{{ $submenu['menu_name'] }}</strong>
                                                @php $lastMenuName = $submenu['menu_name']; @endphp
                                            @endif
                                        </td>
                        
                                        <td>{{ $submenu['submenu_name'] }}</td>
                                        <td>{{ $submenu['submenu_url'] }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" 
                                                onclick="editSubMenu({{ $submenu['submenu_id'] }}, '{{ $submenu['menu_id'] }}', '{{ $submenu['submenu_name'] }}', '{{ $submenu['submenu_url'] }}')">
                                                Edit
                                            </button>

    <button class="btn btn-danger btn-sm" 
    onclick="confirmDelete({{ $submenu['submenu_id'] }})">
    Delete
</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Sub Menu found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Edit Sub Menu Modal -->
                <div class="modal fade" id="editSubMenuModal" tabindex="-1" aria-labelledby="editSubMenuModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSubMenuModalLabel">Edit Sub Menu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('submenu_update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="submenu_id" id="edit_submenu_id">

                                    <div class="mb-3">
                                        <label for="edit_menu_id" class="form-label">Select Role</label>
                                        <select class="form-select" id="edit_menu_id" name="menu_id" required>
                                            <option value="">-- Select Role --</option>
                                            @foreach($roledata as $role)
                                                <option value="{{ $role['role_id'] }}">{{ $role['role_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_submenu_name" class="form-label">Sub Menu Name</label>
                                        <input type="text" class="form-control" id="edit_submenu_name" name="submenu_name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_submenu_url" class="form-label">URL</label>
                                        <input type="text" class="form-control" id="edit_submenu_url" name="submenu_url" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Sub Menu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Hidden Delete Form -->
<form id="deleteForm" action="{{ route('submenu_delete') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="submenu_id" id="delete_submenu_id">
</form>
<script>
function editSubMenu(id, menu_id, submenu_name, submenu_url) {
    document.getElementById('edit_submenu_id').value = id;
    document.getElementById('edit_menu_id').value = menu_id;
    document.getElementById('edit_submenu_name').value = submenu_name;
    document.getElementById('edit_submenu_url').value = submenu_url;
    new bootstrap.Modal(document.getElementById('editSubMenuModal')).show();
}
</script>
<script>
    function confirmDelete(submenu_id) {
        if (confirm("Are you sure you want to delete this Sub Menu?")) {
            document.getElementById('delete_submenu_id').value = submenu_id;
            document.getElementById('deleteForm').submit();
        }
    }
    </script>

@endsection
