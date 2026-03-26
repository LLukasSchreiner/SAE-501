<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->courses()->latest()->get();
        
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);
    
        // Convertir les tags en JSON
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = json_encode($tagsArray);
        } else {
            $validated['tags'] = null;
        }
    
        $course = auth()->user()->courses()->create($validated);
    
        return redirect()->route('courses.index')->with('success', 'Note de cours créée !');
    }

    public function show(Course $course)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if($course->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $course->load('tasks.project');
        
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if($course->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if($course->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        // Convertir les tags en JSON
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = json_encode($tagsArray);
        } else {
            $validated['tags'] = null;
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Note mise à jour !');
    }

    public function destroy(Course $course)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if($course->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Note supprimée !');
    }
}