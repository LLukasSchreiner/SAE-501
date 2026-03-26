<x-app-layout :project="$sprint->project">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                    {{ $sprint->name }}
                </h2>
                <p class="text-sm text-gray-400">
                    {{ \Carbon\Carbon::parse($sprint->start_date)->format('d/m/Y') }} 
                    → 
                    {{ \Carbon\Carbon::parse($sprint->end_date)->format('d/m/Y') }}
                </p>
            </div>
            <div class="flex gap-2">
                @can('update', $sprint->project)
                    <a href="{{ route('sprints.edit', $sprint) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded">
                        ✏️ Modifier
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats du sprint -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow">
                    <div class="text-3xl font-bold text-gray-200">{{ $sprint->tasks->count() }}</div>
                    <div class="text-gray-400">Total tâches</div>
                </div>
                <div class="bg-green-900/30 border border-green-700 rounded-lg p-6 shadow">
                    <div class="text-3xl font-bold text-green-300">{{ $sprint->tasks->where('status', 'done')->count() }}</div>
                    <div class="text-green-400">Terminées</div>
                </div>
                <div class="bg-blue-900/30 border border-blue-700 rounded-lg p-6 shadow">
                    <div class="text-3xl font-bold text-blue-300">{{ $sprint->tasks->where('status', 'in_progress')->count() }}</div>
                    <div class="text-blue-400">En cours</div>
                </div>
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow">
                    <div class="text-3xl font-bold text-gray-200">{{ $sprint->tasks->where('status', 'todo')->count() }}</div>
                    <div class="text-gray-400">À faire</div>
                </div>
            </div>

            <!-- Objectif -->
            @if($sprint->goal)
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow mb-6">
                    <h3 class="font-bold text-lg text-gray-200 mb-2">🎯 Objectif du sprint</h3>
                    <p class="text-gray-300">{{ $sprint->goal }}</p>
                </div>
            @endif

            <!-- Liste des tâches -->
            <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 shadow">
                <h3 class="font-bold text-lg text-gray-200 mb-4">📋 Tâches du sprint</h3>
                
                @if($sprint->tasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($sprint->tasks as $task)
                            <a href="{{ route('tasks.show', $task) }}" 
                               class="block p-4 bg-gray-900/50 border-l-4 hover:bg-gray-700/50 transition {{ $task->status === 'done' ? 'border-green-500' : ($task->status === 'in_progress' ? 'border-blue-500' : 'border-gray-600') }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-200">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-sm text-gray-400 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 ml-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $task->priority === 'high' ? 'bg-red-900/50 text-red-300 border border-red-700' : ($task->priority === 'medium' ? 'bg-yellow-900/50 text-yellow-300 border border-yellow-700' : 'bg-gray-700 text-gray-300 border border-gray-600') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $task->status === 'done' ? 'bg-green-900/50 text-green-300 border border-green-700' : ($task->status === 'in_progress' ? 'bg-blue-900/50 text-blue-300 border border-blue-700' : 'bg-gray-700 text-gray-300 border border-gray-600') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-center py-8">Aucune tâche dans ce sprint.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>