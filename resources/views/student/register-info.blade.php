@extends('layouts.app')

@section('title', 'Student Information')

@section('body')
<div class="student-wrapper min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h3 class="mb-1">{{ $quiz->title }}</h3>
                        <p class="text-muted mb-4">{{ $quiz->subject }} | Duration: {{ $quiz->duration }} minutes</p>

                        @if($errors->any())
                            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                        @endif

                        <form method="POST" action="{{ route('student.register-info') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                                <div class="col-md-6"><label class="form-label">Student ID</label><input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}" required></div>
                                <div class="col-md-4"><label class="form-label">Program</label><input type="text" name="program" class="form-control" value="{{ old('program') }}" required></div>
                                <div class="col-md-4"><label class="form-label">Year Level</label><input type="text" name="year_level" class="form-control" value="{{ old('year_level') }}" required></div>
                                <div class="col-md-4"><label class="form-label">Section</label><input type="text" name="section" class="form-control" value="{{ old('section') }}" required></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Start Examination</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
