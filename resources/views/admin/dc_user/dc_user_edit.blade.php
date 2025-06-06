@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Edit District User')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit District Court User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dc_user_list') }}">District Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">Edit District Court User</div>
                            <a href="{{ route('dc_user_list') }}" class="btn btn-primary float-end">Back</a>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('dc_user_update', $dcUser['id']) }}" method="POST">
                                @csrf
                                @method('POST')
                                
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Select District</label>
                                            <select class="form-select" id="dist_code" name="dist_code" required>
                                                <option value="">-- Select District --</option>
                                                @foreach($districtdata as $district)
                                                 @if (session('user.role_id') == 1 || session('user.dist_code') == $district['dist_code'])
                                                    <option value="{{ $district['dist_code'] }}" {{ $dcUser['dist_code'] == $district['dist_code'] ? 'selected' : '' }}>
                                                        {{ $district['dist_name'] }}
                                                    </option>
                                                  @endif  
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $dcUser['name'] }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $dcUser['email'] }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Mobile No</label>
                                            <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ $dcUser['mobile_no'] }}" required>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Select Role</label>
                                            <select class="form-select" id="role_id" name="role_id" required>
                                                <option value="">-- Select Role --</option>
                                                @foreach($roledata as $role)
                                                    <option value="{{ $role['role_id'] }}" {{ $dcUser['role_id'] == $role['role_id'] ? 'selected' : '' }}>
                                                        {{ $role['role_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="{{ $dcUser['username'] }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Establishments</label>
                                            <div id="establishments">
                                                @foreach($establishments as $est)
                                                    <div class="form-check">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="checkbox" 
                                                            name="est_code[]" 
                                                            value="{{ $est['est_code'] }}" 
                                                            id="est_{{ $est['est_code'] }}"
                                                            {{ in_array($est['est_code'], $selectedEstCodes) ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="est_{{ $est['est_code'] }}">
                                                            {{ $est['estname'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mt-3 me-2">Update User</button>
                                    <a href="{{ route('dc_user_list') }}" class="btn btn-secondary mt-3">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
