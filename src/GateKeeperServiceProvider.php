<?php

namespace Muffin\GateKeeper;

use Illuminate\Support\ServiceProvider;

class GateKeeperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gatekeeper.php' => config_path('gatekeeper.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/gatekeeper.php', 'gatekeeper'
        );
    }
}
