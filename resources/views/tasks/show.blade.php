<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $task->title }}
                </h2>
                <a href="{{ route('projects.show', $task->project) }}" class="text-sm text-blue-600 hover:underline">&larr; Retour au projet : {{ $task->project->name }}</a>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Modifier
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Supprimer cette tâche ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Colonne principale (Description, Cours, Commentaires) -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Description -->
                        <div class="pb-6 border-b">
                            <h3 class="text-lg font-semibold mb-2">Description</h3>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($task->description)) !!}
                            </div>
                        </div>

                        <!-- Notes de cours liées (SPÉCIFICITÉ) -->
                        <div class="py-6 border-b">
                            <h3 class="text-lg font-semibold mb-4">Notes de cours liées</h3>
                            <div class="space-y-2">
                                @forelse($task->courses as $course)
                                    <a href="{{ route('courses.show', $course) }}" class="block p-3 border rounded-lg hover:bg-gray-50">
                                        <h4 class="font-semibold">{{ $course->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $course->subject ?? 'Cours' }}</p>
                                    </a>
                                @empty
                                    <p class="text-gray-500">Aucune note de cours n'est liée à cette tâche.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Section Commentaires -->
                        <div class="mt-8">
                            <h3 class="font-bold text-xl text-gray-200 mb-4 flex items-center gap-2">
                                <span class="text-2xl">💬</span>
                                Commentaires
                                <span class="text-sm font-normal text-gray-400">({{ $task->comments->count() }})</span>
                            </h3>
                        
                            <!-- Formulaire d'ajout -->
                            <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-4 mb-4">
                                <form method="POST" action="{{ route('comments.store', $task) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="content" 
                                                  rows="3" 
                                                  required
                                                  placeholder="Écrivez votre commentaire..."
                                                  class="w-full border border-gray-600 bg-gray-900 text-gray-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent"></textarea>
                                        @error('content')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded transition">
                                        💬 Commenter
                                    </button>
                                </form>
                            </div>
                        
                            <!-- Liste des commentaires -->
                            @if($task->comments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($task->comments as $comment)
                                        <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-4">
                                            <div class="flex items-start gap-4">
                                                <!-- Avatar -->
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                                
                                                <!-- Contenu -->
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div>
                                                            <span class="font-semibold text-gray-200">{{ $comment->user->name }}</span>
                                                            <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        
                                                        @if($comment->user_id === auth()->id())
                                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" 
                                                                  onsubmit="return confirm('Supprimer ce commentaire ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm">
                                                                    🗑️ Supprimer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                    
                                                    <p class="text-gray-300 text-sm whitespace-pre-wrap">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-8 text-center">
                                    <div class="text-4xl mb-2">💬</div>
                                    <p class="text-gray-400">Aucun commentaire pour le moment.</p>
                                    <p class="text-gray-500 text-sm mt-1">Soyez le premier à commenter !</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale (Métadonnées) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-4">
                        
                        <!-- Epic -->
                        @if($task->epic)
                            <div>
                                <h4 class="font-medium text-gray-500 mb-2">Epic</h4>
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-bold text-sm" 
                                      style="background: {{ $task->epic->color }}20; color: {{ $task->epic->color }}; border: 2px solid {{ $task->epic->color }};">
                                    <span class="w-3 h-3 rounded-full" style="background: {{ $task->epic->color }};"></span>
                                    {{ $task->epic->name }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Statut -->
                        <div>
                            <h4 class="font-medium text-gray-500 mb-2">Statut</h4>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $task->status == 'done' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $task->status == 'todo' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $task->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        
                        <!-- Priorité -->
                        <div>
                            <h4 class="font-medium text-gray-500 mb-2">Priorité</h4>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $task->priority == 'high' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $task->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $task->priority == 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>

                        <!-- Assigné à -->
                        <div>
                            <h4 class="font-medium text-gray-500 mb-2">Assigné à</h4>
                            <div class="text-lg font-semibold text-gray-800">
                                {{ $task->assignedUser->name ?? 'Non assigné' }}
                            </div>
                        </div>

                        <!-- Échéance -->
                        @if($task->due_date)
                            <div>
                                <h4 class="font-medium text-gray-500 mb-2">Échéance</h4>
                                <div class="text-lg font-semibold {{ $task->due_date->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ $task->due_date->format('d/m/Y') }}
                                </div>
                            </div>
                        @endif

                        <!-- Sprint -->
                        @if($task->sprint)
                            <div>
                                <h4 class="font-medium text-gray-500 mb-2">Sprint</h4>
                                <div class="text-lg font-semibold">
                                    <a href="{{ route('sprints.show', $task->sprint) }}" class="text-blue-600 hover:underline">
                                        {{ $task->sprint->name }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Projet -->
                        <div>
                            <h4 class="font-medium text-gray-500 mb-2">Projet</h4>
                            <div class="text-lg font-semibold">
                                <a href="{{ route('projects.show', $task->project) }}" class="text-blue-600 hover:underline">
                                    {{ $task->project->name }}
                                </a>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="pt-4 border-t text-sm text-gray-500">
                            <p>Créé le {{ $task->created_at->format('d/m/Y à H:i') }}</p>
                            <p>Modifié le {{ $task->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>