<?php

namespace App\Observers;

use App\Models\Discount;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DiscountObserver
{
    public function created(Discount $discount): void
    {
        $this->log('created', $discount);
    }

    public function updated(Discount $discount): void
    {
        $this->log('updated', $discount);
    }

    public function deleted(Discount $discount): void
    {
        $this->log('deleted', $discount);
    }

    protected function log(string $action, Discount $discount): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => 'discount',
            'resource_id' => $discount->id,
            'changes' => $discount->toArray(),
        ]);
    }
    /**
     * Handle the Discount "restored" event.
     */
    public function restored(Discount $discount): void
    {
        //
    }

    /**
     * Handle the Discount "force deleted" event.
     */
    public function forceDeleted(Discount $discount): void
    {
        //
    }
}
