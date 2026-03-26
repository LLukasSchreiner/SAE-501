<x-app-layout :project="$project">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                🏃 Sprints - {{ $project->name }}
            </h2>
            @can('update', $project)
                <a href="{{ route('projects.sprints.create', $project) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded">
                    + Nouveau Sprint
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($sprints->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sprints as $sprint)
                        <div class="bg-gray-800/70 border border-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                            <!-- Header avec statut -->
                            <div class="p-4 {{ $sprint->status === 'active' ? 'bg-blue-600' : ($sprint->status === 'completed' ? 'bg-green-600' : 'bg-gray-600') }}">
                                <h3 class="font-bold text-xl text-white">{{ $sprint->name }}</h3>
                                <p class="text-white/90 text-sm">
                                    {{ \Carbon\Carbon::parse($sprint->start_date)->format('d/m/Y') }} 
                                    → 
                                    {{ \Carbon\Carbon::parse($sprint->end_date)->format('d/m/Y') }}
                                </p>
                            </div>

                            <!-- Contenu -->
                            <div class="p-6">
                                @if($sprint->goal)
                                    <p class="text-gray-300 mb-4">{{ Str::limit($sprint->goal, 100) }}</p>
                                @endif

                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <div class="text-center p-2 bg-green-900/30 border border-green-700 rounded">
                                        <div class="text-2xl font-bold text-green-300">{{ $sprint->tasks->where('status', 'done')->count() }}</div>
                                        <div class="text-xs text-green-400">Terminées</div>
                                    </div>
                                    <div class="text-center p-2 bg-blue-900/30 border border-blue-700 rounded">
                                        <div class="text-2xl font-bold text-blue-300">{{ $sprint->tasks->where('status', 'in_progress')->count() }}</div>
                                        <div class="text-xs text-blue-400">En cours</div>
                                    </div>
                                    <div class="text-center p-2 bg-gray-700 border border-gray-600 rounded">
                                        <div class="text-2xl font-bold text-gray-300">{{ $sprint->tasks->where('status', 'todo')->count() }}</div>
                                        <div class="text-xs text-gray-400">À faire</div>
                                    </div>
                                </div>

                                <!-- Progression -->
                                <div class="mb-4">
                                    @php
                                        $total = $sprint->tasks->count();
                                        $done = $sprint->tasks->where('status', 'done')->count();
                                        $progress = $total > 0 ? round(($done / $total) * 100) : 0;
                                    @endphp
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-400">Progression</span>
                                        <span class="font-bold text-gray-200">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="flex gap-2">
                                    <a href="{{ route('sprints.show', $sprint) }}" 
                                       class="flex-1 bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-3 rounded text-sm text-center transition">
                                        Voir le sprint
                                    </a>
                                    @can('update', $project)
                                        <a href="{{ route('sprints.edit', $sprint) }}" 
                                           class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-3 rounded text-sm transition">
                                            ✏️
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">🏃</div>
                    <p class="text-gray-400 text-lg mb-4">Aucun sprint pour ce projet.</p>
                    @can('update', $project)
                        <a href="{{ route('projects.sprints.create', $project) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 px-6 rounded">
                            Créer le premier sprint
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-app-layout>