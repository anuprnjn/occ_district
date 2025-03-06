@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Edit Highcourt User')

@section('content')
<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Highcourt User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hc_user_list') }}">Highcourt Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
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
                            <div class="card-title">Edit Highcourt User</div>
                            <a href="{{ route('hc_user_list') }}" class="btn btn-primary float-end">Back</a>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- Edit User Form -->
                            <form action="{{ route('hc_user_update', $hcUser['id']) }}" method="POST">
                                @csrf
                                @method('POST') <!-- Using POST since the API only supports it -->

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $hcUser['name'] }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $hcUser['email'] }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ $hcUser['mobile_no'] }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Select Role</label>
                                    <select class="form-select" id="role_id" name="role_id" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roledata as $role)
                                            <option value="{{ $role['role_id'] }}" {{ $hcUser['role_id'] == $role['role_id'] ? 'selected' : '' }}>
                                                {{ $role['role_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $hcUser['username'] }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Update User</button>
                                <a href="{{ route('hc_user_list') }}" class="btn btn-secondary mt-3">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</main>
@endsection
