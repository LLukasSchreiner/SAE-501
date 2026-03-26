<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            📬 Mes invitations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if($invitations->count() > 0)
                <div class="space-y-4">
                    @foreach($invitations as $invitation)
                        <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">
                                    {{ substr($invitation->project->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-200 text-lg">{{ $invitation->project->name }}</h3>
                                    <p class="text-sm text-gray-400">
                                        <span class="font-semibold text-cyan-400">{{ $invitation->inviter->name }}</span> 
                                        vous invite à rejoindre ce projet en tant que 
                                        <span class="font-semibold text-cyan-400">{{ ucfirst($invitation->role) }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Expire le {{ $invitation->expires_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <form method="POST" action="{{ route('invitations.accept', $invitation) }}">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded transition">
                                        ✓ Accepter
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('invitations.decline', $invitation) }}">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded transition">
                                        ✗ Refuser
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">📬</div>
                    <p class="text-gray-400 text-lg">Aucune invitation en attente.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>