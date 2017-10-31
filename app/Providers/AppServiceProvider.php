<?php

namespace App\Providers;

use App\Repository\UserRepository;
use Auth0\Login\LaravelCacheWrapper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \Auth0\Login\Contract\Auth0UserRepository::class,
            UserRepository::class
        );

        $this->app->bind(
            \Auth0\SDK\Helpers\Cache\CacheHandler::class,
            function() {
                static $cacheWrapper = null;
                if ($cacheWrapper === null) {
                    $cache = Cache::store();
                    $cacheWrapper = new LaravelCacheWrapper($cache);
                }
                return $cacheWrapper;
            });
    }
}
