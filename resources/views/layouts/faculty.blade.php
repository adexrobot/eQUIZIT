@extends('layouts.app')

@section('body')
<div class="d-flex" id="wrapper">
    <div class="sidebar bg-dark text-white" id="sidebar">
        <div class="sidebar-header p-3 border-bottom border-secondary">
            <h5 class="mb-0"><i class="bi bi-mortarboard-fill text-primary"></i> eQUIZMona</h5>
            <small class="text-muted">Faculty Portal</small>
        </div>
        <nav class="nav flex-column p-2">
            <a href="{{ route('faculty.dashboard') }}" class="nav-link text-white {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('faculty.quizzes.index') }}" class="nav-link text-white {{ request()->routeIs('faculty.quizzes.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> My Quizzes
            </a>
            <a href="{{ route('faculty.quizzes.create') }}" class="nav-link text-white">
                <i class="bi bi-plus-circle"></i> Create Quiz
            </a>
            <a href="{{ route('faculty.analytics.index') }}" class="nav-link text-white {{ request()->routeIs('faculty.analytics.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Analytics
            </a>
            <a href="{{ route('faculty.profile.edit') }}" class="nav-link text-white {{ request()->routeIs('faculty.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                <i class="bi bi-shield-lock"></i> Admin Panel
            </a>
            @endif
        </nav>
    </div>

    <div class="flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <span class="navbar-text ms-auto me-3">{{ auth()->user()->name }}</span>
            <button class="btn btn-sm btn-outline-secondary me-2" id="darkModeToggle"><i class="bi bi-moon-stars"></i></button>
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
            </form>
        </nav>

        <main class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@endsection
