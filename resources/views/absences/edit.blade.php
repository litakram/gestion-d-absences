<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier l\'Absence') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('absences.update', $absence->Id_Absence) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="Id_Absence" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">ID Absence</label>
                            <input type="text" value="{{ $absence->Id_Absence }}" disabled
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 dark:text-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label for="id_etudiant" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Étudiant</label>
                            <select name="id_etudiant" id="id_etudiant" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 @error('id_etudiant') border-red-500 @enderror">
                                @foreach($etudiants as $etudiant)
                                    <option value="{{ $etudiant->id_etudiant }}" {{ old('id_etudiant', $absence->id_etudiant) == $etudiant->id_etudiant ? 'selected' : '' }}>
                                        {{ $etudiant->nom }} {{ $etudiant->prenom }} ({{ $etudiant->id_etudiant }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_etudiant')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date_absence" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" name="date_absence" id="date_absence" value="{{ old('date_absence', $absence->date_absence->format('Y-m-d')) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 @error('date_absence') border-red-500 @enderror">
                            @error('date_absence')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="seance" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Séance</label>
                            <select name="seance" id="seance" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 @error('seance') border-red-500 @enderror">
                                <option value="1" {{ old('seance', $absence->seance) == 1 ? 'selected' : '' }}>Séance 1</option>
                                <option value="2" {{ old('seance', $absence->seance) == 2 ? 'selected' : '' }}>Séance 2</option>
                                <option value="3" {{ old('seance', $absence->seance) == 3 ? 'selected' : '' }}>Séance 3</option>
                                <option value="4" {{ old('seance', $absence->seance) == 4 ? 'selected' : '' }}>Séance 4</option>
                            </select>
                            @error('seance')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="justifie" value="1" {{ old('justifie', $absence->justifie) ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Absence justifiée</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('absences.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
