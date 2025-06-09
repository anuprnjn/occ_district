@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || District User List')

@section('content')
    <main class="app-main">
        <!-- Page Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">District User List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">District Users</li>
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
                                <div class="card-title">District User List</div>
                                <a href="{{ route('dc_user_add') }}" class="btn btn-primary float-end">Add User</a>
                            </div>

                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <div class="table-responsive">
                                    <table id="myTable" class="table table-bordered text-nowrap mb-0 align-middle">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Role</th>
                                                <th>Username</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($dcuserdata as $index => $dcuser)
                                                @if (session('user.role_id') == 1 || session('user.dist_code') == $dcuser['dist_code'])
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $dcuser['name'] }}</td>
                                                        <td>{{ $dcuser['email'] }}</td>
                                                        <td>{{ $dcuser['mobile_no'] }}</td>
                                                        <td>{{ $dcuser['role_name'] }}</td>
                                                        <td>{{ $dcuser['username'] }}</td>
                                                        <td>
                                                            <a href="{{ route('dc_user_edit', $dcuser['id']) }}"
                                                                class="btn btn-warning btn-sm">Edit</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                              
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div> <!-- End Table Responsive -->
                            </div> <!-- End Card Body -->
                        </div> <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>
    </main>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable(); // Initialize DataTables
            });
        </script>
    @endpush
@endsection
