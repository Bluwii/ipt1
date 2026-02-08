<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Validation and update logic here
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }
    
    /**
     * Toggle user status (activate/deactivate).
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Toggle status logic here
        
        return redirect()->back()
            ->with('success', 'User status updated!');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}