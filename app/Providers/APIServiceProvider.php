<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\API\Request\Dispatcher;

class APIServiceProvider extends ServiceProvider
{
    protected $defer = True;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bind('Api', function($app) {
            return new Dispatcher;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        return ['Api'];
    }
}
