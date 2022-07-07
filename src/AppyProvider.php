<?php

namespace Appy\FcmHttpV1;

use Illuminate\Support\ServiceProvider;

class AppyProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/firebase.php' => config_path('firebase.php'),
        ]);
    }
}
