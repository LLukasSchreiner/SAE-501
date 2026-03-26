<aside class="w-64 border-r border-gray-700 flex flex-col" style="background: linear-gradient(135deg, rgba(15, 20, 25, 0.95) 0%, rgba(26, 35, 50, 0.95) 100%); min-height: calc(100vh - 4rem);">
    <div class="p-6 flex-1">
        <!-- Infos du projet -->
        <div class="mb-6 pb-6 border-b border-gray-700">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-4 h-4 rounded" style="background-color: {{ $project->color }};"></span>
                <h3 class="font-bold text-lg text-gray-200">{{ $project->name }}</h3>
            </div>
            <p class="text-sm text-gray-400">{{ Str::limit($project->description, 60) }}</p>
        </div>

        <!-- Navigation du projet -->
        <nav class="space-y-1" id="main-menu">
            <a href="{{ route('projects.show', $project) }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.show') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                Vue Kanban
            </a>

            <a href="{{ route('projects.roadmap', $project) }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.roadmap') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
                Roadmap
            </a>

            <a href="{{ route('projects.sprints.index', $project) }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.sprints.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                Sprints
            </a>

            <a href="{{ route('projects.epics.index', $project) }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.epics.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                </svg>
                Epics
            </a>

            <button onclick="toggleFilters()" 
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.tasks.index') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h.01a1 1 0 100-2H6zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                </svg>
                Toutes les tâches
            </button>

            <a href="{{ route('projects.notes', $project) }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.notes') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                </svg>
                Notes liées
            </a>

            <a href="{{ route('projects.reporting', $project) }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('projects.reporting') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                </svg>
                Reporting
            </a>

            <button onclick="toggleMembers()" 
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
                Membres
            </button>

            <button onclick="toggleSettings()" 
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                </svg>
                Paramètres
            </button>
        </nav>

<!-- Section Membres (cachée par défaut) -->
        <div id="members-section" class="hidden overflow-y-auto" style="max-height: calc(100vh - 200px);">
            <button onclick="toggleMembers()" class="flex items-center gap-2 text-gray-400 hover:text-gray-200 mb-4 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Retour
            </button>
            
            <h3 class="font-bold text-lg text-gray-200 mb-4">👥 Membres du projet</h3>
            
            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="bg-green-900/50 border border-green-600 text-green-300 px-3 py-2 rounded mb-4 text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900/50 border border-red-600 text-red-300 px-3 py-2 rounded mb-4 text-sm">
                    ✗ {{ session('error') }}
                </div>
            @endif
            
            <!-- Formulaire d'ajout -->
            @can('update', $project)
                <div class="mb-6 p-4 bg-gray-900/50 border border-gray-700 rounded-lg">
                    <h4 class="font-semibold text-gray-200 mb-3 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                        Inviter un membre
                    </h4>
                    <form method="POST" action="{{ route('projects.invite', $project) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" required
                                   placeholder="utilisateur@exemple.com"
                                   class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs text-gray-400 mb-1">Rôle</label>
                            <select name="role" required
                                    class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="member">👤 Membre</option>
                                <option value="viewer">👁️ Observateur</option>
                                <option value="owner">👑 Propriétaire</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded text-sm transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            Envoyer l'invitation
                        </button>
                    </form>
                </div>
            @endcan

            <!-- Liste des membres -->
            <div class="space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase mb-2">Membres actuels ({{ $project->users->count() }})</div>
                
                @foreach($project->users as $member)
                    <div class="p-3 bg-gray-800/50 hover:bg-gray-800/70 rounded-lg border border-gray-700 transition">
                        <div class="flex items-center gap-3 mb-2">
                            <!-- Avatar -->
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-lg">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-200 truncate">{{ $member->name }}</div>
                                <div class="text-xs text-gray-400 truncate">{{ $member->email }}</div>
                            </div>

                            <!-- Badge propriétaire -->
                            @if($project->owner_id === $member->id)
                                <span class="px-2 py-1 rounded text-xs font-bold bg-yellow-900/50 text-yellow-300 border border-yellow-700">
                                    👑 Proprio
                                </span>
                            @endif
                        </div>

                        <!-- Rôle (modifiable si permission) -->
                        @can('update', $project)
                            @if($project->owner_id !== $member->id)
                                <form method="POST" action="{{ route('projects.members.updateRole', [$project, $member]) }}" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" 
                                            class="w-full text-xs border border-gray-600 bg-gray-700 text-gray-200 rounded px-2 py-1.5 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                            onchange="this.form.submit()">
                                        <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>👤 Membre</option>
                                        <option value="viewer" {{ $member->pivot->role === 'viewer' ? 'selected' : '' }}>👁️ Observateur</option>
                                        <option value="owner" {{ $member->pivot->role === 'owner' ? 'selected' : '' }}>👑 Propriétaire</option>
                                    </select>
                                </form>

                                <!-- Bouton retirer -->
                                <form method="POST" action="{{ route('projects.members.remove', [$project, $member]) }}" 
                                      onsubmit="return confirm('⚠️ Retirer {{ $member->name }} du projet ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-xs bg-red-900/30 hover:bg-red-900/50 text-red-400 py-1.5 rounded transition font-semibold">
                                        🗑️ Retirer du projet
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="text-xs px-2 py-1 rounded bg-gray-700 text-gray-300 text-center">
                                {{ $member->pivot->role === 'owner' ? '👑 Propriétaire' : ($member->pivot->role === 'viewer' ? '👁️ Observateur' : '👤 Membre') }}
                            </div>
                        @endcan
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section Paramètres (cachée par défaut) -->
        <div id="settings-section" class="hidden">
            <button onclick="toggleSettings()" class="flex items-center gap-2 text-gray-400 hover:text-gray-200 mb-4">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Retour
            </button>
            
            <h3 class="font-bold text-lg text-gray-200 mb-4">Paramètres</h3>
            
            <div class="space-y-3">
                <a href="{{ route('projects.edit', $project) }}" 
                   class="block p-3 bg-gray-800/50 hover:bg-gray-800 rounded-lg transition">
                    <div class="font-semibold text-gray-200 mb-1">✏️ Modifier le projet</div>
                    <div class="text-xs text-gray-400">Nom, description, couleur</div>
                </a>

                <div class="p-3 bg-gray-800/50 rounded-lg">
                    <div class="font-semibold text-gray-200 mb-2">🎨 Couleur du projet</div>
                    <div class="flex gap-2 flex-wrap">
                        @php
                            $colors = ['#ef4444', '#f97316', '#f59e0b', '#10b981', '#22d3ee', '#3b82f6', '#a855f7', '#ec4899'];
                        @endphp
                        @foreach($colors as $color)
                            <div class="w-8 h-8 rounded cursor-pointer border-2 {{ $project->color === $color ? 'border-white' : 'border-gray-600' }}" 
                                 style="background: {{ $color }};"
                                 onclick="updateProjectColor('{{ $color }}')"></div>
                        @endforeach
                    </div>
                </div>

                @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" 
                          onsubmit="return confirm('⚠️ Supprimer ce projet et toutes ses données ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full p-3 bg-red-900/30 hover:bg-red-900/50 text-red-400 rounded-lg font-semibold transition">
                            🗑️ Supprimer le projet
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Section Filtres (cachée par défaut) -->
        <div id="filters-section" class="hidden overflow-y-auto" style="max-height: calc(100vh - 200px);">
            <button onclick="toggleFilters()" class="flex items-center gap-2 text-gray-400 hover:text-gray-200 mb-4">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Retour
            </button>
            
            <h3 class="font-bold text-lg text-gray-200 mb-4">🔍 Filtres des tâches</h3>
            
            <form method="GET" action="{{ route('projects.tasks.index', $project) }}" class="space-y-4">
                
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Titre de la tâche..."
                           class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Statut</label>
                    <select name="status" class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="">Tous</option>
                        <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>À faire</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Terminé</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <!-- Priorité -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Priorité</label>
                    <select name="priority" class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="">Toutes</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Haute</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Basse</option>
                    </select>
                </div>

                <!-- Assigné à -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Assigné à</label>
                    <select name="assigned_to" class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="">Tous</option>
                        @foreach($project->users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Epic -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Epic</label>
                    <select name="epic_id" class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="">Tous</option>
                        @foreach($project->epics as $epic)
                            <option value="{{ $epic->id }}" {{ request('epic_id') == $epic->id ? 'selected' : '' }}>
                                {{ $epic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sprint -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Sprint</label>
                    <select name="sprint_id" class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="">Tous</option>
                        @foreach($project->sprints as $sprint)
                            <option value="{{ $sprint->id }}" {{ request('sprint_id') == $sprint->id ? 'selected' : '' }}>
                                {{ $sprint->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tri -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">Trier par</label>
                    <select name="sort" class="w-full border border-gray-600 bg-gray-800 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="created_desc" {{ request('sort', 'created_desc') === 'created_desc' ? 'selected' : '' }}>Plus récent</option>
                        <option value="created_asc" {{ request('sort') === 'created_asc' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="due_date_asc" {{ request('sort') === 'due_date_asc' ? 'selected' : '' }}>Échéance (proche)</option>
                        <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Priorité</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="space-y-2 pt-4">
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded transition">
                        Appliquer
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'assigned_to', 'epic_id', 'sprint_id', 'sort']))
                        <a href="{{ route('projects.tasks.index', $project) }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center transition">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>
        
    </div>
</aside>

<script>
function toggleMembers() {
    document.getElementById('main-menu').classList.toggle('hidden');
    document.getElementById('members-section').classList.toggle('hidden');
    document.getElementById('settings-section').classList.add('hidden');
    document.getElementById('filters-section').classList.add('hidden');  // ← Cette ligne doit être là
}

function toggleSettings() {
    document.getElementById('main-menu').classList.toggle('hidden');
    document.getElementById('settings-section').classList.toggle('hidden');
    document.getElementById('members-section').classList.add('hidden');
    document.getElementById('filters-section').classList.add('hidden');
}

function toggleFilters() {
    document.getElementById('main-menu').classList.toggle('hidden');
    document.getElementById('filters-section').classList.toggle('hidden');
    document.getElementById('members-section').classList.add('hidden');
    document.getElementById('settings-section').classList.add('hidden');
}

function updateProjectColor(color) {
    fetch(`/projects/{{ $project->id }}/update-color`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ color: color })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        }
    });
}
</script>