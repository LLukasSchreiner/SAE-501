<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function getEvents()
    {
        $events = auth()->user()->calendarEvents()->get()->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => $event->end_date,
                'backgroundColor' => $event->color,
                'borderColor' => $event->color,
                'type' => $event->type,
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:deadline,exam,project,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'required|string',
            'description' => 'nullable|string',
            'notify_2_days' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        
        if($request->id) {
            // Mode édition
            $event = CalendarEvent::findOrFail($request->id);
            if($event->user_id !== auth()->id()) {
                abort(403);
            }
            $event->update($validated);
        } else {
            // Mode création
            CalendarEvent::create($validated);
        }

        return redirect()->route('calendar.index')->with('success', 'Événement enregistré !');
    }

    public function destroy($id)
    {
        $event = CalendarEvent::findOrFail($id);
        
        if($event->user_id !== auth()->id()) {
            abort(403);
        }
        
        $event->delete();

        return response()->json(['success' => true]);
    }
}