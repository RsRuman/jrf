<?php

namespace App\Providers;

use App\Interfaces\AuthenticationInterface;
use App\Repositories\AuthenticationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationInterface::class, AuthenticationRepository::class);
    }
}
