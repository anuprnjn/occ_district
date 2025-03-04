@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Role List')

@section('content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4" style="margin-top:-70px;">District User List</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Display Role List -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-semibold">User List</h5>
                        <a href="{{ route('dc_user_add') }}" class="btn btn-primary">Add User</a>
                    </div>
                    
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4 table-primary">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @forelse ($dcuserdata as $index => $dcuser)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $dcuser['name'] }}</td>
                                    <td>{{ $dcuser['email'] }}</td>
                                    <td>{{ $dcuser['mobile_no'] }}</td>
                                    <td>{{ $dcuser['role_name'] }}</td> {{-- Replace with role name if needed --}}
                                    <td>{{ $dcuser['username'] }}</td>
                                    <td>
                                        <a href="{{ route('dc_user_edit', $dcuser['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No HC Users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
