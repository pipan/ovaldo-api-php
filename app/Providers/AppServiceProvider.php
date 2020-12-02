<?php

namespace App\Providers;

use App\Http\Bearer\Bearer;
use App\Http\Bearer\BearerProxy;
use App\Http\Bearer\SimpleBearer;
use App\Integration\Zomato\ZomatoApi;
use Illuminate\Support\ServiceProvider;
use Lib\Time\OffsetTimeAdjuster;
use Lib\Time\TimeAdjuster;
use Lib\Time\TimeAdjusterProxy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BearerProxy::class, function ($app) {
            return new BearerProxy(new SimpleBearer(null));
        });
        $this->app->bind(Bearer::class, BearerProxy::class);

        $this->app->singleton(TimeAdjusterProxy::class, function ($app) {
            return new TimeAdjusterProxy(new OffsetTimeAdjuster(0));
        });
        $this->app->bind(TimeAdjuster::class, TimeAdjusterProxy::class);

        $this->app->singleton(ZomatoApi::class, function () {
            return new ZomatoApi(['key' => env('ZOMATO_API_KEY')]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
