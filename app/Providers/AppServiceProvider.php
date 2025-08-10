<?php

namespace App\Providers;

use App\Storage\Contracts\FileStorageDriver;
use App\Storage\FileStorageFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FileStorageDriver::class, function () {
        return FileStorageFactory::make();
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}