@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Menu List')

@section('content')


<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Menu Master</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menu List</li>
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
                        <div class="card-header">
                            <div class="card-title">Add New Menu</div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

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
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Menu List</div>
                        </div>
                        <div class="card-body">
                            <table id="menuTable" class="table table-bordered">
                                <thead>
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
                </div>
            </div>
        </div>
    </div>
</main>

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

@push('styles')
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        $('#menuTable').DataTable(); // Initialize DataTables
    });

    function editMenu(id, name) {
        $('#edit_menu_id').val(id);
        $('#edit_menu_name').val(name);
        $('#editMenuModal').modal('show');
    }
</script>
@endpush

@endsection