<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteabsController;
use App\Http\Controllers\EtudiantController;  
use App\Http\Controllers\AbsenceController;  
use App\Models\etudiant;
use App\Models\absence;
use App\Models\noteabs;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalEtudiants = etudiant::count();
    $totalAbsences = absence::count();
    $absencesNonJustifiees = absence::where('justifie', false)->count();
    $etudiantsEnDifficulte = noteabs::where('note', '<', 10)->count();
    $recentAbsences = absence::with('etudiant')->latest('date_absence')->take(5)->get();
    
    return view('dashboard', compact('totalEtudiants', 'totalAbsences', 'absencesNonJustifiees', 'etudiantsEnDifficulte', 'recentAbsences'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

Route::resource('noteabs', NoteabsController::class);
Route::resource('etudiants', EtudiantController::class);
Route::resource('absences', AbsenceController::class);
