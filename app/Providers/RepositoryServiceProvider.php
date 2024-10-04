<?php

namespace App\Providers;

use App\Interfaces\AuthenticationInterface;
use App\Interfaces\TaskInterface;
use App\Repositories\AuthenticationRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationInterface::class, AuthenticationRepository::class);
        $this->app->bind(TaskInterface::class, TaskRepository::class);
    }
}
