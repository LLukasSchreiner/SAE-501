<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📚 Mes Notes de Cours
            </h2>
            <a href="{{ route('courses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Nouvelle Note
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

            <!-- Filtres et recherche -->
            <div class="bg-white rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('courses.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Recherche par tag -->
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700">🏷️ Filtrer par tag</label>
                            <select name="tag" class="w-full border rounded px-3 py-2" onchange="this.form.submit()">
                                <option value="">Tous les tags</option>
                                @php
                                    $allTags = collect();
                                    foreach($courses as $course) {
                                        $tags = json_decode($course->tags);
                                        if($tags) {
                                            $allTags = $allTags->merge($tags);
                                        }
                                    }
                                    $allTags = $allTags->unique()->sort();
                                @endphp
                                @foreach($allTags as $tag)
                                    <option value="{{ $tag }}" {{ request('tag') === $tag ? 'selected' : '' }}>
                                        {{ $tag }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tri par date -->
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700">📅 Trier par</label>
                            <select name="sort" class="w-full border rounded px-3 py-2" onchange="this.form.submit()">
                                <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Plus récent</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Titre (A-Z)</option>
                            </select>
                        </div>

                        <!-- Recherche par titre -->
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700">🔍 Rechercher</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Titre de la note..." 
                                   class="w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if(request()->has('tag') || request()->has('sort') || request()->has('search'))
                            <a href="{{ route('courses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Réinitialiser
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @php
                // Appliquer les filtres
                $filteredCourses = $courses;
                
                // Filtre par tag
                if(request('tag')) {
                    $filteredCourses = $filteredCourses->filter(function($course) {
                        $tags = json_decode($course->tags);
                        return $tags && in_array(request('tag'), $tags);
                    });
                }
                
                // Recherche par titre
                if(request('search')) {
                    $filteredCourses = $filteredCourses->filter(function($course) {
                        return stripos($course->title, request('search')) !== false;
                    });
                }
                
                // Tri
                switch(request('sort', 'recent')) {
                    case 'oldest':
                        $filteredCourses = $filteredCourses->sortBy('created_at');
                        break;
                    case 'title':
                        $filteredCourses = $filteredCourses->sortBy('title');
                        break;
                    default:
                        $filteredCourses = $filteredCourses->sortByDesc('created_at');
                }
            @endphp

            @if($filteredCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($filteredCourses as $course)
                        @php
                            $tags = json_decode($course->tags);
                            $firstTag = $tags[0] ?? 'default';
                            $colors = [
                                '#22d3ee', '#f97316', '#10b981', '#a855f7', 
                                '#ec4899', '#f59e0b', '#3b82f6', '#ef4444'
                            ];
                            $colorIndex = abs(crc32($firstTag)) % count($colors);
                            $cardColor = $colors[$colorIndex];
                        @endphp
                        
                        <div class="bg-white rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 relative group" 
                             style="border: 3px solid {{ $cardColor }}; box-shadow: 0 4px 12px {{ $cardColor }}40;">
                            
                            <!-- Bande de couleur -->
                            <div class="h-2" style="background: {{ $cardColor }};"></div>
                            
                            <!-- Zone cliquable (toute la carte) -->
                            <a href="{{ route('courses.show', $course) }}" class="block p-6">
                                <h3 class="font-bold text-xl mb-3 text-gray-800 group-hover:text-blue-600 transition">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ Str::limit($course->content, 150) }}
                                </p>
                                
                                @if($tags && count($tags) > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($tags as $tag)
                                            @php
                                                $tagColorIndex = abs(crc32($tag)) % count($colors);
                                                $tagColor = $colors[$tagColorIndex];
                                            @endphp
                                            <span class="text-xs px-3 py-1 rounded-full font-semibold" 
                                                  style="background: {{ $tagColor }}20; color: {{ $tagColor }}; border: 1px solid {{ $tagColor }};">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="text-sm text-gray-500">
                                    📅 {{ $course->created_at->format('d/m/Y') }}
                                </div>
                            </a>

                            <!-- Boutons actions (au-dessus de la zone cliquable) -->
                            <div class="px-6 pb-6 flex gap-2 relative z-10">
                                <a href="{{ route('courses.edit', $course) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-sm text-center transition"
                                   onclick="event.stopPropagation();">
                                    ✏️ Modifier
                                </a>
                                <form method="POST" action="{{ route('courses.destroy', $course) }}" 
                                      onsubmit="event.stopPropagation(); return confirm('Supprimer cette note ?');" 
                                      class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm transition">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">📚</div>
                    @if(request()->has('tag') || request()->has('search'))
                        <p class="text-gray-500 text-lg mb-4">Aucune note ne correspond à vos critères.</p>
                        <a href="{{ route('courses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded">
                            Voir toutes les notes
                        </a>
                    @else
                        <p class="text-gray-500 text-lg mb-4">Aucune note de cours pour le moment.</p>
                        <a href="{{ route('courses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">
                            Créer ma première note
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>