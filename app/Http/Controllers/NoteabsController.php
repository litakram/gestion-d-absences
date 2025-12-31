<?php

namespace App\Http\Controllers;

use App\Models\noteabs;
use App\Models\etudiant;
use Illuminate\Http\Request;

class NoteabsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = noteabs::with('etudiant');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Id_note', 'LIKE', "%{$search}%")
                  ->orWhere('note', 'LIKE', "%{$search}%")
                  ->orWhere('remarque', 'LIKE', "%{$search}%")
                  ->orWhereHas('etudiant', function($q) use ($search) {
                      $q->where('nom', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%")
                        ->orWhere('id_etudiant', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $noteabs = $query->paginate(20)->appends(['search' => $request->search]);
        return view('noteabs.index', compact('noteabs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('noteabs.index')
            ->with('info', 'Les notes d\'absence sont générées automatiquement.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Les notes sont créées automatiquement via les absences
        return redirect()->route('noteabs.index')
            ->with('info', 'Les notes d\'absence sont générées automatiquement.');
    }

    /**
     * Display the specified resource.
     */
    public function show(noteabs $noteab)
    {
        $noteab->load('etudiant.absences');
        return view('noteabs.show', compact('noteab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(noteabs $noteab)
    {
        return view('noteabs.edit', compact('noteab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, noteabs $noteab)
    {
        $validated = $request->validate([
            'remarque' => 'nullable|string',
        ]);

        $etudiant = $noteab->etudiant;
        $noteCalculee = $etudiant->calculerNoteAbsence();
        
        $noteab->update([
            'note' => $noteCalculee,
            'remarque' => $validated['remarque'],
        ]);

        return redirect()->route('noteabs.show', $noteab->Id_note)
            ->with('success', 'Remarque mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(noteabs $noteab)
    {
        return redirect()->route('noteabs.index')
            ->with('error', 'Les notes d\'absence ne peuvent pas être supprimées directement.');
    }
}
