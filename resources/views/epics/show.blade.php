<x-app-layout :project="$epic->project">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="w-4 h-4 rounded-full" style="background: {{ $epic->color }};"></span>
                {{ $epic->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('epics.edit', $epic) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Modifier
                </a>
                <form method="POST" action="{{ route('epics.destroy', $epic) }}" onsubmit="return confirm('Supprimer cet epic ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-2">Description</h3>
                    <p class="text-gray-700 mb-4">{{ $epic->description ?: 'Aucune description' }}</p>
                    
                    <div class="text-sm text-gray-500">
                        <p>Projet : <a href="{{ route('projects.show', $epic->project) }}" class="text-blue-600 hover:underline">{{ $epic->project->name }}</a></p>
                    </div>
                </div>
            </div>

            <!-- Tâches de l'epic -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-4">Tâches ({{ $epic->tasks->count() }})</h3>
                    
                    @if($epic->tasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($epic->tasks as $task)
                                <div class="border-l-4 pl-4 py-2" style="border-color: {{ $epic->color }};">
                                    <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-blue-600 hover:underline">
                                        {{ $task->title }}
                                    </a>
                                    <p class="text-sm text-gray-600">
                                        Statut: 
                                        <span class="px-2 py-0.5 rounded text-xs {{ $task->status === 'done' ? 'bg-green-100 text-green-800' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Aucune tâche associée à cet epic.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>