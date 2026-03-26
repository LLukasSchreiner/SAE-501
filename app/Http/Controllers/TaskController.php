<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Notification;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $query = $project->tasks()->with(['assignedUser', 'epic', 'sprint']);
        
        // Filtres
        if(request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }
        
        if(request('status')) {
            $query->where('status', request('status'));
        }
        
        if(request('priority')) {
            $query->where('priority', request('priority'));
        }
        
        if(request('assigned_to')) {
            $query->where('assigned_to', request('assigned_to'));
        }
        
        if(request('epic_id')) {
            $query->where('epic_id', request('epic_id'));
        }
        
        if(request('sprint_id')) {
            $query->where('sprint_id', request('sprint_id'));
        }
        
        // Tri
        switch(request('sort', 'created_desc')) {
            case 'created_asc':
                $query->oldest();
                break;
            case 'due_date_asc':
                $query->orderBy('due_date', 'asc');
                break;
            case 'priority':
                $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END");
                break;
            default:
                $query->latest();
        }
        
        $tasks = $query->get();
        
        return view('tasks.index', compact('project', 'tasks'));
    }

    public function create(Project $project)
    {
        $this->authorize('update', $project);
        
        $epics = $project->epics;
        $sprints = $project->sprints;
        $members = $project->users;
        
        return view('tasks.create', compact('project', 'epics', 'sprints', 'members'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'epic_id' => 'nullable|exists:epics,id',
            'sprint_id' => 'nullable|exists:sprints,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,done,cancelled',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);
    
        $task = $project->tasks()->create($validated);
    
        // ✅ NOTIFICATION : Tâche assignée
        if($task->assigned_to && $task->assigned_to !== auth()->id()) {
            Notification::send(
                $task->assigned_to,
                'task_assigned',
                '📋 Nouvelle tâche assignée',
                auth()->user()->name . ' vous a assigné la tâche "' . $task->title . '"',
                route('tasks.show', $task),
                $task->id,
                'Task'
            );
        }
    
        return redirect()->route('projects.show', $project)->with('success', 'Tâche créée !');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load(['project', 'epic', 'sprint', 'assignedUser', 'comments.user', 'courses']);
        
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $project = $task->project;
        $epics = $project->epics;
        $sprints = $project->sprints;
        $members = $project->users;
        
        return view('tasks.edit', compact('task', 'project', 'epics', 'sprints', 'members'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $oldAssignedTo = $task->assigned_to; 

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'epic_id' => 'nullable|exists:epics,id',
            'sprint_id' => 'nullable|exists:sprints,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,done,cancelled',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        if($task->assigned_to && $task->assigned_to !== auth()->id()) {
            Notification::send(
                $task->assigned_to,
                'task_updated',
                '✏️ Tâche modifiée',
                auth()->user()->name . ' a modifié la tâche "' . $task->title . '"',
                route('tasks.show', $task),
                $task->id,
                'Task'
            );
        }

        if(isset($validated['assigned_to']) && $validated['assigned_to'] !== $oldAssignedTo && $validated['assigned_to'] !== auth()->id()) {
            Notification::send(
                $validated['assigned_to'],
                'task_assigned',
                '📋 Tâche assignée',
                auth()->user()->name . ' vous a assigné la tâche "' . $task->title . '"',
                route('tasks.show', $task),
                $task->id,
                'Task'
            );
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Tâche mise à jour !');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $project = $task->project;
        $task->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Tâche supprimée !');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done,cancelled'
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json(['success' => true]);
    }

    



}