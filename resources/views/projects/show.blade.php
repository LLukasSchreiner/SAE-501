<x-app-layout :project="$project">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="w-6 h-6 rounded" style="background-color: {{ $project->color }};"></span>
                <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                    {{ $project->name }}
                </h2>
            </div>
            <div class="flex space-x-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-bold py-2 px-4 rounded text-sm border border-gray-600">
                        Modifier le projet
                    </a>
                @endcan
                @can('update', $project)
                    <a href="{{ route('projects.tasks.create', $project) }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded text-sm">
                        + Nouvelle Tâche
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-cyan-900/50 border border-cyan-600 text-cyan-200 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filtres par Epic -->
            <div class="mb-6 bg-gray-800/50 border border-gray-700 rounded-lg p-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-gray-300 font-semibold">🔍 Filtrer par Epic :</span>
                    
                    <button onclick="filterByEpic(null)" 
                            class="epic-filter-btn px-4 py-2 rounded-lg font-semibold transition bg-cyan-600 text-white border-2 border-cyan-600"
                            data-epic="all">
                        Tous
                    </button>

                    @foreach($epics as $epic)
                        <button onclick="filterByEpic({{ $epic->id }})" 
                                class="epic-filter-btn px-4 py-2 rounded-lg font-semibold transition border-2"
                                data-epic="{{ $epic->id }}"
                                style="background: {{ $epic->color }}20; color: {{ $epic->color }}; border-color: {{ $epic->color }};">
                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background: {{ $epic->color }};"></span>
                            {{ $epic->name }}
                        </button>
                    @endforeach

                    <button onclick="filterByEpic('none')" 
                            class="epic-filter-btn px-4 py-2 rounded-lg font-semibold transition bg-gray-700 text-gray-300 border-2 border-gray-600"
                            data-epic="none">
                        Sans Epic
                    </button>
                </div>
            </div>

            <div class="kanban-container">
                <div class="grid grid-cols-4 gap-4 h-[calc(100vh-320px)]">
                    
                    <!-- Colonne À FAIRE -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="kanban-column-title">À FAIRE</h3>
                            </div>
                        </div>
                        <div id="todo" data-status="todo" class="kanban-column-content">
                            @foreach($groupedTasks['todo'] as $task)
                                @include('projects.partials.kanban-card', ['task' => $task])
                            @endforeach
                        </div>
                    </div>

                    <!-- Colonne EN COURS -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="kanban-column-title text-blue-400">EN COURS</h3>
                            </div>
                        </div>
                        <div id="in_progress" data-status="in_progress" class="kanban-column-content">
                            @foreach($groupedTasks['in_progress'] as $task)
                                @include('projects.partials.kanban-card', ['task' => $task])
                            @endforeach
                        </div>
                    </div>

                    <!-- Colonne TERMINÉ -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="kanban-column-title text-green-400">TERMINÉ</h3>
                            </div>
                        </div>
                        <div id="done" data-status="done" class="kanban-column-content">
                            @foreach($groupedTasks['done'] as $task)
                                @include('projects.partials.kanban-card', ['task' => $task])
                            @endforeach
                        </div>
                    </div>

                    <!-- Colonne ANNULÉ -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="kanban-column-title text-red-400">ANNULÉ</h3>
                            </div>
                        </div>
                        <div id="cancelled" data-status="cancelled" class="kanban-column-content">
                            @foreach($groupedTasks['cancelled'] as $task)
                                @include('projects.partials.kanban-card', ['task' => $task])
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        function filterByEpic(epicId) {
            // Mettre à jour les boutons
            document.querySelectorAll('.epic-filter-btn').forEach(btn => {
                btn.classList.remove('bg-cyan-600', 'text-white', 'border-cyan-600');
                btn.classList.add('bg-gray-700', 'text-gray-300', 'border-gray-600');
            });

            const activeBtn = epicId === null 
                ? document.querySelector('[data-epic="all"]')
                : epicId === 'none'
                    ? document.querySelector('[data-epic="none"]')
                    : document.querySelector(`[data-epic="${epicId}"]`);
            
            if(activeBtn) {
                activeBtn.classList.remove('bg-gray-700', 'text-gray-300', 'border-gray-600');
                if(epicId === null) {
                    activeBtn.classList.add('bg-cyan-600', 'text-white', 'border-cyan-600');
                }
            }

            // Filtrer les cartes
            document.querySelectorAll('.kanban-card').forEach(card => {
                const cardEpicId = card.dataset.epicId;
                
                if(epicId === null) {
                    // Tout afficher
                    card.style.display = 'block';
                } else if(epicId === 'none') {
                    // Afficher seulement les tâches sans epic
                    card.style.display = !cardEpicId || cardEpicId === '' ? 'block' : 'none';
                } else {
                    // Afficher seulement les tâches de cet epic
                    card.style.display = cardEpicId == epicId ? 'block' : 'none';
                }
            });
        }
    </script>

    
<style>
        body {
            background-color: #0f1419;
        }
        
        .kanban-container {
            background: linear-gradient(180deg, #0f1419 0%, #1a2332 100%);
            border-radius: 8px;
            padding: 1rem;
        }

        .kanban-column {
            background: rgba(17, 24, 39, 0.5);
            border: 1px solid rgba(55, 65, 81, 0.3);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        .kanban-column-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(55, 65, 81, 0.3);
        }

        .kanban-column-title {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
        }

        .kanban-column-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .kanban-column-content::-webkit-scrollbar {
            width: 6px;
        }

        .kanban-column-content::-webkit-scrollbar-track {
            background: rgba(31, 41, 55, 0.5);
            border-radius: 3px;
        }

        .kanban-column-content::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.8);
            border-radius: 3px;
        }

        .kanban-column-content::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 0.9);
        }

        .kanban-card {
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.8) 0%, rgba(17, 24, 39, 0.9) 100%);
            border: 1px solid rgba(75, 85, 99, 0.4);
            border-radius: 6px;
            padding: 1rem;
            cursor: grab;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .kanban-card:hover {
            border-color: rgba(34, 211, 238, 0.6);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 211, 238, 0.15);
        }

        .kanban-card.dragging {
            opacity: 0.5;
            cursor: grabbing;
        }

        .kanban-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--card-accent, #6b7280);
        }

        .kanban-card-title {
            color: #e5e7eb;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .kanban-card-title:hover {
            color: #22d3ee;
        }

        .kanban-card-description {
            color: #9ca3af;
            font-size: 0.813rem;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }

        .kanban-card-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.75rem;
        }

        .kanban-card-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .priority-badge {
            padding: 0.25rem 0.625rem;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.688rem;
            letter-spacing: 0.025em;
        }

        .priority-high {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .priority-medium {
            background: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .priority-low {
            background: rgba(107, 114, 128, 0.2);
            color: #d1d5db;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .assigned-user {
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .due-date {
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .due-date.overdue {
            color: #f87171;
        }
    </style>
</x-app-layout>