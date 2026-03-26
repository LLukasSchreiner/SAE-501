<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Sprint;
use App\Models\Epic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Projets récents (4 derniers)
        $recentProjects = $user->projects()->latest()->take(4)->get();
        
        // Tâches avec échéance proche (7 prochains jours)
        $upcomingDeadlines = $user->assignedTasks()
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->with(['project', 'epic'])
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        // Tâches en cours
        $tasksInProgress = $user->assignedTasks()
            ->where('status', 'in_progress')
            ->with(['project', 'epic'])
            ->latest()
            ->take(5)
            ->get();
        
        // Statistiques globales
        $stats = [
            'total_projects' => $user->projects()->count(),
            'total_tasks' => Task::whereHas('project', function($q) use ($user) {
                $q->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->count(),
            'tasks_completed' => Task::whereHas('project', function($q) use ($user) {
                $q->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->where('status', 'done')->count(),
            'tasks_in_progress' => Task::whereHas('project', function($q) use ($user) {
                $q->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->where('status', 'in_progress')->count(),
            'tasks_todo' => Task::whereHas('project', function($q) use ($user) {
                $q->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->where('status', 'todo')->count(),
            'overdue_tasks' => Task::whereHas('project', function($q) use ($user) {
                $q->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->where('status', '!=', 'done')
              ->whereNotNull('due_date')
              ->where('due_date', '<', now())
              ->count(),
        ];
        
        return view('dashboard', compact('recentProjects', 'upcomingDeadlines', 'tasksInProgress', 'stats'));
    }
}