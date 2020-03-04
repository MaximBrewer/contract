<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Phrase;
use App\Auction;
use App\Observers\PhraseObserver;
use App\Observers\Auction as AuctionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Phrase::observe(PhraseObserver::class);
        Auction::observe(AuctionObserver::class);
        //
    }
}
