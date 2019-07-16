<?php
namespace UrApi\Utils;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        * Publish the urapi.php config file
        */
        $this->publishes([
            __DIR__ . '/config/urapi.php' => config_path('urapi.php'),
        ]);
    }
}
