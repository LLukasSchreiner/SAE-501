<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📖 {{ $course->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('courses.edit', $course) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Modifier
                </a>
                <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Supprimer cette note ?');">
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $tags = json_decode($course->tags);
                $firstTag = $tags[0] ?? 'default';
                $colors = ['#22d3ee', '#f97316', '#10b981', '#a855f7', '#ec4899', '#f59e0b', '#3b82f6', '#ef4444'];
                $colorIndex = abs(crc32($firstTag)) % count($colors);
                $cardColor = $colors[$colorIndex];
            @endphp

            <div class="bg-white rounded-lg overflow-hidden" style="border: 4px solid {{ $cardColor }}; box-shadow: 0 8px 24px {{ $cardColor }}40;">
                
                <!-- Bande de couleur -->
                <div class="h-3" style="background: {{ $cardColor }};"></div>
                
                <div class="p-8">
                    
                    @if($tags && count($tags) > 0)
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($tags as $tag)
                                @php
                                    $tagColorIndex = abs(crc32($tag)) % count($colors);
                                    $tagColor = $colors[$tagColorIndex];
                                @endphp
                                <span class="px-4 py-2 rounded-full font-semibold" 
                                      style="background: {{ $tagColor }}20; color: {{ $tagColor }}; border: 2px solid {{ $tagColor }};">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="prose max-w-none text-gray-800" style="white-space: pre-wrap;">{{ $course->content }}</div>

                    <div class="mt-8 pt-6 border-t text-sm text-gray-500">
                        <p>📅 Créé le {{ $course->created_at->format('d/m/Y à H:i') }}</p>
                        <p>🔄 Modifié le {{ $course->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Retour -->
            <div class="mt-6">
                <a href="{{ route('courses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Retour aux notes
                </a>
            </div>

        </div>
    </div>
</x-app-layout>