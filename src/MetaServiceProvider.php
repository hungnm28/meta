<?php
namespace Hungnm28\Meta;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MetaServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app->bind('meta', function() {
            return new Meta();
        });
        $loader = AliasLoader::getInstance();
        $loader->alias("Meta","Hungnm28\Meta\Facades\Meta");
    }
}
