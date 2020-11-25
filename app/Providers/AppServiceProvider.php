<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Bet;
use App\Attachment;
use App\Phrase;
use App\Auction;
use App\Logistic;
use App\Contract;
use App\Observers\Bet as BetObserver;
use App\Observers\Attachment as AttachmentObserver;
use App\Observers\PhraseObserver;
use App\Observers\Auction as AuctionObserver;
use App\Observers\Logistic as LogisticObserver;
use App\Observers\Contract as ContractObserver;
use Jenssegers\Date\Date;

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
        Attachment::observe(AttachmentObserver::class);
        Bet::observe(BetObserver::class);
        Logistic::observe(LogisticObserver::class);
        Contract::observe(ContractObserver::class);
        Date::setlocale(config('app.locale'));
    }
}
