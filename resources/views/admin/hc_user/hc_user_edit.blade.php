@extends('admin.layouts.app')

@section('title', 'Edit HC User')

@section('content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="card-body">
         
            <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">Edit Highcourt User</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Edit User Form -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-semibold">Edit Highcourt User</h5>
                        <a href="{{ route('hc_user_list') }}" class="btn btn-primary">Back</a>
                    </div>

                    <form action="{{ route('hc_user_update', $hcUser['id']) }}" method="POST">
                        @csrf
                        @method('POST') <!-- Use POST since the API only supports it -->
                        
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
@endsection
