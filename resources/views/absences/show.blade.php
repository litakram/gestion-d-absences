<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de l\'Absence') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Informations</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">ID Absence</p>
                            <p class="font-medium">{{ $absence->Id_Absence }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Étudiant</p>
                            <p class="font-medium">
                                <a href="{{ route('etudiants.show', $absence->etudiant->id_etudiant) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $absence->etudiant->nom }} {{ $absence->etudiant->prenom }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Date</p>
                            <p class="font-medium">{{ $absence->date_absence->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Séance</p>
                            <p class="font-medium">Séance {{ $absence->seance }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Justifiée</p>
                            <p class="font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absence->justifie ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $absence->justifie ? 'Oui' : 'Non' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('absences.edit', $absence->Id_Absence) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Modifier
                        </a>
                        <a href="{{ route('absences.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
