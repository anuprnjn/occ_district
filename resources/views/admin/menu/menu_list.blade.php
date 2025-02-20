@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Menu List')

@section('content')

    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Menu Master</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('menu_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="menu_name" class="form-label">Menu Name</label>
                                <input type="text" class="form-control" id="menu_name" placeholder="Enter Menu Name" name="menu_name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Menu</button>
                        </form>
                    </div>
                </div>

                <!-- Display Menu List -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Menu List</h5>

                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th>#</th>
                                    <th>Menu Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                @forelse ($menudata as $index => $menu)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $menu['menu_name'] }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editMenu({{ $menu['menu_id'] }}, '{{ $menu['menu_name'] }}')">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No Menu found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Edit Menu Modal -->
                <div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('menu_update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_id" id="edit_menu_id">
                                    <div class="mb-3">
                                        <label for="edit_menu_name" class="form-label">Menu Name</label>
                                        <input type="text" class="form-control" id="edit_menu_name" name="menu_name" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Menu</button>
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
function editMenu(id, name) {
    document.getElementById('edit_menu_id').value = id;
    document.getElementById('edit_menu_name').value = name;
    new bootstrap.Modal(document.getElementById('editMenuModal')).show();
}
</script>

@endsection
