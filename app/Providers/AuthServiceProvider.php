<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('administrator') ? true : null;
        });

        Gate::after(function ($user, $ability) {
            return $user->hasRole('administrator'); // note this returns boolean
        });

        $this->checkAccess();
    }

    private function checkAccess()
    {
        Gate::define('client-project-view', function (User $user, Project $project) {
            if ($user->hasRole('client')) {
                return $user->id === $project->client->user_id ?? '';
            }

            return true;
        });
    }
}
