<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSuperAdmin',function ($user){
            return $user->type === 'super';
        });
        Gate::define('isAdmin',function ($user){
            return $user->type === 'admin';
        });
        Gate::define('isEmployee',function ($user){
            return $user->type === 'employee';
        });
        Gate::define('isOrg',function ($user){
            return $user->type === 'org';
        });
        Gate::define('isTeam',function ($user){
            return $user->type === 'team';
        });
        Gate::define('isProfessional',function ($user){
            return $user->type === 'pro';
        });
        Gate::define('isUser',function ($user){
            return $user->type === 'user';
        });
        Gate::define('isStudent',function ($user){
            return $user->type === 'student';
        });
        Gate::define('isGroup',function ($user){
            return $user->type === 'group';
        });

        Passport::routes();
    }
}
