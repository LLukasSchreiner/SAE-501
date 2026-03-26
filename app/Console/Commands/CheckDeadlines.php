<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckDeadlines extends Command
{
    protected $signature = 'tasks:check-deadlines';
    protected $description = 'Vérifier les échéances des tâches et envoyer des notifications';

    public function handle()
    {
        // Tâches dont l'échéance est dans moins de 24h
        $tasks = Task::where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->whereNotNull('assigned_to')
            ->whereBetween('due_date', [now(), now()->addDay()])
            ->get();

        foreach($tasks as $task) {
            // Vérifier si notification déjà envoyée
            $existingNotif = Notification::where('user_id', $task->assigned_to)
                ->where('related_id', $task->id)
                ->where('type', 'deadline_soon')
                ->where('created_at', '>', now()->subDay())
                ->exists();

            if(!$existingNotif) {
                Notification::send(
                    $task->assigned_to,
                    'deadline_soon',
                    'Échéance proche',
                    'La tâche "' . $task->title . '" arrive à échéance le ' . Carbon::parse($task->due_date)->format('d/m/Y'),
                    route('tasks.show', $task),
                    $task->id,
                    'Task'
                );
            }
        }

        $this->info('Vérification des échéances terminée !');
    }
}