<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Users can only update their own pending appointments
        return $user->id === $appointment->user_id 
            && $appointment->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Users can only cancel their own pending or confirmed appointments
        return $user->id === $appointment->user_id 
            && in_array($appointment->status, ['pending', 'confirmed']);
    }
}