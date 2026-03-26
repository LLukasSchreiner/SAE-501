<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle Deadline') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('deadlines.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Titre *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="due_date" class="block text-gray-700 text-sm font-bold mb-2">Date et heure *</label>
                            <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('due_date') border-red-500 @enderror">
                            @error('due_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type *</label>
                            <select name="type" id="type" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type') border-red-500 @enderror">
                                <option value="">Sélectionnez un type</option>
                                <option value="exam" {{ old('type') === 'exam' ? 'selected' : '' }}>Examen</option>
                                <option value="project" {{ old('type') === 'project' ? 'selected' : '' }}>Projet</option>
                                <option value="assignment" {{ old('type') === 'assignment' ? 'selected' : '' }}>Devoir</option>
                                <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('deadlines.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Créer la deadline
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>