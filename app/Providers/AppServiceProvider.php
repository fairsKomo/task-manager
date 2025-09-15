<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


use App\Models\Project;
use App\Models\Task;
use App\Models\User;


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
        Gate::define('view-project', function (User $user, Project $project) {
            return $project->user_id === $user->id;
        });

        Gate::define('update-project', function ($user, Project $project) {
            return $user->id === $project->user_id;
        });

        Gate::define('create-task', function (User $user, Project $project) {
        return $user->id === $project->user_id;
        });

        Gate::define('view-task', function ($user, $project) {
            return $user->id === $project->user_id;
        });

    }
}
