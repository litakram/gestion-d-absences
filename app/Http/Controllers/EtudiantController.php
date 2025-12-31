<?php

namespace App\Http\Controllers;

use App\Models\etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = etudiant::with('noteabs');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_etudiant', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('filiere', 'LIKE', "%{$search}%");
            });
        }
        
        $etudiants = $query->paginate(15)->appends(['search' => $request->search]);
        return view('etudiants.index', compact('etudiants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('etudiants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_etudiant' => 'required|string|max:15|unique:etudiants,id_etudiant',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
        ]);

        $etudiant = etudiant::create($validated);

        return redirect()->route('etudiants.show', $etudiant->id_etudiant)
            ->with('success', 'Étudiant créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(etudiant $etudiant)
    {
        $etudiant->load(['absences', 'noteabs']);
        $noteCalculee = $etudiant->calculerNoteAbsence();
        
        return view('etudiants.show', compact('etudiant', 'noteCalculee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(etudiant $etudiant)
    {
        return view('etudiants.edit', compact('etudiant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, etudiant $etudiant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
        ]);

        $etudiant->update($validated);

        return redirect()->route('etudiants.show', $etudiant->id_etudiant)
            ->with('success', 'Étudiant modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(etudiant $etudiant)
    {
        $etudiant->delete();

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }
}
