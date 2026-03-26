<x-app-layout :project="$project">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Créer un sprint - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-6">
                
                <form method="POST" action="{{ route('projects.sprints.store', $project) }}">
                    @csrf

                    <!-- Nom -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom du sprint *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Ex: Sprint 1, Sprint Mars 2024"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Objectif -->
                    <div class="mb-4">
                        <label for="goal" class="block text-gray-700 text-sm font-bold mb-2">Objectif (optionnel)</label>
                        <textarea name="goal" id="goal" rows="3"
                                  placeholder="Objectif principal de ce sprint..."
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('goal') }}</textarea>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Date de début *</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Date de fin *</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="mb-6">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Statut *</label>
                        <select name="status" id="status" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planifié</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('projects.sprints.index', $project) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Créer le sprint
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>