<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'patients');
        
        if ($tab === 'workers') {
            // Get admin/staff users from database
            $users = User::where('role', 'admin')
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'no' => $index + 1,
                        'worker_name' => $user->name,
                        'email' => $user->email,
                        'role' => ucfirst($user->role),
                        'status' => 'Offline', // Can be enhanced with online tracking
                    ];
                })->toArray();
        } else {
            // Get regular users (patients) from database
            $users = User::where('role', 'user')
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'no' => $index + 1,
                        'patient_name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone_number ?? 'N/A',
                        'status' => 'Offline', // Can be enhanced with online tracking
                    ];
                })->toArray();
        }
        
        return view('admin.users.index', compact('users', 'tab'));
    }
    
    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $appointments = $user->appointments()->orderBy('appointment_date', 'desc')->take(10)->get();
        $healthRecords = $user->healthRecords()->orderBy('record_date', 'desc')->take(10)->get();
        
        return view('admin.users.show', compact('user', 'appointments', 'healthRecords'));
    }

    /**
     * Show the form for editing the user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin'
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Delete the user.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Don't allow deleting yourself
        if ($user->id === Auth::user()->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}