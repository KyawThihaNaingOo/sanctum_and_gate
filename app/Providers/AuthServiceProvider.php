<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('adminOnly', function () {
            return Auth::user()->role_id === 22;
        });

        Gate::define('editorOnly', function () {
            $user = Auth::user();
            return $user->isAdmin();
        });

        Gate::define('adminAndEditor', function () {
            $user = Auth::user();
            if ($user->isAdmin() || $user->isEditor()) {
                return true;
            }
            return false;
        });
        
    }
}
