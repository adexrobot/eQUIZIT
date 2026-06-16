<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('facultyProfile');

        return view('faculty.profile.edit', compact('user'));
    }

    public function update(Request $request, ActivityLogService $logger)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'department' => ['required', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => !empty($data['password']) ? Hash::make($data['password']) : $user->password,
        ]);

        $user->facultyProfile?->update([
            'department' => $data['department'],
            'program' => $data['program'] ?? null,
            'contact_number' => $data['contact_number'] ?? null,
        ]);

        $logger->log('profile_updated', 'Updated profile');

        return back()->with('success', 'Profile updated.');
    }
}
