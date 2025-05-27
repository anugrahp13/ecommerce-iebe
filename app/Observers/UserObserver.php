<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function created(User $user): void
    {
        $this->log('created', $user);
    }

    public function updated(User $user): void
    {
        $this->log('updated', $user);
    }

    public function deleted(User $user): void
    {
        $this->log('deleted', $user);
    }

    protected function log(string $action, User $user): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => 'users',
            'resource_id' => $user->id,
            'changes' => $user->toArray(),
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
