@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Role List')

@section('content')
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Highcourt User Detail Add</h5>

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
                            <h5 class="card-title fw-semibold">Add Highcourt User</h5>
                            <a href="{{ route('hc_user_list') }}" class="btn btn-primary">Back</a>
                        </div>

                        <form action="{{ route('hc_user_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label"> Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label"> Email</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label"> Mobile No</label>
                                <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile No" name="mobile_no" required>
                            </div>

                            <div class="mb-3">
                                <label for="menu_id" class="form-label">Select Role</label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach($roledata as $role)
                                        <option value="{{ $role['role_id'] }}">{{ $role['role_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label"> Username</label>
                                <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" placeholder="Enter password" name="password" required>
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Add User</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
