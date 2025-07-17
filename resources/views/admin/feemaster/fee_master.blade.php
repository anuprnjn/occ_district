@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Fee Master')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Fee Master Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Fee Master Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fee Master List</h3>
                            <div class="card-tools">
                                <a href="{{ route('fee_master.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add New Fee
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($feeMasters) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Fee Type</th>
                                                <th>Amount (₹)</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($feeMasters as $feeMaster)
                                                <tr>
                                                    <td>{{ $feeMaster->fee_type }}</td>
                                                    <td>₹{{ number_format($feeMaster->amount, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('fee_master.edit', $feeMaster->fee_id) }}" 
                                                           class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No fee masters found. 
                                    <a href="{{ route('fee_master.create') }}">Click here to add the first one.</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
@endpush
@endsection