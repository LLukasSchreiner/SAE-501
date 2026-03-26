<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                🔔 Notifications
            </h2>
            @if(auth()->user()->unreadNotifications()->count() > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded text-sm">
                        ✓ Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <div class="bg-gray-800/70 border {{ $notification->read ? 'border-gray-700' : 'border-cyan-600' }} rounded-lg p-4 flex items-start gap-4 {{ $notification->read ? 'opacity-60' : '' }}">
                            
                            <!-- Icône selon le type -->
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                {{ $notification->type === 'task_assigned' ? 'bg-blue-600' : '' }}
                                {{ $notification->type === 'task_updated' ? 'bg-yellow-600' : '' }}
                                {{ $notification->type === 'deadline_soon' ? 'bg-red-600' : '' }}
                                {{ $notification->type === 'invitation' ? 'bg-green-600' : '' }}
                                {{ $notification->type === 'task_comment' ? 'bg-cyan-600' : '' }}
                                {{ !in_array($notification->type, ['task_assigned', 'task_updated', 'deadline_soon', 'invitation']) ? 'bg-gray-600' : '' }}">
                                @if($notification->type === 'task_assigned')
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path>
                                    </svg>
                                @elseif($notification->type === 'task_updated')
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                @elseif($notification->type === 'deadline_soon')
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($notification->type === 'invitation')
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                @elseif($notification->type === 'task_comment')
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Contenu -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="font-bold text-gray-200">{{ $notification->title }}</h3>
                                        <p class="text-sm text-gray-400 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    @if(!$notification->read)
                                        <span class="flex-shrink-0 w-2 h-2 bg-cyan-500 rounded-full"></span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2 mt-3">                                    
                                    @if(!$notification->read)
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-1 px-3 rounded text-xs transition">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="bg-gray-800/70 border border-gray-700 rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">🔔</div>
                    <p class="text-gray-400 text-lg">Aucune notification.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>