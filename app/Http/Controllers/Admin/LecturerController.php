<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LecturerController extends Controller
{
    public function index(): View
    {
        $lecturers = User::query()
            ->where('role', User::ROLE_LECTURER)
            ->orderBy('name')
            ->paginate(15);

        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => User::ROLE_LECTURER,
            'email_verified_at' => now(),
        ]);

        return back()->with('status', __('Lecturer account created.'));
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === User::ROLE_LECTURER, 404);

        $user->delete();

        return back()->with('status', __('Lecturer removed.'));
    }
}
