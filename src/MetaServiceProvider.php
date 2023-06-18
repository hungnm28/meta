<?php

namespace Hungnm28\Meta;
use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider
{
    protected $defer = false;
    public function register()
    {
        parent::register();
        $this->app->bind('meta', function($app) {
            return new Meta;
        });
    }

    public function provides()
    {
        return array();
    }
}
