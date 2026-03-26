<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Projets') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Créer un projet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($projects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 relative">
                                    <span class="absolute top-4 right-4 w-4 h-4 rounded-full" style="background-color: {{ $project->color }};"></span>
                                    
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $project->name }}</h5>
                                    
                                    <p class="font-normal text-gray-700 mb-4">
                                        {{ Str::limit($project->description, 100) }}
                                    </p>
                                    
                                    <div class="text-sm text-gray-600">
                                        Propriétaire : {{ $project->owner->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Modifié le : {{ $project->updated_at->format('d/m/Y') }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500">
                            Vous n'avez encore aucun projet. 
                            <a href="{{ route('projects.create') }}" class="text-blue-500 hover:underline">Commencez par en créer un !</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>