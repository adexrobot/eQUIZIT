@extends('layouts.app')

@section('title', 'Register')

@section('body')
<div class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="card shadow-lg border-0 auth-card-wide">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill display-4 text-primary"></i>
                <h3 class="mt-2">Faculty Registration</h3>
            </div>

            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Faculty ID</label>
                        <input type="text" name="faculty_id" class="form-control" value="{{ old('faculty_id') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Program</label>
                        <input type="text" name="program" class="form-control" value="{{ old('program') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">Register</button>
            </form>
            <p class="text-center mt-3 mb-0"><a href="{{ route('login') }}">Already have an account? Login</a></p>
        </div>
    </div>
</div>
@endsection
