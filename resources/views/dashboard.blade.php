<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                👋 Bonjour, {{ auth()->user()->name }} !
            </h2>
            <div class="text-sm text-gray-400">
                {{ now()->translatedFormat('l j F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                
                <!-- PARTIE GAUCHE (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Échéances proches -->
                    <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-gray-200 flex items-center gap-2">
                                <span class="text-2xl">⏰</span>
                                Échéances proches
                            </h3>
                            <span class="text-xs text-gray-400">7 prochains jours</span>
                        </div>

                        @if($upcomingDeadlines->count() > 0)
                            <div class="space-y-2">
                                @foreach($upcomingDeadlines as $task)
                                    <a href="{{ route('tasks.show', $task) }}" 
                                       class="block p-3 bg-gray-900/50 hover:bg-gray-700/50 border-l-4 rounded transition"
                                       style="border-color: {{ $task->epic->color ?? '#6b7280' }};">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-200">{{ $task->title }}</h4>
                                                <p class="text-xs text-gray-400 mt-1">{{ $task->project->name }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-semibold {{ $task->due_date->isPast() ? 'text-red-400' : 'text-yellow-400' }}">
                                                    {{ $task->due_date->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $task->due_date->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <div class="text-4xl mb-2">✅</div>
                                <p>Aucune échéance proche</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tâches en cours -->
                    <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-gray-200 flex items-center gap-2">
                                <span class="text-2xl">🚀</span>
                                Tâches en cours
                            </h3>
                            <span class="text-xs bg-blue-900/50 text-blue-300 px-2 py-1 rounded">
                                {{ $stats['tasks_in_progress'] }} tâche(s)
                            </span>
                        </div>

                        @if($tasksInProgress->count() > 0)
                            <div class="space-y-2">
                                @foreach($tasksInProgress as $task)
                                    <a href="{{ route('tasks.show', $task) }}" 
                                       class="block p-3 bg-gray-900/50 hover:bg-gray-700/50 border-l-4 rounded transition"
                                       style="border-color: {{ $task->epic->color ?? '#6b7280' }};">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-200">{{ $task->title }}</h4>
                                                <p class="text-xs text-gray-400 mt-1">{{ $task->project->name }}</p>
                                            </div>
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $task->priority === 'high' ? 'bg-red-900/50 text-red-300' : ($task->priority === 'medium' ? 'bg-yellow-900/50 text-yellow-300' : 'bg-gray-700 text-gray-300') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <div class="text-4xl mb-2">📋</div>
                                <p>Aucune tâche en cours</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- PARTIE DROITE (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Projets récents -->
                    <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-gray-200">📁 Projets</h3>
                        </div>

                        @if($recentProjects->count() > 0)
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                @foreach($recentProjects as $project)
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="block p-4 rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105"
                                       style="background: linear-gradient(135deg, {{ $project->color }}dd 0%, {{ $project->color }}99 100%);">
                                        <div class="text-center">
                                            <div class="font-bold text-white text-sm mb-1 truncate">
                                                {{ $project->name }}
                                            </div>
                                            <div class="text-xs text-white/80">
                                                {{ $project->tasks->count() }} tâche(s)
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <a href="{{ route('projects.index') }}" 
                               class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded transition">
                                Voir tous les projets →
                            </a>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <div class="text-4xl mb-2">📁</div>
                                <p class="mb-4">Aucun projet</p>
                                <a href="{{ route('projects.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded">
                                    Créer un projet
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Notifications récentes -->
                    <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-gray-200">🔔 Notifications</h3>
                        </div>

                        @php
                            $recentNotifications = auth()->user()->notifications()->take(3)->get();
                        @endphp

                        @if($recentNotifications->count() > 0)
                            <div class="space-y-2 mb-4">
                                @foreach($recentNotifications as $notif)
                                    <a href="{{ route('notifications.index') }}" 
                                       class="block p-2 bg-gray-900/50 hover:bg-gray-700/50 rounded text-sm transition {{ $notif->read ? 'opacity-60' : '' }}">
                                        <div class="font-semibold text-gray-200">{{ $notif->title }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                                    </a>
                                @endforeach
                            </div>

                            <a href="{{ route('notifications.index') }}" 
                               class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded transition">
                                Voir toutes les notifications →
                            </a>
                        @else
                            <div class="text-center py-4 text-gray-400">
                                <p class="text-sm">Aucune notification</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- BARRE DE STATISTIQUES (toute la largeur) -->
            <div class="bg-gradient-to-r from-gray-800/70 to-gray-900/70 border border-gray-700 rounded-lg p-6 shadow-lg">
                <div class="flex justify-between gap-4">
                    
                    <div class="text-center p-4 bg-gray-900/50 rounded-lg">
                        <div class="text-3xl font-bold text-cyan-400">{{ $stats['total_projects'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">Projets</div>
                    </div>

                    <div class="text-center p-4 bg-gray-900/50 rounded-lg">
                        <div class="text-3xl font-bold text-gray-200">{{ $stats['total_tasks'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">Total tâches</div>
                    </div>

                    <div class="text-center p-4 bg-green-900/30 rounded-lg">
                        <div class="text-3xl font-bold text-green-400">{{ $stats['tasks_completed'] }}</div>
                        <div class="text-xs text-green-300 mt-1">Terminées</div>
                    </div>

                    <div class="text-center p-4 bg-blue-900/30 rounded-lg">
                        <div class="text-3xl font-bold text-blue-400">{{ $stats['tasks_in_progress'] }}</div>
                        <div class="text-xs text-blue-300 mt-1">En cours</div>
                    </div>

                    <div class="text-center p-4 bg-gray-900/50 rounded-lg">
                        <div class="text-3xl font-bold text-gray-300">{{ $stats['tasks_todo'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">À faire</div>
                    </div>

                    <div class="text-center p-4 bg-red-900/30 rounded-lg">
                        <div class="text-3xl font-bold text-red-400">{{ $stats['overdue_tasks'] }}</div>
                        <div class="text-xs text-red-300 mt-1">En retard</div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>