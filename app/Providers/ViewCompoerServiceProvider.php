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
        View::composer(['store.index', 'dashboard'], OutletComposer::class );
        View::composer(['dashboard'], PendingPurchaseAndSalesComposer::class );
        View::composer(['banking.index', 'dashboard'], BankingComposer::class );
        View::composer([
            'outlet.index', 'outlet.edit', 'store.index', 'dashboard', 'collections.edit', 'store.report.index'
        ], UpozilaComposer::class );
        View::composer([
            'store.index', 'dashboard', 'outlet.edit', 'outlet.show', 'readysale.edit', 'store.report.index',
            'collections.index', 'collections.edit', 'sales.index', 'layouts.backend.common._sidebarSearch'
        ], VendorComposer::class );

        View::composer([
            'store.index', 'dashboard', 'outlet.show', 'sales.show', 'purchases.show', 'readysale.edit'
        ], ProductComposer::class );
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
