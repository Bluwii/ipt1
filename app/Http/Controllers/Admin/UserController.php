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
        $tab = $request->input('tab', 'patients'); // 'patients' or 'workers'
        
        if ($tab === 'workers') {
            // Static workers data
            $users = [
                [
                    'no' => 1,
                    'worker_name' => 'Vina Noguera',
                    'email' => 'noguera@health.com',
                    'role' => 'BHW',
                    'status' => 'Online',
                ],
                [
                    'no' => 2,
                    'worker_name' => 'Shelaica Lapis',
                    'email' => 'lapis@health.com',
                    'role' => 'BHW',
                    'status' => 'Offline',
                ],
                [
                    'no' => 3,
                    'worker_name' => 'Marilyn Canda',
                    'email' => 'marilyn@health.com',
                    'role' => 'Secretary',
                    'status' => 'Offline',
                ],
                [
                    'no' => 4,
                    'worker_name' => 'Swadiksik Janet',
                    'email' => 'swadiksik@health.com',
                    'role' => 'BHW',
                    'status' => 'Offline',
                ],
                [
                    'no' => 5,
                    'worker_name' => 'Jennelyn Caruncho',
                    'email' => 'caruncho@health.com',
                    'role' => 'Secretary',
                    'status' => 'Offline',
                ],
                [
                    'no' => 6,
                    'worker_name' => 'Betty Tenado',
                    'email' => 'betty@health.com',
                    'role' => 'Midwife',
                    'status' => 'Online',
                ],
                [
                    'no' => 7,
                    'worker_name' => 'Manuel Rene Luis',
                    'email' => 'manuel@health.com',
                    'role' => 'Doctor',
                    'status' => 'Offline',
                ],
            ];
        } else {
            // Static patients data
            $users = [
                [
                    'no' => 1,
                    'patient_name' => 'Debz De Ocampo',
                    'email' => 'DebzDeOcampo@email.com',
                    'phone' => '09234******',
                    'status' => 'Online',
                ],
                [
                    'no' => 2,
                    'patient_name' => 'Theo Sandoval',
                    'email' => 'Theo68@gmail.com',
                    'phone' => '09534******',
                    'status' => 'Offline',
                ],
                [
                    'no' => 3,
                    'patient_name' => 'Zia Vasquez',
                    'email' => 'ziavasquez@gmail.com',
                    'phone' => '09042******',
                    'status' => 'Offline',
                ],
                [
                    'no' => 4,
                    'patient_name' => 'Lechelle Lozano',
                    'email' => 'lechelleabcd@yahoo.com',
                    'phone' => '09563******',
                    'status' => 'Online',
                ],
                [
                    'no' => 5,
                    'patient_name' => 'Jane Ann Santisteban',
                    'email' => 'JaneAnn876@email.com',
                    'phone' => '09889******',
                    'status' => 'Offline',
                ],
                [
                    'no' => 6,
                    'patient_name' => 'Trisha Jean Galiza',
                    'email' => 'trishagaliza@gmail.com',
                    'phone' => '09021******',
                    'status' => 'Online',
                ],
            ];
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