<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📅 Mon Calendrier
            </h2>
            <a href="{{ route('deadlines.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Nouvelle Deadline
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

            @if($deadlines->count() > 0)
                <div class="space-y-4">
                    @foreach($deadlines as $deadline)
                        @php
                            $typeColors = [
                                'exam' => '#ef4444',      // rouge
                                'project' => '#3b82f6',   // bleu
                                'assignment' => '#f97316', // orange
                                'other' => '#6b7280',     // gris
                            ];
                            $deadlineColor = $typeColors[$deadline->type] ?? '#6b7280';
                            $isPast = \Carbon\Carbon::parse($deadline->due_date)->isPast();
                        @endphp
                        
                        <div class="bg-white rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 {{ $isPast ? 'opacity-60' : '' }}" 
                             style="border-left: 6px solid {{ $deadlineColor }}; box-shadow: 0 4px 12px {{ $deadlineColor }}30;">
                            
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <a href="{{ route('deadlines.show', $deadline) }}" class="block mb-2">
                                            <h3 class="font-bold text-xl text-gray-800 hover:text-blue-600 transition">
                                                {{ $deadline->title }}
                                            </h3>
                                        </a>
                                        
                                        @if($deadline->description)
                                            <p class="text-gray-600 mb-3">{{ Str::limit($deadline->description, 150) }}</p>
                                        @endif
                                        
                                        <div class="flex items-center gap-4 text-sm">
                                            <span class="flex items-center gap-2 font-semibold {{ $isPast ? 'text-red-600' : 'text-gray-700' }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($deadline->due_date)->format('d/m/Y à H:i') }}
                                            </span>
                                            
                                            <span class="px-3 py-1 rounded-full text-xs font-bold" 
                                                  style="background: {{ $deadlineColor }}20; color: {{ $deadlineColor }}; border: 2px solid {{ $deadlineColor }};">
                                                {{ strtoupper($deadline->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2 ml-4">
                                        <a href="{{ route('deadlines.edit', $deadline) }}" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-sm transition">
                                            ✏️
                                        </a>
                                        <form method="POST" action="{{ route('deadlines.destroy', $deadline) }}" 
                                              onsubmit="return confirm('Supprimer cette deadline ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm transition">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">📅</div>
                    <p class="text-gray-500 text-lg mb-4">Aucune deadline pour le moment.</p>
                    <a href="{{ route('deadlines.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">
                        Créer ma première deadline
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>