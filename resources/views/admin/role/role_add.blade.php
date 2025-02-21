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

                <!-- Display Role Form -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold">Add Role</h5>
                            <a href="{{ route('role_list') }}" class="btn btn-primary">Back</a>
                        </div>

                        <form action="{{ route('role_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="role_name" placeholder="Enter Role Name" name="role_name" required>
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
@endsection
