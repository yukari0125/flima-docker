<?php

namespace App\Providers;

use App\Auth\FortifyCreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse as VerifyEmailViewResponseContract;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse as RequestPasswordResetLinkViewResponseContract;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse as ResetPasswordViewResponseContract;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse as ConfirmPasswordViewResponseContract;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\VerifyEmailResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $features = config('fortify.features', []);
        if (! in_array(Features::emailVerification(), $features, true)) {
            $features[] = Features::emailVerification();
            config()->set('fortify.features', $features);
        }

        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(VerifyEmailResponseContract::class, VerifyEmailResponse::class);
        $this->app->singleton(VerifyEmailViewResponseContract::class, function () {
            return new \Laravel\Fortify\Http\Responses\SimpleViewResponse('auth.verify-email');
        });
        $this->app->singleton(RequestPasswordResetLinkViewResponseContract::class, function () {
            return new \Laravel\Fortify\Http\Responses\SimpleViewResponse('auth.forgot-password');
        });
        $this->app->singleton(ResetPasswordViewResponseContract::class, function () {
            return new \Laravel\Fortify\Http\Responses\SimpleViewResponse('auth.reset-password');
        });
        $this->app->singleton(ConfirmPasswordViewResponseContract::class, function () {
            return new \Laravel\Fortify\Http\Responses\SimpleViewResponse('auth.confirm-password');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            Fortify::createUsersUsing(FortifyCreateNewUser::class);
        });
    }
}
