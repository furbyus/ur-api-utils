<?php
namespace UrApi\Utils;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/src/config/urapi.php' => config_path('urapi.php'),
        ]);
    }
}
