<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use App\Observers\CategoryObserver;
use App\Observers\DiscountObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Observers\VoucherObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);
        User::observe(UserObserver::class);
        Voucher::observe(VoucherObserver::class);
        Product::observe(ProductObserver::class);
        Discount::observe(DiscountObserver::class);

    }
}
