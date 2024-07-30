<?php

namespace App\Providers;

use App\Redis\Imp\PubSubPublisher;
use App\Redis\IPubSubPublisher;
use App\Repository\Imp\UserRepository;
use App\Repository\IUserRepo;
use App\Service\IAuthService;
use App\Service\Imp\AuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(IUserRepo::class, UserRepository::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IPubSubPublisher::class, PubSubPublisher::class);
    }
}
