@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Add Highcourt User')

@section('content')
<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add Highcourt User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hc_user_list') }}">Highcourt Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add User</li>
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
                            <div class="card-title">Add Highcourt User</div>
                            <a href="{{ route('hc_user_list') }}" class="btn btn-secondary float-end">Back</a>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- Add User Form -->
                            <form action="{{ route('hc_user_add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile No" name="mobile_no" required>
                                </div>

                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Select Role</label>
                                    <select class="form-select" id="role_id" name="role_id" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roledata as $role)
                                            <option value="{{ $role['role_id'] }}">{{ $role['role_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Add User</button>
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
