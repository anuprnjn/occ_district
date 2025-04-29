@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Sub Menu List')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Sub Menu Management</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sub Menu</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header"><div class="card-title">Add Sub Menu</div></div>
                        <div class="card-body">
                            <form action="{{ route('submenu_add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Select Menu</label>
                                    <select class="form-select" name="menu_id" required>
                                        <option value="">-- Select Menu --</option>
                                        @foreach($menudata as $menu)
                                            <option value="{{ $menu['menu_id'] }}">{{ $menu['menu_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sub Menu Name</label>
                                    <input type="text" class="form-control" name="submenu_name" placeholder="Enter Sub Menu Name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">URL</label>
                                    <input type="text" class="form-control" name="submenu_url" placeholder="Enter URL" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Sub Menu</button>
                            </form>
                        </div>
                    </div>

                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header"><div class="card-title">Sub Menu List</div></div>
                        <div class="card-body">
                            <table id="submenuTablee" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Menu Name</th>
                                        <th>Sub Menu Name</th>
                                        <th>URL</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                    @php 
                                        $lastMenuName = null;
                                        $rowspanCounts = [];
                                    @endphp
                    
                                    @foreach ($submenudata as $submenu)
                                        @php
                                            $menuName = $submenu['menu_name'];
                                            if (!isset($rowspanCounts[$menuName])) {
                                                $rowspanCounts[$menuName] = count(array_filter($submenudata, fn($item) => $item['menu_name'] === $menuName));
                                            }
                                        @endphp
                                    @endforeach
                    
                                    @foreach ($submenudata as $index => $submenu)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                    
                                            @if ($lastMenuName !== $submenu['menu_name'])
                                                <td rowspan="{{ $rowspanCounts[$submenu['menu_name']] }}">
                                                    <strong>{{ $submenu['menu_name'] }}</strong>
                                                </td>
                                                @php $lastMenuName = $submenu['menu_name']; @endphp
                                            @else
                                                <!-- Add an empty td for proper column count -->
                                                <td style="display: none;"></td>
                                            @endif
                    
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
                                    @endforeach
                    
                                    @if (empty($submenudata))
                                        <tr>
                                            <td colspan="5" class="text-center">No Sub Menu found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    


                </div>
            </div>
        </div>
    </div>
</main>

<!-- Edit Sub Menu Modal -->
<div class="modal fade" id="editSubMenuModal" tabindex="-1" aria-labelledby="editSubMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sub Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('submenu_update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submenu_id" id="edit_submenu_id">
                    <div class="mb-3">
                        <label class="form-label">Select Menu</label>
                        <select class="form-control" id="edit_menu_id" name="menu_id" required>
                            <option value="">-- Select Menu --</option>
                            @foreach($menudata as $menu)
                                <option value="{{ $menu['menu_id'] }}">{{ $menu['menu_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Menu Name</label>
                        <input type="text" class="form-control" id="edit_submenu_name" name="submenu_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" id="edit_submenu_url" name="submenu_url" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Sub Menu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="{{ route('submenu_delete') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="submenu_id" id="delete_submenu_id">
</form>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#submenuTable').DataTable();
    });
    
    function editSubMenu(id, menu_id, submenu_name, submenu_url) {
        document.getElementById('edit_submenu_id').value = id;
        document.getElementById('edit_menu_id').value = menu_id;
        document.getElementById('edit_submenu_name').value = submenu_name;
        document.getElementById('edit_submenu_url').value = submenu_url;
        new bootstrap.Modal(document.getElementById('editSubMenuModal')).show();
    }

    function confirmDelete(submenu_id) {
        if (confirm("Are you sure you want to delete this Sub Menu?")) {
            document.getElementById('delete_submenu_id').value = submenu_id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endpush
@endsection