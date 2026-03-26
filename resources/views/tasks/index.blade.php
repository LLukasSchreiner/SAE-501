<x-app-layout :project="$project">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                📋 Toutes les tâches - {{ $project->name }}
            </h2>
            @can('update', $project)
                <a href="{{ route('projects.tasks.create', $project) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded">
                    + Nouvelle Tâche
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats rapides -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-gray-200">{{ $tasks->count() }}</div>
                    <div class="text-sm text-gray-400">Total</div>
                </div>
                <div class="bg-green-900/30 border border-green-700 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-green-400">{{ $tasks->where('status', 'done')->count() }}</div>
                    <div class="text-sm text-green-300">Terminées</div>
                </div>
                <div class="bg-blue-900/30 border border-blue-700 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-blue-400">{{ $tasks->where('status', 'in_progress')->count() }}</div>
                    <div class="text-sm text-blue-300">En cours</div>
                </div>
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-gray-200">{{ $tasks->where('status', 'todo')->count() }}</div>
                    <div class="text-sm text-gray-400">À faire</div>
                </div>
            </div>

            <!-- Liste des tâches -->
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg shadow-xl overflow-hidden">
                @if($tasks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50 border-b border-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Tâche</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Epic</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Sprint</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Assigné</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Priorité</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Échéance</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($tasks as $task)
                                    <tr class="hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-cyan-400 hover:text-cyan-300 font-semibold">
                                                {{ $task->title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($task->epic)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-bold" 
                                                      style="background: {{ $task->epic->color }}20; color: {{ $task->epic->color }}; border: 1px solid {{ $task->epic->color }};">
                                                    <span class="w-2 h-2 rounded-full" style="background: {{ $task->epic->color }};"></span>
                                                    {{ $task->epic->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-500 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $task->sprint->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $task->assignedUser->name ?? 'Non assigné' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $task->status === 'done' ? 'bg-green-900/50 text-green-300 border border-green-700' : '' }}
                                                {{ $task->status === 'in_progress' ? 'bg-blue-900/50 text-blue-300 border border-blue-700' : '' }}
                                                {{ $task->status === 'todo' ? 'bg-gray-700 text-gray-300 border border-gray-600' : '' }}
                                                {{ $task->status === 'cancelled' ? 'bg-red-900/50 text-red-300 border border-red-700' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $task->priority === 'high' ? 'bg-red-900/50 text-red-300 border border-red-700' : '' }}
                                                {{ $task->priority === 'medium' ? 'bg-yellow-900/50 text-yellow-300 border border-yellow-700' : '' }}
                                                {{ $task->priority === 'low' ? 'bg-gray-700 text-gray-300 border border-gray-600' : '' }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $task->due_date && $task->due_date->isPast() ? 'text-red-400 font-semibold' : 'text-gray-300' }}">
                                            {{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('tasks.edit', $task) }}" class="text-cyan-400 hover:text-cyan-300 text-sm font-semibold">
                                                ✏️ Modifier
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📋</div>
                        @if(request()->hasAny(['search', 'status', 'priority', 'assigned_to', 'epic_id', 'sprint_id']))
                            <p class="text-gray-400 text-lg mb-4">Aucune tâche ne correspond à vos filtres.</p>
                            <a href="{{ route('projects.tasks.index', $project) }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Réinitialiser les filtres
                            </a>
                        @else
                            <p class="text-gray-400 text-lg mb-4">Aucune tâche pour ce projet.</p>
                            @can('update', $project)
                                <a href="{{ route('projects.tasks.create', $project) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 px-6 rounded">
                                    Créer la première tâche
                                </a>
                            @endcan
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>