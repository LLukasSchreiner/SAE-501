<x-app-layout :project="$project">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Epics du Projet') }}: {{ $project->name }}
            </h2>
            <a href="{{ route('projects.epics.create', $project) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Nouvel Epic
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($epics->count() > 0)
                        <div class="space-y-4">
                            @foreach($epics as $epic)
                                <div class="border-l-4 p-4 rounded hover:shadow-lg transition" style="border-color: {{ $epic->color }}; background: {{ $epic->color }}10;">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('epics.show', $epic) }}" class="block">
                                                <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full" style="background: {{ $epic->color }};"></span>
                                                    {{ $epic->name }}
                                                </h3>
                                                <p class="text-gray-600 mt-1">{{ $epic->description }}</p>
                                                
                                                <div class="mt-3 text-sm text-gray-500">
                                                    {{ $epic->tasks->count() }} tâche(s)
                                                </div>
                                            </a>
                                        </div>
                                        
                                        <div class="flex gap-2 ml-4">
                                            <a href="{{ route('epics.edit', $epic) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                Modifier
                                            </a>
                                            <form method="POST" action="{{ route('epics.destroy', $epic) }}" onsubmit="return confirm('Supprimer cet epic ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500">
                            Aucun epic pour ce projet. 
                            <a href="{{ route('projects.epics.create', $project) }}" class="text-blue-500 hover:underline">Créez-en un !</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>