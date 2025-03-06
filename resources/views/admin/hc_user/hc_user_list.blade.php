@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Highcourt User List')

@section('content')
<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0">Highcourt User List</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Highcourt Users</li>
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
                <div class="card-title">Highcourt User List</div>
                <a href="{{ route('hc_user_add') }}" class="btn btn-primary float-end">Add User</a>
              </div>
              <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <table id="myTable" class="table table-bordered">
                    <thead>
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
                        @forelse ($hcuserdata as $index => $hcuser)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $hcuser['name'] }}</td>
                                <td>{{ $hcuser['email'] }}</td>
                                <td>{{ $hcuser['mobile_no'] }}</td>
                                <td>{{ $hcuser['role_name'] }}</td>
                                <td>{{ $hcuser['username'] }}</td>
                                <td>
                                    <a href="{{ route('hc_user_edit', $hcuser['id']) }}" class="btn btn-warning btn-sm">Edit</a>
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
    </div>
</main>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable(); // Initialize DataTables
        });
    </script>
@endpush
@endsection
