<x-guest-layout>
    <div class="h-fit rounded-xl flex items-center justify-center p-4" style="background: #0f1419;">
        <div class="w-full max-w-sm">
            
            <h2 class="text-3xl font-bold text-white text-center mb-8">Connexion</h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autofocus
                           class="w-full bg-gray-900 border border-gray-800 text-white rounded-lg px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500 transition" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Mot de passe</label>
                    <input id="password" 
                           type="password" 
                           name="password"
                           required
                           class="w-full bg-gray-900 border border-gray-800 text-white rounded-lg px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500 transition" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               name="remember"
                               class="rounded bg-gray-900 border-gray-800 text-cyan-600 focus:ring-cyan-500">
                        <span class="ms-2 text-sm text-gray-400">Se souvenir</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-cyan-400 hover:text-cyan-300" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-semibold py-3 rounded-lg transition transform hover:scale-105">
                    Se connecter
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-400">
                Pas de compte ?
                <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold">
                    S'inscrire
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>