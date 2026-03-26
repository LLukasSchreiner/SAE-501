<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Epic;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EpicController extends Controller
{
    use AuthorizesRequests;
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $epics = $project->epics()->withCount('tasks')->get();
        
        return view('epics.index', compact('project', 'epics'));
    }

    public function create(Project $project)
    {
        $this->authorize('view', $project);
        
        return view('epics.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('view', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $epic = $project->epics()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#10B981',
        ]);

        return redirect()->route('epics.show', $epic)->with('success', 'Epic créé !');
    }

    public function show(Epic $epic)
    {
        $epic->load(['project', 'tasks.assignedUser']);
        
        return view('epics.show', compact('epic'));
    }

    public function edit(Epic $epic)
    {
        return view('epics.edit', compact('epic'));
    }

    public function update(Request $request, Epic $epic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $epic->update($validated);

        return redirect()->route('epics.show', $epic)->with('success', 'Epic mis à jour !');
    }

    public function destroy(Epic $epic)
    {
        $project = $epic->project;
        $epic->delete();

        return redirect()->route('epics.index', $project)->with('success', 'Epic supprimé !');
    }
}