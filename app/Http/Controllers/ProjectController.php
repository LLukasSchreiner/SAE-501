<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\ProjectInvitation;
use App\Models\User; 

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $projects = auth()->user()->projects()->with('owner')->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class); 
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = auth()->user()->ownedProjects()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        $project->users()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('projects.show', $project)->with('success', 'Projet créé avec succès !');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['users', 'tasks.assignedUser', 'tasks.epic', 'epics']);

        $groupedTasks = [
            'todo' => $project->tasks->where('status', 'todo'),
            'in_progress' => $project->tasks->where('status', 'in_progress'),
            'done' => $project->tasks->where('status', 'done'),
            'cancelled' => $project->tasks->where('status', 'cancelled'),
        ];

        $epics = $project->epics;

        return view('projects.show', compact('project', 'groupedTasks', 'epics'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Projet mis à jour !');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé !');
    }

    public function roadmap(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['epics.tasks', 'tasks']);
        $sprints = $project->sprints()->orderBy('start_date')->with('tasks')->get();

        return view('projects.roadmap', compact('project', 'sprints'));
    }
    
    public function notes(Project $project)
    {
        $this->authorize('view', $project);
        
        // TODO: Lier les notes aux projets
        $notes = collect();
        
        return view('projects.notes', compact('project', 'notes'));
    }
    
    public function updateColor(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'color' => 'required|string'
        ]);
        
        $project->update(['color' => $validated['color']]);
        
        return response()->json(['success' => true]);
    }

    public function addMember(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:owner,member,viewer'
        ]);
        
        $user = \App\Models\User::where('email', $validated['email'])->first();
        
        // Vérifier si déjà membre
        if($project->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }
        
        // Ajouter le membre
        $project->users()->attach($user->id, ['role' => $validated['role']]);
        
        return back()->with('success', $user->name . ' a été ajouté au projet !');
    }
    
    public function removeMember(Project $project, \App\Models\User $user)
    {
        $this->authorize('update', $project);
        
        // Ne pas permettre de retirer le propriétaire
        if($project->owner_id === $user->id) {
            return back()->with('error', 'Impossible de retirer le propriétaire du projet.');
        }
        
        $project->users()->detach($user->id);
        
        return back()->with('success', $user->name . ' a été retiré du projet.');
    }
    
    public function updateMemberRole(Request $request, Project $project, \App\Models\User $user)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'role' => 'required|in:owner,member,viewer'
        ]);
        
        $project->users()->updateExistingPivot($user->id, ['role' => $validated['role']]);
        
        return back()->with('success', 'Rôle mis à jour !');
    }   

    public function inviteMember(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:owner,member,viewer'
        ]);

        $invitee = User::where('email', $validated['email'])->first();

        // Vérifier si déjà membre
        if($project->users()->where('user_id', $invitee->id)->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        // Vérifier si invitation déjà envoyée
        $existingInvitation = ProjectInvitation::where('project_id', $project->id)
            ->where('invitee_id', $invitee->id)
            ->where('status', 'pending')
            ->first();

        if($existingInvitation) {
            return back()->with('error', 'Une invitation a déjà été envoyée à cet utilisateur.');
        }

        // Créer l'invitation
        $invitation = ProjectInvitation::create([
            'project_id' => $project->id,
            'inviter_id' => auth()->id(),
            'invitee_id' => $invitee->id,
            'role' => $validated['role'],
            'token' => ProjectInvitation::generateToken(),
            'expires_at' => now()->addDays(7) // Expire dans 7 jours
        ]);

        // Optionnel : Envoyer un email
        // Mail::to($invitee)->send(new ProjectInvitationMail($invitation));

        return back()->with('success', 'Invitation envoyée à ' . $invitee->name . ' !');
    }

    public function invitations()
    {
        $invitations = ProjectInvitation::where('invitee_id', auth()->id())
            ->where('status', 'pending')
            ->with(['project', 'inviter'])
            ->latest()
            ->get();

        return view('invitations.index', compact('invitations'));
    }

    public function acceptInvitation(ProjectInvitation $invitation)
    {
        if($invitation->invitee_id !== auth()->id()) {
            abort(403);
        }

        if(!$invitation->isValid()) {
            return back()->with('error', 'Cette invitation a expiré ou n\'est plus valide.');
        }

        // Ajouter l'utilisateur au projet
        $invitation->project->users()->attach(auth()->id(), ['role' => $invitation->role]);

        // Marquer l'invitation comme acceptée
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('projects.show', $invitation->project)
            ->with('success', 'Vous avez rejoint le projet ' . $invitation->project->name . ' !');
    }

    public function declineInvitation(ProjectInvitation $invitation)
    {
        if($invitation->invitee_id !== auth()->id()) {
            abort(403);
        }

        $invitation->update(['status' => 'declined']);

        return back()->with('success', 'Invitation refusée.');
    }

    public function reporting(Project $project)
    {
        $this->authorize('view', $project);
        
        $project->load(['tasks', 'epics', 'sprints', 'users']);
        
        // Stats par statut
        $tasksByStatus = [
            'todo' => $project->tasks()->where('status', 'todo')->count(),
            'in_progress' => $project->tasks()->where('status', 'in_progress')->count(),
            'done' => $project->tasks()->where('status', 'done')->count(),
            'cancelled' => $project->tasks()->where('status', 'cancelled')->count(),
        ];
        
        // Stats par priorité
        $tasksByPriority = [
            'high' => $project->tasks()->where('priority', 'high')->count(),
            'medium' => $project->tasks()->where('priority', 'medium')->count(),
            'low' => $project->tasks()->where('priority', 'low')->count(),
        ];
        
        // Stats par epic
        $tasksByEpic = [];
        foreach($project->epics as $epic) {
            $tasksByEpic[$epic->name] = $epic->tasks()->count();
        }
        
        // Stats par membre
        $tasksByMember = [];
        foreach($project->users as $user) {
            $tasksByMember[$user->name] = $project->tasks()->where('assigned_to', $user->id)->count();
        }
        
        // Progression dans le temps (derniers 30 jours)
        $progressionData = [];
        for($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $progressionData[$date->format('d/m')] = $project->tasks()
                ->where('status', 'done')
                ->whereDate('updated_at', '<=', $date)
                ->count();
        }
        
        // Stats globales
        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $tasksByStatus['done'],
            'completion_rate' => $project->tasks()->count() > 0 
                ? round(($tasksByStatus['done'] / $project->tasks()->count()) * 100) 
                : 0,
            'active_sprints' => $project->sprints()->where('status', 'active')->count(),
            'total_epics' => $project->epics()->count(),
            'overdue_tasks' => $project->tasks()
                ->where('status', '!=', 'done')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->count(),
        ];
        
        return view('projects.reporting', compact(
            'project', 
            'tasksByStatus', 
            'tasksByPriority', 
            'tasksByEpic', 
            'tasksByMember',
            'progressionData',
            'stats'
        ));
    }

}