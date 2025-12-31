<?php

namespace App\Http\Controllers;

use App\Models\absence;
use App\Models\etudiant;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = absence::with('etudiant')->latest('date_absence');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Id_Absence', 'LIKE', "%{$search}%")
                  ->orWhere('date_absence', 'LIKE', "%{$search}%")
                  ->orWhere('seance', 'LIKE', "%{$search}%")
                  ->orWhereHas('etudiant', function($q) use ($search) {
                      $q->where('nom', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%")
                        ->orWhere('id_etudiant', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $absences = $query->paginate(20)->appends(['search' => $request->search]);
        return view('absences.index', compact('absences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $etudiants = etudiant::orderBy('nom')->orderBy('prenom')->get();
        return view('absences.create', compact('etudiants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_Absence' => 'required|string|max:15|unique:absences,Id_Absence',
            'date_absence' => 'required|date',
            'seance' => 'required|integer|min:1|max:4',
            'justifie' => 'boolean',
            'id_etudiant' => 'required|string|exists:etudiants,id_etudiant',
        ]);

        $validated['justifie'] = $request->has('justifie');

        $absence = absence::create($validated);

        return redirect()->route('absences.show', $absence->Id_Absence)
            ->with('success', 'Absence enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(absence $absence)
    {
        $absence->load('etudiant');
        return view('absences.show', compact('absence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(absence $absence)
    {
        $etudiants = etudiant::orderBy('nom')->orderBy('prenom')->get();
        return view('absences.edit', compact('absence', 'etudiants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, absence $absence)
    {
        $validated = $request->validate([
            'date_absence' => 'required|date',
            'seance' => 'required|integer|min:1|max:4',
            'justifie' => 'boolean',
            'id_etudiant' => 'required|string|exists:etudiants,id_etudiant',
        ]);

        $validated['justifie'] = $request->has('justifie');

        $absence->update($validated);

        return redirect()->route('absences.show', $absence->Id_Absence)
            ->with('success', 'Absence modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(absence $absence)
    {
        $absence->delete();

        return redirect()->route('absences.index')
            ->with('success', 'Absence supprimée avec succès.');
    }
}
