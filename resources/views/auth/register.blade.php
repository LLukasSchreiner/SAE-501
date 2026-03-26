<x-guest-layout>
    <div class="h-fit rounded-xl flex items-center justify-center p-4" style="background: #0f1419;">
        <div class="w-full max-w-sm">
            
            <h2 class="text-3xl font-bold text-white text-center mb-8">Inscription</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Nom</label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           required 
                           autofocus
                           class="w-full bg-gray-900 border border-gray-800 text-white rounded-lg px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500 transition" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required
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

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-2">Confirmer</label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation"
                           required
                           class="w-full bg-gray-900 border border-gray-800 text-white rounded-lg px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500 transition" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-semibold py-3 rounded-lg transition transform hover:scale-105">
                    Créer mon compte
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-400">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold">
                    Se connecter
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>