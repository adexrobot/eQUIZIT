<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FacultyProfile;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, ActivityLogService $logger)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $logger->log('login', 'User logged in: ' . Auth::user()->email);

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('faculty.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request, ActivityLogService $logger)
    {
        $data = $request->validate([
            'faculty_id' => ['required', 'string', 'max:50', 'unique:faculty_profiles,faculty_id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'department' => ['required', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'faculty',
        ]);

        FacultyProfile::create([
            'user_id' => $user->id,
            'faculty_id' => $data['faculty_id'],
            'department' => $data['department'],
            'program' => $data['program'] ?? null,
            'contact_number' => $data['contact_number'] ?? null,
        ]);

        Auth::login($user);
        $logger->log('register', 'Faculty registered: ' . $user->email);

        return redirect()->route('faculty.dashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request, ActivityLogService $logger)
    {
        if (Auth::check()) {
            $logger->log('logout', 'User logged out: ' . Auth::user()->email);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
