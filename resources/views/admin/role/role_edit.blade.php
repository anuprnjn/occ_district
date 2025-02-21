@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Edit Role')

@section('content')
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Edit Role</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Edit Role Form -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold">Edit Role</h5>
                            <a href="{{ route('role_list') }}" class="btn btn-primary">Back</a>
                        </div>

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
@endsection
