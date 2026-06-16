@extends('layouts.app')

@section('title', 'Take Quiz')

@section('body')
<div class="student-wrapper min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-key-fill display-3 text-primary"></i>
                        <h2 class="mt-3">Enter Quiz Code</h2>
                        <p class="text-muted">No account needed. Enter the code provided by your instructor.</p>

                        @if($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('student.verify-code') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="quiz_code" class="form-control form-control-lg text-center text-uppercase" placeholder="e.g. QUIZ2026CS101" value="{{ old('quiz_code') }}" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">Continue</button>
                        </form>
                        <a href="{{ route('home') }}" class="d-block mt-3">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
