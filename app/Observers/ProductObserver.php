<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function created(Product $product): void
    {
        $this->log('created', $product);
    }

    public function updated(Product $product): void
    {
        $this->log('updated', $product);
    }

    public function deleted(Product $product): void
    {
        $this->log('deleted', $product);
    }

    protected function log(string $action, Product $product): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => 'products',
            'resource_id' => $product->id,
            'changes' => $product->toArray(),
        ]);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
