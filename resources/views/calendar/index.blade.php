<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📅 Mon Calendrier
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- LAYOUT PRINCIPAL : Gauche (formulaire) + Droite (calendrier) -->
            <div class="grid grid-cols-12 gap-6">
                
                <!-- COLONNE GAUCHE - Formulaire (4 colonnes sur 12) -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="bg-white rounded-lg p-6 sticky top-6">
                        
                        <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
                            <span class="text-2xl">📝</span>
                            Ajouter un événement
                        </h3>

                        <form id="calendar_form" method="POST" action="{{ route('calendar.store') }}">
                            @csrf
                            <input type="hidden" name="id" id="event_id">

                            <!-- Titre -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2 text-gray-700">📌 Titre *</label>
                                <input type="text" name="title" id="event_title" required
                                       placeholder="Ex: Rendu SAE501"
                                       class="w-full border rounded px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2 text-gray-700">🏷️ Type *</label>
                                <select name="type" id="event_type" required
                                        class="w-full border rounded px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500">
                                    <option value="deadline">📌 Deadline</option>
                                    <option value="exam">📝 Examen</option>
                                    <option value="project">📂 Projet (durée)</option>
                                    <option value="other">📋 Autre</option>
                                </select>
                            </div>

                            <!-- Date de début -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2 text-gray-700">📅 Date de début *</label>
                                <input type="date" name="start_date" id="event_start" required
                                       class="w-full border rounded px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Date de fin (projet uniquement) -->
                            <div id="event_end_container" class="mb-4" style="display: none;">
                                <label class="block text-sm font-bold mb-2 text-gray-700">🏁 Date de fin</label>
                                <input type="date" name="end_date" id="event_end"
                                       class="w-full border rounded px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Couleur -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2 text-gray-700">🎨 Couleur *</label>
                                <div class="grid grid-cols-4 gap-2">
                                    @php
                                        $colors = [
                                            '#ef4444' => 'Rouge',
                                            '#f97316' => 'Orange',
                                            '#f59e0b' => 'Ambre',
                                            '#10b981' => 'Vert',
                                            '#22d3ee' => 'Cyan',
                                            '#3b82f6' => 'Bleu',
                                            '#a855f7' => 'Violet',
                                            '#ec4899' => 'Rose',
                                        ];
                                    @endphp
                                    @foreach($colors as $hex => $name)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="color" value="{{ $hex }}" 
                                                   class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                                            <div class="w-full aspect-square rounded-lg border-2 border-gray-300 peer-checked:border-gray-900 peer-checked:ring-4 peer-checked:ring-offset-2 peer-checked:ring-blue-500 transition transform hover:scale-110"
                                                 style="background: {{ $hex }};"
                                                 title="{{ $name }}"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2 text-gray-700">📄 Description</label>
                                <textarea name="description" id="event_description" rows="3"
                                          placeholder="Détails optionnels..."
                                          class="w-full border rounded px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <!-- Notifications -->
                            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="notify_2_days" value="1"
                                           class="rounded w-5 h-5 text-blue-600">
                                    <span class="text-sm font-semibold text-gray-800">
                                        🔔 Me notifier 2 jours avant
                                    </span>
                                </label>
                            </div>

                            <!-- Boutons -->
                            <div class="space-y-2">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105">
                                    <span id="submit_text">➕ Ajouter l'événement</span>
                                </button>
                                <button type="button" onclick="deleteEvent()" id="delete_btn" 
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition" 
                                        style="display: none;">
                                    🗑️ Supprimer
                                </button>
                                <button type="button" onclick="resetForm()" 
                                        class="w-full bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">
                                    ↺ Nouveau
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- COLONNE DROITE - Calendrier (8 colonnes sur 12) -->
                <div class="col-span-12 lg:col-span-8">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div id="calendar"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        #calendar {
            min-height: 700px;
        }
        
        .fc {
            color: #1f2937;
            font-family: inherit;
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .fc .fc-button {
            background: #3b82f6;
            border-color: #3b82f6;
            text-transform: capitalize;
            font-weight: 600;
        }
        
        .fc .fc-button:hover {
            background: #2563eb;
            border-color: #2563eb;
        }
        
        .fc .fc-button-active {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }
        
        .fc-event {
            cursor: pointer;
            border: none;
            padding: 4px 6px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .fc-daygrid-event {
            margin: 2px 0;
        }

        .fc-event:hover {
            opacity: 0.85;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .fc-day-today {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }
    </style>
</x-app-layout>