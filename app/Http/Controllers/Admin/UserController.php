<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'patients');

        if ($tab === 'workers') {
            $users = User::where('role', 'admin')
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'no'          => $index + 1,
                        'id'          => $user->id,
                        'worker_name' => $user->name,
                        'email'       => $user->email,
                        'role'        => ucfirst($user->role),
                        'status'      => 'Active',
                    ];
                })->toArray();
        } else {
            $users = User::where('role', 'user')
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'no'           => $index + 1,
                        'id'           => $user->id,
                        'patient_name' => $user->name,
                        'email'        => $user->email,
                        'phone'        => $user->phone_number ?? 'N/A',
                        'status'       => 'Active',
                    ];
                })->toArray();
        }

        return view('admin.users.index', compact('users', 'tab'));
    }

    public function show(User $user): View
    {
        $appointments  = $user->appointments()->orderBy('appointment_date', 'desc')->take(10)->get();
        $healthRecords = $user->healthRecords()->orderBy('record_date', 'desc')->take(10)->get();

        return view('admin.users.show', compact('user', 'appointments', 'healthRecords'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'role'                  => 'required|in:user,admin',
            'phone_number'          => 'nullable|string|max:30',
            'gender'                => 'nullable|in:male,female,other',
            'birthdate'             => 'nullable|date',
            'purok_no'              => 'nullable|string|max:20',
            'password'              => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string',
        ]);

        // Handle password separately
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        // Only include columns that actually exist in the DB
        // This prevents crashes if a migration hasn't been run yet
        $safeData = [];
        foreach ($validated as $column => $value) {
            if (Schema::hasColumn('users', $column)) {
                $safeData[$column] = $value;
            }
        }

        $user->update($safeData);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        return redirect()->back()->with('info', 'Status toggle not yet implemented.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}