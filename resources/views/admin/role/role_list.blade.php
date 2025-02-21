@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Role List')

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

            <!-- Display Role List -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-semibold">Role List</h5>
                        <a href="{{ route('role_add') }}" class="btn btn-primary">Add Role</a>
                    </div>
                    
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4 table-primary">
                            <tr>
                                <th>#</th>
                                <th>Role Name</th>
                                <th>Role ID</th>
                                <th>Permissions</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @forelse ($roledata as $index => $role)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $role['role_name'] }}</td>
                                    <td>{{ $role['role_id'] }}</td>
                                    <td>
                                        @if (!empty($role['permissions']))
                                            <ul>
                                                @foreach ($role['permissions'] as $permission)
                                                    <li>
                                                        {{ $permission['menu_name'] ?? 'N/A' }} | 
                                                        {{ $permission['submenu_name'] ?? 'N/A' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">No permissions assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('role_edit', ['role_id' => $role['role_id']]) }}" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Roles found</td>
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
