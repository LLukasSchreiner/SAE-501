<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier la tâche : {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div>
                            <x-input-label for="title" :value="__('Titre de la tâche')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description (optionnel)')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $task->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Lignes de sélection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Statut -->
                            <div>
                                <x-input-label for="status" :value="__('Statut')" />
                                <select name="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="todo" @selected(old('status', $task->status) == 'todo')>À faire</option>
                                    <option value="in_progress" @selected(old('status', $task->status) == 'in_progress')>En cours</option>
                                    <option value="done" @selected(old('status', $task->status) == 'done')>Terminé</option>
                                </select>
                            </div>

                            <!-- Priorité -->
                            <div>
                                <x-input-label for="priority" :value="__('Priorité')" />
                                <select name="priority" id="priority" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="low" @selected(old('priority', $task->priority) == 'low')>Basse</option>
                                    <option value="medium" @selected(old('priority', $task->priority) == 'medium')>Moyenne</option>
                                    <option value="high" @selected(old('priority', $task->priority) == 'high')>Haute</option>
                                </select>
                            </div>

                            <!-- Assigné à -->
                            <div>
                                <x-input-label for="assigned_to" :value="__('Assigné à (optionnel)')" />
                                <select name="assigned_to" id="assigned_to" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Personne</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" @selected(old('assigned_to', $task->assigned_to) == $member->id)>{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Échéance -->
                            <div>
                                <x-input-label for="due_date" :value="__('Date d\'échéance (optionnel)')" />
                                <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', $task->due_date?->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>

                            <!-- Epic -->
                            <div class="mb-4">
                                <label for="epic_id" class="block text-gray-700 text-sm font-bold mb-2">Epic</label>
                                <select name="epic_id" id="epic_id" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Aucun epic</option>
                                    @foreach($task->project->epics as $epic)
                                        <option value="{{ $epic->id }}" {{ old('epic_id', $task->epic_id) == $epic->id ? 'selected' : '' }}>
                                            {{ $epic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sprint -->
                            <div>
                                <x-input-label for="sprint_id" :value="__('Sprint (optionnel)')" />
                                <select name="sprint_id" id="sprint_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Aucun</option>
                                    @foreach($sprints as $sprint)
                                        <option value="{{ $sprint->id }}" @selected(old('sprint_id', $task->sprint_id) == $sprint->id)>{{ $sprint->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                         <div class="flex items-center justify-between mt-6">
                            <!-- Boutons Annuler/Mettre à jour -->
                            <a href="{{ route('tasks.show', $task) }}" class="text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <x-primary-button type="submit">
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Formulaire de suppression SÉPARÉ -->
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="mt-4" onsubmit="return confirm('Voulez-vous vraiment supprimer cette tâche ?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button class="w-full">
                            {{ __('Supprimer la tâche') }}
                        </x-danger-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>