<x-app-layout :project="$project">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            🗺️ Roadmap - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4">
            
            <!-- Légende -->
            <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-4 mb-6 flex items-center gap-6">
                <span class="text-gray-300 font-semibold">Légende :</span>
                @foreach($project->epics as $epic)
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded" style="background: {{ $epic->color }};"></div>
                        <span class="text-gray-300 text-sm">{{ $epic->name }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Timeline -->
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6 overflow-x-auto">
                
                <!-- En-tête avec les sprints -->
                <div class="flex mb-8 border-b border-gray-600 pb-4">
                    <div class="w-48 flex-shrink-0"></div>
                    <div class="flex-1 flex gap-4">
                        @foreach($sprints as $sprint)
                            <div class="flex-1 text-center">
                                <div class="inline-block px-6 py-3 rounded-full font-bold text-white mb-2 {{ $sprint->status === 'active' ? 'bg-blue-600' : ($sprint->status === 'completed' ? 'bg-green-600' : 'bg-gray-600') }}">
                                    {{ $sprint->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($sprint->start_date)->format('d/m') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($sprint->end_date)->format('d/m') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Epics et tâches -->
                @foreach($project->epics as $epic)
                    <div class="mb-8">
                        <!-- Nom de l'epic -->
                        <div class="flex items-center mb-4">
                            <div class="w-48 flex-shrink-0 flex items-center gap-3">
                                <div class="w-12 h-12 rounded flex items-center justify-center" style="background: {{ $epic->color }};">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-200">{{ $epic->name }}</h3>
                                    <p class="text-xs text-gray-400">{{ $epic->tasks->count() }} tâche(s)</p>
                                </div>
                            </div>
                            
                            <!-- Timeline des tâches -->
                            <div class="flex-1 flex gap-4 relative" style="min-height: 100px;">
                                @foreach($sprints as $sprint)
                                    <div class="flex-1 border-l border-gray-700 pl-4">
                                        @php
                                            $sprintTasks = $epic->tasks->where('sprint_id', $sprint->id);
                                        @endphp
                                        
                                        @foreach($sprintTasks as $task)
                                            <div class="mb-2 p-3 rounded-lg text-white font-semibold text-sm hover:opacity-80 transition cursor-pointer"
                                                 style="background: {{ $epic->color }}; box-shadow: 0 2px 8px {{ $epic->color }}40;"
                                                 onclick="window.location='{{ route('tasks.show', $task) }}'">
                                                {{ $task->title }}
                                                <div class="flex items-center gap-2 mt-1 text-xs opacity-90">
                                                    <span class="px-2 py-0.5 bg-white/20 rounded">{{ ucfirst($task->status) }}</span>
                                                    <span class="px-2 py-0.5 bg-white/20 rounded">{{ ucfirst($task->priority) }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Tâches sans epic -->
                @php
                    $noEpicTasks = $project->tasks->whereNull('epic_id');
                @endphp
                @if($noEpicTasks->count() > 0)
                    <div class="mb-8 border-t border-gray-700 pt-8">
                        <div class="flex items-center mb-4">
                            <div class="w-48 flex-shrink-0 flex items-center gap-3">
                                <div class="w-12 h-12 rounded flex items-center justify-center bg-gray-600">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-200">Sans Epic</h3>
                                    <p class="text-xs text-gray-400">{{ $noEpicTasks->count() }} tâche(s)</p>
                                </div>
                            </div>
                            
                            <div class="flex-1 flex gap-4 relative" style="min-height: 100px;">
                                @foreach($sprints as $sprint)
                                    <div class="flex-1 border-l border-gray-700 pl-4">
                                        @php
                                            $sprintTasks = $noEpicTasks->where('sprint_id', $sprint->id);
                                        @endphp
                                        
                                        @foreach($sprintTasks as $task)
                                            <div class="mb-2 p-3 rounded-lg bg-gray-600 text-white font-semibold text-sm hover:opacity-80 transition cursor-pointer"
                                                 onclick="window.location='{{ route('tasks.show', $task) }}'">
                                                {{ $task->title }}
                                                <div class="flex items-center gap-2 mt-1 text-xs opacity-90">
                                                    <span class="px-2 py-0.5 bg-white/20 rounded">{{ ucfirst($task->status) }}</span>
                                                    <span class="px-2 py-0.5 bg-white/20 rounded">{{ ucfirst($task->priority) }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            @if($sprints->count() === 0)
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">🗺️</div>
                    <p class="text-gray-400 text-lg mb-4">Aucun sprint pour afficher la roadmap.</p>
                    <a href="{{ route('projects.sprints.create', $project) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 px-6 rounded">
                        Créer le premier sprint
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>