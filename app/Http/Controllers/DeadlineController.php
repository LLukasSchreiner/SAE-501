<?php

namespace App\Http\Controllers;

use App\Models\Deadline;
use App\Models\Project;
use Illuminate\Http\Request;

class DeadlineController extends Controller
{
    public function index()
    {
        $deadlines = auth()->user()->deadlines()->with('project')->orderBy('date')->get();
        
        return view('deadlines.index', compact('deadlines'));
    }

    public function create()
    {
        $projects = auth()->user()->projects;
        
        return view('deadlines.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'type' => 'required|in:exam,project,assignment,other',
        ]);
    
        $validated['user_id'] = auth()->id();
    
        $deadline = \App\Models\Deadline::create($validated);
    
        return redirect()->route('deadlines.index')->with('success', 'Deadline créée !');
    }

    public function show(Deadline $deadline)
    {
        $this->authorize('view', $deadline);
        
        return view('deadlines.show', compact('deadline'));
    }

    public function edit(Deadline $deadline)
    {
        $this->authorize('update', $deadline);
        
        $projects = auth()->user()->projects;
        
        return view('deadlines.edit', compact('deadline', 'projects'));
    }

    public function update(Request $request, Deadline $deadline)
    {
        $this->authorize('update', $deadline);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:exam,assignment,presentation',
            'date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $deadline->update($validated);

        return redirect()->route('deadlines.show', $deadline)->with('success', 'Deadline mise à jour !');
    }

    public function destroy(Deadline $deadline)
    {
        $this->authorize('delete', $deadline);
        
        $deadline->delete();

        return redirect()->route('deadlines.index')->with('success', 'Deadline supprimée !');
    }
}