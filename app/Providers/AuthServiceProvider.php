<?php

namespace App\Providers;

// 1. Importer les Modèles
use App\Models\Project;
use App\Models\Course;
use App\Models\Deadline;
use App\Models\Sprint;
use App\Models\Task; // On anticipe pour le TaskController

// 2. Importer les Policies
use App\Policies\ProjectPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DeadlinePolicy;
use App\Policies\SprintPolicy;
use App\Policies\TaskPolicy; // On anticipe pour le TaskController

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
        // 3. C'est le tableau important.
        // On dit à Laravel :
        // "Pour le modèle X, utilise la policy Y"
        
        Project::class => ProjectPolicy::class,
        Course::class => CoursePolicy::class,
        Deadline::class => DeadlinePolicy::class,
        Sprint::class => SprintPolicy::class,
        
        // On va aussi avoir besoin d'une TaskPolicy pour sécuriser le TaskController
        // On l'ajoute maintenant.
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}