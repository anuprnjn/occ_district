@extends('admin.layouts.app')

@section('title', 'Unauthorized Access')

@section('content')
<div class="container text-center mt-5">
    <h1 class="text-danger">403 - Unauthorized Access</h1>
    <p>You do not have permission to access this page.</p>
    <a href="{{ route('index') }}" class="btn btn-primary mt-3">Go to Dashboard</a>
</div>
@endsection
