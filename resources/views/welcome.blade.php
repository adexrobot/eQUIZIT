@extends('layouts.app')

@section('body')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="bi bi-mortarboard-fill"></i> eQUIZMona</a>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('student.index') }}" class="btn btn-light btn-sm">Take Quiz</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Faculty Login</a>
        </div>
    </div>
</nav>

<section class="hero-section text-white text-center py-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-3">eQUIZMona</h1>
        <p class="lead mb-4">Electronic Quiz Management and AI-Assisted Assessment Platform</p>
        <p class="mb-4">Create, manage, and evaluate quizzes with intelligent essay grading for colleges and universities.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('student.index') }}" class="btn btn-light btn-lg"><i class="bi bi-pencil-square"></i> Enter Quiz Code</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg"><i class="bi bi-person-plus"></i> Faculty Register</a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-file-earmark-text display-4 text-primary"></i>
                        <h5 class="mt-3">Quiz Creation</h5>
                        <p class="text-muted">Create quizzes manually or upload DOCX/PDF files with automatic question extraction.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-robot display-4 text-primary"></i>
                        <h5 class="mt-3">AI Essay Evaluation</h5>
                        <p class="text-muted">Intelligent grading with detailed feedback, rubric alignment, and writing pattern analysis.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-graph-up-arrow display-4 text-primary"></i>
                        <h5 class="mt-3">Analytics & Reports</h5>
                        <p class="text-muted">Track student performance with charts, question analysis, and PDF/Excel exports.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white text-center py-3">
    <small>&copy; {{ date('Y') }} eQUIZMona - Electronic Quiz Management Platform</small>
</footer>
@endsection
