@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Edit Role')

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
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Role</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('role_list') }}">Role List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Edit Role</h5>
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

                            <form action="{{ route('role_update', ['role_id' => $role['role_id']]) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="role_name" class="form-label">Role Name</label>
                                    <input type="text" class="form-control" id="role_name" name="role_name" value="{{ $role['role_name'] }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Menu Permission</label>
                                    <input type="checkbox" class="form-check-input" id="checkPermissionAll">
                                    <label class="form-check-label" for="checkPermissionAll">Select All</label>
                                </div>
                                <hr>

                                @foreach ($menudata as $index => $menu)
                                    @php
                                        $allChecked = !empty($menu['submenus']) && collect($menu['submenus'])->every(fn($submenu) => in_array($submenu['submenu_id'], array_column($role['permissions'], 'submenu_id')));
                                    @endphp

                                    <div class="row mb-3">
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input menu-checkbox" id="menu{{ $index }}"
                                                       onclick="toggleSubmenuCheckboxes({{ $index }}, this)"
                                                       {{ $allChecked ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="menu{{ $index }}">{{ $menu['menu_name'] }}</label>
                                            </div>
                                        </div>

                                        <div class="col-9" id="submenu-group-{{ $index }}">
                                            @foreach ($menu['submenus'] as $submenu)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input submenu-checkbox"
                                                           name="permissions[]" id="submenu{{ $submenu['submenu_id'] }}"
                                                           value="{{ $submenu['submenu_id'] }}"
                                                           data-menu-index="{{ $index }}"
                                                           @if (in_array($submenu['submenu_id'], array_column($role['permissions'], 'submenu_id'))) checked @endif>
                                                    <label class="form-check-label" for="submenu{{ $submenu['submenu_id'] }}">{{ $submenu['submenu_name'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-success mt-3">Update Role</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    document.getElementById('checkPermissionAll').addEventListener('change', function () {
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    function toggleSubmenuCheckboxes(menuIndex, mainCheckbox) {
        document.querySelectorAll(`[data-menu-index="${menuIndex}"]`).forEach(submenu => {
            submenu.checked = mainCheckbox.checked;
        });
        updateMainCheckbox();
    }

    function updateMainCheckbox() {
        document.querySelectorAll('.menu-checkbox').forEach(menu => {
            const index = menu.id.replace('menu', '');
            const submenus = document.querySelectorAll(`[data-menu-index="${index}"]`);
            menu.checked = submenus.length > 0 && [...submenus].every(sub => sub.checked);
        });

        document.getElementById('checkPermissionAll').checked =
            document.querySelectorAll('.menu-checkbox').length > 0 &&
            [...document.querySelectorAll('.menu-checkbox')].every(menu => menu.checked);
    }

    document.querySelectorAll('.submenu-checkbox').forEach(submenu => {
        submenu.addEventListener('change', updateMainCheckbox);
    });

    updateMainCheckbox();
</script>
@endpush
@endsection
