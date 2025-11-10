<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Transaction;
use App\Observers\ClientObserver;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;

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
        // Register the ClientObserver
        Client::observe(ClientObserver::class);

        // Register the TransactionObserver
        Transaction::observe(TransactionObserver::class);
    }
}
