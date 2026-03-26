<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📌 {{ $deadline->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('deadlines.edit', $deadline) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Modifier
                </a>
                <form method="POST" action="{{ route('deadlines.destroy', $deadline) }}" onsubmit="return confirm('Supprimer cette deadline ?');">
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
                $typeColors = [
                    'exam' => '#ef4444',
                    'project' => '#3b82f6',
                    'assignment' => '#f97316',
                    'other' => '#6b7280',
                ];
                $deadlineColor = $typeColors[$deadline->type] ?? '#6b7280';
                $isPast = \Carbon\Carbon::parse($deadline->due_date)->isPast();
            @endphp

            <div class="bg-white rounded-lg overflow-hidden" style="border: 4px solid {{ $deadlineColor }}; box-shadow: 0 8px 24px {{ $deadlineColor }}40;">
                
                <div class="h-3" style="background: {{ $deadlineColor }};"></div>
                
                <div class="p-8">
                    
                    <div class="mb-6">
                        <span class="px-4 py-2 rounded-full text-sm font-bold" 
                              style="background: {{ $deadlineColor }}20; color: {{ $deadlineColor }}; border: 2px solid {{ $deadlineColor }};">
                            {{ strtoupper($deadline->type) }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-bold text-lg mb-2">📅 Date et heure</h3>
                        <p class="text-2xl font-bold flex items-center gap-2 {{ $isPast ? 'text-red-600' : 'text-gray-800' }}">
                            {{ \Carbon\Carbon::parse($deadline->due_date)->format('d/m/Y à H:i') }}
                            @if($isPast)
                                <span class="text-sm font-normal">(Passée)</span>
                            @else
                                <span class="text-sm font-normal text-gray-500">({{ \Carbon\Carbon::parse($deadline->due_date)->diffForHumans() }})</span>
                            @endif
                        </p>
                    </div>

                    @if($deadline->description)
                        <div class="mb-6">
                            <h3 class="font-bold text-lg mb-2">📝 Description</h3>
                            <p class="text-gray-700 leading-relaxed" style="white-space: pre-wrap;">{{ $deadline->description }}</p>
                        </div>
                    @endif

                    <div class="border-t pt-6 text-sm text-gray-500">
                        <p>📅 Créé le {{ $deadline->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('deadlines.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Retour au calendrier
                </a>
            </div>

        </div>
    </div>
</x-app-layout>