<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class absence extends Model
{

    use HasFactory;
    protected $primaryKey = 'Id_Absence';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Id_Absence',
        'date_absence',
        'seance',
        'justifie',
        'id_etudiant',
    ];

    protected $casts = [
        'date_absence' => 'date',
        'justifie' => 'boolean',

    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(etudiant::class, 'id_etudiant', 'id_etudiant');
    }

    protected static function booted(): void
    {
        static::created(function ($absence) {
            $absence->mettreAJourNoteAbsence();
        });

        static::updated(function ($absence) {
            $absence->mettreAJourNoteAbsence();
        });

        static::deleted(function ($absence) {
            $absence->mettreAJourNoteAbsence();
        });
    }

    protected function mettreAJourNoteAbsence(): void
    {
        $etudiant = $this->etudiant;
        if ($etudiant) {
            $noteCalculee = $etudiant->calculerNoteAbsence();
            
            $etudiant->noteabs()->updateOrCreate(
                ['id_etudiant' => $etudiant->id_etudiant],
                ['note' => $noteCalculee]
            );
        }
    }
}
