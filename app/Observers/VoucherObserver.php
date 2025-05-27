<?php

namespace App\Observers;

use App\Models\Voucher;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class VoucherObserver
{
    public function created(Voucher $voucher): void
    {
        $this->log('created', $voucher);
    }

    public function updated(Voucher $voucher): void
    {
        $this->log('updated', $voucher);
    }

    public function deleted(Voucher $voucher): void
    {
        $this->log('deleted', $voucher);
    }

    protected function log(string $action, Voucher $voucher): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => 'vouchers',
            'resource_id' => $voucher->id,
            'changes' => $voucher->toArray(),
        ]);
    }


    /**
     * Handle the Voucher "restored" event.
     */
    public function restored(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "force deleted" event.
     */
    public function forceDeleted(Voucher $voucher): void
    {
        //
    }
}
