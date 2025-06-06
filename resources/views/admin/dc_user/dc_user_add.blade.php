@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Add District User')

@section('content')
    <main class="app-main">
        <!-- Page Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Add District Court User</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dc_user_list') }}">District Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add User</li>
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
                                <h5 class="card-title">Add District Court User</h5>
                                <a href="{{ route('dc_user_list') }}" class="btn btn-primary float-end">Back</a>
                            </div>

                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <!-- User Form -->
                                <form action="{{ route('dc_user_add') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="dist_code" class="form-label">Select District</label>
                                                <select class="form-select @error('dist_code') is-invalid @enderror"
                                                    id="dist_code" name="dist_code" required>
                                                    <option value="">-- Select District --</option>
                                                    @foreach ($districtdata as $district)
                                                    @if (session('user.role_id') == 1 || session('user.dist_code') == $district['dist_code'])
                                                        <option value="{{ $district['dist_code'] }}"
                                                            {{ old('dist_code') == $district['dist_code'] ? 'selected' : '' }}>
                                                            {{ $district['dist_name'] }}
                                                        </option>
                                                     @endif   
                                                    @endforeach
                                                </select>
                                                @error('dist_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    placeholder="Enter Name" name="name" value="{{ old('name') }}"
                                                    required>
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    placeholder="Enter Email" name="email" value="{{ old('email') }}"
                                                    required>
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="mobile_no" class="form-label">Mobile No</label>
                                                <input type="text"
                                                    class="form-control @error('mobile_no') is-invalid @enderror"
                                                    id="mobile_no" placeholder="Enter Mobile No" name="mobile_no"
                                                    value="{{ old('mobile_no') }}" required>
                                                @error('mobile_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3" id="establishment-container">
                                                <label class="form-label">Establishments</label>
                                                <div id="establishments">
                                                    <!-- Establishments will be loaded dynamically -->
                                                </div>
                                                @error('establishments')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="role_id" class="form-label">Select Role</label>
                                                <select class="form-select @error('role_id') is-invalid @enderror"
                                                    id="role_id" name="role_id" required>
                                                    <option value="">-- Select Role --</option>
                                                    @foreach ($roledata as $role)
                                                        <option value="{{ $role['role_id'] }}"
                                                            {{ old('role_id') == $role['role_id'] ? 'selected' : '' }}>
                                                            {{ $role['role_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text"
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    id="username" placeholder="Enter Username" name="username"
                                                    value="{{ old('username') }}" required>
                                                @error('username')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" placeholder="Enter Password" name="password" required>
                                                @error('password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Add User</button>
                                </form>

                            </div> <!-- End Card Body -->
                        </div> <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery for AJAX Request -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#dist_code').change(function() {
                    let dist_code = $(this).val();
                    if (dist_code) {
                        $.ajax({
                            url: '{{ route('fetch_establishments') }}',
                            type: 'POST',
                            data: {
                                dist_code: dist_code,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#establishments').empty();
                                if (response.length > 0) {
                                    response.forEach(function(est) {
                                        $('#establishments').append(
                                            `<div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="est_code[]" value="${est.est_code}">
                                            <label class="form-check-label">${est.estname}</label>
                                        </div>`
                                        );
                                    });
                                } else {
                                    $('#establishments').html(
                                        '<p class="text-muted">No establishments found.</p>');
                                }
                            },
                            error: function() {
                                $('#establishments').html(
                                    '<p class="text-danger">Error fetching establishments.</p>');
                            }
                        });
                    } else {
                        $('#establishments').empty();
                    }
                });
            });
        </script>
    @endpush
@endsection
