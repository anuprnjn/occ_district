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
                <div class="col-sm-6"><h3 class="mb-0">Role And Permission</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Role Management</li>
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
                                <h5 class="card-title fw-semibold">Add Role</h5>
                                <a href="{{ route('role_list') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('role_add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Role Name</label>
                                    <input type="text" class="form-control" name="role_name" placeholder="Enter Role Name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Menu Permission</label>
                                    <input type="checkbox" class="form-check-input" id="checkPermissionAll">
                                    <label class="form-check-label" for="checkPermissionAll">All</label>
                                </div>

                                <hr>

                                @foreach ($menudata as $index => $menu)
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="menu{{ $index }}" onclick="checkPermissionByGroup('role-{{ $index }}-management-checkbox', this)">
                                            <label class="form-check-label" for="menu{{ $index }}">{{ $menu['menu_name'] }}</label>
                                        </div>
                                    </div>
                                    <div class="col-9 role-{{ $index }}-management-checkbox">
                                        @foreach ($menu['submenus'] as $submenu)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="permissions[]" id="submenu{{ $submenu['submenu_id'] }}" value="{{ $submenu['submenu_id'] }}">
                                                <label class="form-check-label" for="submenu{{ $submenu['submenu_id'] }}">{{ $submenu['submenu_name'] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary mt-3">Add Role</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('checkPermissionAll').addEventListener('change', function () {
        document.querySelectorAll('.form-check-input').forEach(checkbox => checkbox.checked = this.checked);
    });

    function checkPermissionByGroup(className, mainCheckbox) {
        document.querySelectorAll('.' + className + ' input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = mainCheckbox.checked;
        });
    }
</script>

@push('styles')
@endpush

@push('scripts')
@endpush

@endsection
