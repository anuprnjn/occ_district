@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Edit Highcourt User')

@section('content')
<main class="app-main">
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

                            <form action="{{ route('hc_user_update', $hcUser['id']) }}" method="POST">
                                @csrf
                                @method('POST')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name', $hcUser['name']) }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email', $hcUser['email']) }}" required>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="text" class="form-control @error('mobile_no') is-invalid @enderror"
                                           id="mobile_no" name="mobile_no"
                                           value="{{ old('mobile_no', $hcUser['mobile_no']) }}" required>
                                    @error('mobile_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Select Role</label>
                                    <select class="form-select @error('role_id') is-invalid @enderror"
                                            id="role_id" name="role_id" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roledata as $role)
                                        @if (session('user.role_id') != 1 && $role['role_id'] == 1)
                                                @continue
                                            @endif
                                            <option value="{{ $role['role_id'] }}"
                                                {{ old('role_id', $hcUser['role_id']) == $role['role_id'] ? 'selected' : '' }}>
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
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                           id="username" name="username"
                                           value="{{ old('username', $hcUser['username']) }}" required>
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
