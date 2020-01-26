<?php

namespace Bling;

use Illuminate\Support\ServiceProvider;
use Bling\Core\Client;
use Bling\Core\Config;

class BlingServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/bling.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([ $source => config_path('bling.php') ], 'config');
        }

        $this->mergeConfigFrom($source, 'bling');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BlingApiInterface::class, function () {
            $client = Client::getInstance(Config::configure(config('bling')));
            return new BlingApi($client);
        });
    }
}
