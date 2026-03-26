<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SprintController extends Controller
{
    use AuthorizesRequests;
    public function index(Project $project)
    {
        $this->authorize('view', $project);

        $sprints = $project->sprints()->withCount('tasks')->with('tasks')->get();

        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project)
    {
        $this->authorize('view', $project);
        
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('view', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planned,active,completed',
        ]);

        $sprint = $project->sprints()->create($validated);

        return redirect()->route('sprints.show', $sprint)->with('success', 'Sprint créé !');
    }

    public function show(Sprint $sprint)
    {
        $this->authorize('view', $sprint->project);

        $sprint->load(['project', 'tasks.assignedUser']);
        
        return view('sprints.show', compact('sprint'));
    }

    public function edit(Sprint $sprint)
    {
        return view('sprints.edit', compact('sprint'));
    }

    public function update(Request $request, Sprint $sprint)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planned,active,completed',
        ]);

        $sprint->update($validated);

        return redirect()->route('sprints.show', $sprint)->with('success', 'Sprint mis à jour !');
    }

    public function destroy(Sprint $sprint)
    {
        $project = $sprint->project;
        $sprint->delete();

        return redirect()->route('sprints.index', $project)->with('success', 'Sprint supprimé !');
    }
}