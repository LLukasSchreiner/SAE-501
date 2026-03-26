<x-app-layout :project="$project">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            📚 Notes liées - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4">
            <div class="bg-white rounded-lg p-6">
                <p class="text-gray-600">Aucune note liée pour le moment.</p>
            </div>
        </div>
    </div>
</x-app-layout>