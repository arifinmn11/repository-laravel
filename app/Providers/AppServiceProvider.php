<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\UserIRepository::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Repositories\BranchIRepository::class, \App\Repositories\BranchRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        //     return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        // });
    }
}
