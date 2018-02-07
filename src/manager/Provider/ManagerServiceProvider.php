<?php
namespace ImageManager\Provider;

use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migration');
        
        $this->loadRoutesFrom(__DIR__ . '/../routes/route.php');
        
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'imagemanager');


        $this->publishes([
            __DIR__.'/../public/css' => public_path('imagemanager/css'),
        ], 'imagemanager');

        $this->publishes([
            __DIR__.'/../public/js' => public_path('imagemanager/js'),  
        ], 'imagemanager');

    }

    public function register()
    {

    }

}