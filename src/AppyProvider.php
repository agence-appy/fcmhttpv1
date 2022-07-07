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
        $this->app->register('Appy\FcmHttpV1\AppyProvider');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('AppyFcmHttpV1', 'Appy\FcmHttpV1\Classes\AppyFcmHttpV1');
        $loader->alias('AppyNotification', 'Appy\FcmHttpV1\Classes\AppyNotification');
        $loader->alias('AppyGoogleHelper', 'Appy\FcmHttpV1\Helpers\AppyGoogleHelper');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '\config\appy_firebase.php' => config_path('appy_firebase.php'),
        ], 'appyfcmhttpv1');
    }
}
