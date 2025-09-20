<?php

namespace App\Providers;

use App\Repositories\Contracts\CreditTransactionRepositoryInterface;
use App\Repositories\Contracts\InvestigationRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\CreditTransactionRepository;
use App\Repositories\InvestigationRepository;
use App\Repositories\UserRepository;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CreditTransactionRepositoryInterface::class, CreditTransactionRepository::class);
        $this->app->bind(InvestigationRepositoryInterface::class, InvestigationRepository::class);
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