@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Add Fee Master')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Fee Master</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fee_master') }}">Fee Master Management</a></li>
                        <li class="breadcrumb-item active">Add New Fee Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fee Master Information</h3>
                        </div>
                        <form action="{{ route('fee_master.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('fee_type') is-invalid @enderror" 
                                                   id="fee_type" 
                                                   name="fee_type" 
                                                   value="{{ old('fee_type') }}" 
                                                   placeholder="Enter fee type (e.g., urgent_fee, per_page_fee)"
                                                   required>
                                            @error('fee_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Maximum 50 characters</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control @error('amount') is-invalid @enderror" 
                                                   id="amount" 
                                                   name="amount" 
                                                   value="{{ old('amount') }}" 
                                                   placeholder="Enter amount"
                                                   step="0.01" 
                                                   min="0"
                                                   required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Enter amount in rupees (e.g., 5.00)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Fee Master
                                </button>
                                <a href="{{ route('fee_master') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Instructions</h3>
                        </div>
                        <div class="card-body">
                            <h6>Fee Type Guidelines:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Use descriptive names</li>
                                <li><i class="fas fa-check text-success"></i> Keep it under 50 characters</li>
                                <li><i class="fas fa-check text-success"></i> Use underscore for spaces</li>
                            </ul>
                            
                            <h6 class="mt-3">Amount Guidelines:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Enter in rupees</li>
                                <li><i class="fas fa-check text-success"></i> Use decimal for paisa</li>
                                <li><i class="fas fa-check text-success"></i> Must be positive</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
document.getElementById('fee_type').addEventListener('input', function(e) {
    // Replace spaces with underscores
    this.value = this.value.replace(/\s+/g, '_');
});
</script>
@endpush
@endsection