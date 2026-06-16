@extends('layouts.faculty')

@section('title', 'Profile')

@section('content')
<h2 class="mb-4">My Profile</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('faculty.profile.update') }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                <div class="col-md-4"><label class="form-label">Faculty ID</label><input type="text" class="form-control" value="{{ $user->facultyProfile->faculty_id ?? '' }}" disabled></div>
                <div class="col-md-4"><label class="form-label">Department</label><input type="text" name="department" class="form-control" value="{{ old('department', $user->facultyProfile->department ?? '') }}" required></div>
                <div class="col-md-4"><label class="form-label">Program</label><input type="text" name="program" class="form-control" value="{{ old('program', $user->facultyProfile->program ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $user->facultyProfile->contact_number ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label">New Password (optional)</label><input type="password" name="password" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" class="form-control"></div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Profile</button>
        </form>
    </div>
</div>
@endsection
