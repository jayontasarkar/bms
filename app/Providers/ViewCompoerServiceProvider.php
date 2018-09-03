<?php

namespace App\Providers;

use App\Http\ViewComposers\{
    UpozilaComposer, VendorComposer, ProductComposer, OutletComposer
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
        View::composer(['outlet.index', 'store.index'], UpozilaComposer::class );

        View::composer(['store.index'], VendorComposer::class );

        View::composer(['store.index'], ProductComposer::class );

        View::composer(['store.index'], OutletComposer::class );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
