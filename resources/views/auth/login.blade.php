@extends('layouts.app')

@section('title', 'Login')

@section('body')
<div class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-lg border-0 auth-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-mortarboard-fill display-4 text-primary"></i>
                <h3 class="mt-2">Faculty Login</h3>
                <p class="text-muted">Sign in to eQUIZMona</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('register') }}">Register as Faculty</a> |
                <a href="{{ route('student.index') }}">Take Quiz</a>
            </p>
        </div>
    </div>
</div>
@endsection
