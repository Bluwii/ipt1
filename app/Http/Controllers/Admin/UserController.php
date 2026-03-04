<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                        'is_active'   => $user->is_active ?? true,
                        'status'      => ($user->is_active ?? true) ? 'Active' : 'Inactive',
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
                        'is_active'    => $user->is_active ?? true,
                        'status'       => ($user->is_active ?? true) ? 'Active' : 'Inactive',
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

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

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

    /**
     * Create a new worker/admin account (called by "Add Worker" modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->route('admin.users.index', ['tab' => 'workers'])
            ->with('success', 'Worker account created successfully!');
    }

    /**
     * Toggle is_active on a user account.
     * Requires migration: add_is_active_to_users_table
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot change your own account status.');
        }

        if (Schema::hasColumn('users', 'is_active')) {
            $newStatus = ! ($user->is_active ?? true);
            $user->update(['is_active' => $newStatus]);
            $label = $newStatus ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Account {$label} successfully.");
        }

        return redirect()->back()
            ->with('info', 'Run php artisan migrate to enable activate/deactivate.');
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