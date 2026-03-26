<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mosaic - Gestion de projets agile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(34, 211, 238, 0.2);
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-count { animation: countUp 0.6s ease-out; }
    </style>
</head>
<body style="background: #0f1419;">
    
    <!-- Navigation -->
    <nav class="border-b border-gray-800">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto fill-current text-cyan-400" />
                    <span class="font-bold text-lg text-white">Mosaic</span>
                </div>
                
                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-gray-400 hover:text-cyan-400 transition">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white px-5 py-2 rounded font-semibold transition">
                            S'inscrire
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold text-white mb-4">
                Gestion de projets <span class="text-cyan-400">Agile</span>
            </h1>

            <p class="text-lg text-gray-400 mb-8 max-w-2xl mx-auto">
                Kanban, Roadmap et Sprints pour une gestion moderne et efficace.
            </p>

            <div class="flex justify-center gap-4 mb-12">
                <a href="{{ route('register') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white px-6 py-3 rounded font-semibold transition transform hover:scale-105">
                    Commencer gratuitement
                </a>
                <a href="#features" class="border border-gray-700 hover:border-cyan-600 text-white px-6 py-3 rounded font-semibold transition">
                    En savoir plus
                </a>
            </div>

            <!-- Counter animé -->
            <div class="inline-block bg-gray-900  rounded-lg px-8 py-4 animate-count mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-left">
                        <div class="text-3xl font-bold text-cyan-400" id="projectCounter">0</div>
                        <div class="text-lg text-gray-400">projets créés</div>
                    </div>
                </div>
            </div>
    </div>

    <!-- Features -->
    <div id="features" class="py-16 bg-gray-900/50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-white text-center mb-10">Fonctionnalités complètes</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Kanban -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover-lift cursor-pointer">
                    <svg class="w-10 h-10 mb-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Kanban interactif</h3>
                    <p class="text-sm text-gray-400">Drag & drop pour une gestion visuelle.</p>
                </div>

                <!-- Roadmap -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover-lift cursor-pointer">
                    <svg class="w-10 h-10 mb-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Roadmap</h3>
                    <p class="text-sm text-gray-400">Timeline claire de vos sprints.</p>
                </div>

                <!-- Sprints -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover-lift cursor-pointer">
                    <svg class="w-10 h-10 mb-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Sprints Scrum</h3>
                    <p class="text-sm text-gray-400">Objectifs mesurables.</p>
                </div>

                <!-- Collaboration -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover-lift cursor-pointer">
                    <svg class="w-10 h-10 mb-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Collaboration</h3>
                    <p class="text-sm text-gray-400">Équipe, tâches, commentaires.</p>
                </div>

                <!-- Reporting -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover-lift cursor-pointer">
                    <svg class="w-10 h-10 mb-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Reporting</h3>
                    <p class="text-sm text-gray-400">Graphiques détaillés.</p>
                </div>

                <!-- Notifications -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover-lift cursor-pointer">
                    <svg class="w-10 h-10 mb-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Notifications</h3>
                    <p class="text-sm text-gray-400">Alertes en temps réel.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Final -->
    <div class="py-16">
        <div class="max-w-4xl mx-auto text-center px-6">
            <h2 class="text-3xl font-bold text-white mb-4">
                Prêt à transformer votre gestion de projet ?
            </h2>
            <a href="{{ route('register') }}" class="inline-block bg-cyan-600 hover:bg-cyan-500 text-white px-8 py-3 rounded font-semibold transition transform hover:scale-105">
                Créer un compte gratuitement →
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-gray-800 py-6">
        <div class="max-w-6xl mx-auto px-6 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Mosaic. Projet SAE - IUT MMI.</p>
        </div>
    </footer>

    <script>
        // Animation du compteur
        const target = 13540;
        const duration = 2000;
        const counter = document.getElementById('projectCounter');
        let current = 0;
        const increment = target / (duration / 16);

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    </script>

</body>
</html>