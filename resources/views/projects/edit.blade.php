<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le projet :') }} {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" :value="__('Nom du projet')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description (optionnel)')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Date de début (optionnel)')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $project->start_date?->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Date de fin (optionnel)')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $project->end_date?->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="color" :value="__('Couleur')" />
                            <input id="color" class="block mt-1 w-24 h-10 p-1 border-gray-300 rounded-md shadow-sm" type="color" name="color" value="{{ old('color', $project->color) }}" />
                            <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('projects.show', $project) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Annuler
                            </a>
                            
                            <x-primary-button>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="border-t mt-8 pt-6 border-red-200">
                        <h3 class="text-lg font-medium text-red-700">Zone de Danger</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            La suppression d'un projet est définitive et entraînera la perte de toutes les tâches, sprints et epics associés.
                        </p>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce projet ? Cette action est irréversible.');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button class="mt-4">
                                {{ __('Supprimer le projet') }}
                            </x-danger-button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>