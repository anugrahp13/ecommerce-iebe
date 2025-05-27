<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->log('created', $category);
    }

    public function updated(Category $category): void
    {
        $this->log('updated', $category);
    }

    public function deleted(Category $category): void
    {
        $this->log('deleted', $category);
    }

    protected function log(string $action, Category $category): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => 'categories',
            'resource_id' => $category->id,
            'changes' => $category->toArray(),
        ]);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
