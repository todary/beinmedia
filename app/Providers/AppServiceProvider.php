<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        #region  User & Auth
        $this->app->bind('App\Services\IUserService', 'App\Services\Implementation\UserService');
        $this->app->bind('App\Services\IAuthService', 'App\Services\Implementation\AuthService');
        $this->app->bind('App\Adapters\IUserAdapter', 'App\Adapters\Implementation\UserAdapter');
        #endregion

        #region  User & Auth
        $this->app->bind('App\Services\IRequestService', 'App\Services\Implementation\RequestService');
        $this->app->bind('App\Adapters\IRequestAdapter', 'App\Adapters\Implementation\RequestAdapter');
        #endregion
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
