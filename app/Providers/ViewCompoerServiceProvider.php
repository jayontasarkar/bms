<?php

namespace App\Providers;

use App\Http\ViewComposers\{
    UpozilaComposer, VendorComposer, ProductComposer, OutletComposer, 
    PendingPurchaseAndSalesComposer, BankingComposer
};
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewCompoerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['outlet.index', 'store.index', 'dashboard'], UpozilaComposer::class );

        View::composer(['store.index', 'dashboard'], VendorComposer::class );

        View::composer(['store.index', 'dashboard'], ProductComposer::class );

        View::composer(['store.index', 'dashboard'], OutletComposer::class );
        View::composer(['dashboard'], PendingPurchaseAndSalesComposer::class );
        View::composer(['banking.index', 'dashboard'], BankingComposer::class );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PendingPurchaseAndSalesComposer::class);
    }
}
