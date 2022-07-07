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
            __DIR__.'\config\appy_firebase.php' => config_path('appy_firebase.php'),
        ], 'appyfcmhttpv1');
    }
}
