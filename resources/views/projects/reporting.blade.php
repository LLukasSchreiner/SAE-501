<x-app-layout :project="$project">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            📊 Reporting - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Stats en haut -->
            <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center gap-6">

                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-cyan-400">{{ $stats['total_tasks'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">Total tâches</div>
                    </div>

                    <div class="w-px h-10 bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-green-400">{{ $stats['completed_tasks'] }}</div>
                        <div class="text-xs text-green-300 mt-1">Terminées</div>
                    </div>

                    <div class="w-px h-10 bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-blue-400">{{ $stats['completion_rate'] }}%</div>
                        <div class="text-xs text-blue-300 mt-1">Progression</div>
                    </div>

                    <div class="w-px h-10 bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-purple-400">{{ $stats['active_sprints'] }}</div>
                        <div class="text-xs text-purple-300 mt-1">Sprints actifs</div>
                    </div>

                    <div class="w-px h-10 bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-yellow-400">{{ $stats['total_epics'] }}</div>
                        <div class="text-xs text-yellow-300 mt-1">Epics</div>
                    </div>

                    <div class="w-px h-10 bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-red-400">{{ $stats['overdue_tasks'] }}</div>
                        <div class="text-xs text-red-300 mt-1">En retard</div>
                    </div>

                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                <!-- Répartition par statut -->
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6">
                    <h3 class="font-bold text-lg text-gray-200 mb-4">📊 Répartition par statut</h3>
                    <canvas id="statusChart"></canvas>
                </div>

                <!-- Répartition par priorité -->
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6">
                    <h3 class="font-bold text-lg text-gray-200 mb-4">🎯 Répartition par priorité</h3>
                    <canvas id="priorityChart"></canvas>
                </div>

                <!-- Progression dans le temps -->
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 lg:col-span-2">
                    <h3 class="font-bold text-lg text-gray-200 mb-4">📈 Évolution des tâches terminées</h3>
                    <canvas id="progressionChart"></canvas>
                </div>

                <!-- Tâches par epic -->
                @if(count($tasksByEpic) > 0)
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6">
                    <h3 class="font-bold text-lg text-gray-200 mb-4">🎨 Tâches par Epic</h3>
                    <canvas id="epicChart"></canvas>
                </div>
                @endif

                <!-- Charge par membre -->
                @if(count($tasksByMember) > 0)
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6">
                    <h3 class="font-bold text-lg text-gray-200 mb-4">👥 Charge par membre</h3>
                    <canvas id="memberChart"></canvas>
                </div>
                @endif

            </div>

        </div>
    </div>

    <script>
        // Configuration générale
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.borderColor = '#374151';

        // Graphique par statut (Donut)
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['À faire', 'En cours', 'Terminé', 'Annulé'],
                datasets: [{
                    data: [
                        {{ $tasksByStatus['todo'] }},
                        {{ $tasksByStatus['in_progress'] }},
                        {{ $tasksByStatus['done'] }},
                        {{ $tasksByStatus['cancelled'] }}
                    ],
                    backgroundColor: ['#6b7280', '#3b82f6', '#10b981', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#1f2937'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique par priorité (Donut)
        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Haute', 'Moyenne', 'Basse'],
                datasets: [{
                    data: [
                        {{ $tasksByPriority['high'] }},
                        {{ $tasksByPriority['medium'] }},
                        {{ $tasksByPriority['low'] }}
                    ],
                    backgroundColor: ['#ef4444', '#f59e0b', '#6b7280'],
                    borderWidth: 2,
                    borderColor: '#1f2937'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique de progression (Ligne)
        new Chart(document.getElementById('progressionChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($progressionData)) !!},
                datasets: [{
                    label: 'Tâches terminées',
                    data: {!! json_encode(array_values($progressionData)) !!},
                    borderColor: '#22d3ee',
                    backgroundColor: 'rgba(34, 211, 238, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Graphique par Epic (Barre)
        @if(count($tasksByEpic) > 0)
        new Chart(document.getElementById('epicChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($tasksByEpic)) !!},
                datasets: [{
                    label: 'Nombre de tâches',
                    data: {!! json_encode(array_values($tasksByEpic)) !!},
                    backgroundColor: '#a855f7',
                    borderColor: '#1f2937',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        @endif

        // Graphique par Membre (Barre horizontale)
        @if(count($tasksByMember) > 0)
        new Chart(document.getElementById('memberChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($tasksByMember)) !!},
                datasets: [{
                    label: 'Tâches assignées',
                    data: {!! json_encode(array_values($tasksByMember)) !!},
                    backgroundColor: '#22d3ee',
                    borderColor: '#1f2937',
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        @endif
    </script>
</x-app-layout>