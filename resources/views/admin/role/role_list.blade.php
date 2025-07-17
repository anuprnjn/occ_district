@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Role List')

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
                <div class="col-sm-6"><h3 class="mb-0">Role and Permission</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Role List</li>
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title fw-semibold">Role List</h5>
                                <a href="{{ route('role_add') }}" class="btn btn-primary"><i class="bi bi-plus"></i>Add Role</a>
                            </div>
                        </div>
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
                                                <a href="{{ route('role_edit', ['role_id' => encrypt($role['role_id'])]) }}" 
                                                class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Empty state content --}}
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
