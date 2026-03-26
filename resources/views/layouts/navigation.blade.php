<nav x-data="{ open: false }" class="border-b border-gray-700" style="background: linear-gradient(135deg, rgba(15, 20, 25, 0.95) 0%, rgba(26, 35, 50, 0.95) 100%); backdrop-filter: blur(10px);">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo + Menu principal -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current" style="color: #22d3ee;" />
                        <span class="font-bold text-lg" style="color: #22d3ee;">Mosaic</span>
                    </a>
                </div>

                <!-- Navigation principale -->
                <div class="hidden space-x-2 sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 rounded-md text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('projects.index') }}" 
                       class="px-4 py-2 rounded-md text-sm font-semibold transition {{ request()->routeIs('projects.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        Projets
                    </a>
                    
                    <a href="{{ route('courses.index') }}" 
                       class="px-4 py-2 rounded-md text-sm font-semibold transition {{ request()->routeIs('courses.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        Cours
                    </a>
                    
                    <a href="{{ route('calendar.index') }}" 
                       class="px-4 py-2 rounded-md text-sm font-semibold transition {{ request()->routeIs('calendar.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        Calendrier
                    </a>
                </div>
            </div>

            <!-- Partie droite : Invitations + Notifications + User -->
            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <!-- Invitations -->
                <a href="{{ route('invitations.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold transition {{ request()->routeIs('invitations.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <span>📬 Invitations</span>
                    @php
                        $pendingCount = \App\Models\ProjectInvitation::where('invitee_id', auth()->id())
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- Notifications -->
                <a href="{{ route('notifications.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold transition {{ request()->routeIs('notifications.*') ? 'bg-cyan-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <span>🔔 Notifications</span>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- User menu -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition ease-in-out duration-150" style="color: #9ca3af; background: transparent;">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mon Compte') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile menu button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out" style="color: #9ca3af;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background: rgba(17, 24, 39, 0.9); border-top: 1px solid rgba(34, 211, 238, 0.2);">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">Dashboard</a>
            <a href="{{ route('projects.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">Projets</a>
            <a href="{{ route('courses.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">Cours</a>
            <a href="{{ route('calendar.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">Calendrier</a>
            <a href="{{ route('invitations.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">
                📬 Invitations
                @php
                    $pendingCount = \App\Models\ProjectInvitation::where('invitee_id', auth()->id())->where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">
                🔔 Notifications
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                @endif
            </a>
        </div>
        <div class="pt-4 pb-1 border-t" style="border-color: rgba(34, 211, 238, 0.2);">
            <div class="px-4">
                <div class="font-medium text-base" style="color: #e5e7eb;">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm" style="color: #9ca3af;">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">Mon Compte</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-300 hover:bg-gray-800 rounded transition">Déconnexion</button>
                </form>
            </div>
        </div>
    </div>
</nav>