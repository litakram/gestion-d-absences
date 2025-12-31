<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class etudiant extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_etudiant';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_etudiant',
        'nom',
        'prenom',
        'filiere',
    ];

    public function absences(): HasMany 
    {
        return $this->hasMany(absence::class, 'id_etudiant', 'id_etudiant');
    }

    public function noteabs(): HasOne 
    {
        return $this->hasOne(noteabs::class, 'id_etudiant', 'id_etudiant');
    }

    public function calculerNoteAbsence()
    {
        $nbAbsences = $this->absences()
            ->where('justifie', false)
            ->count();
            $note = 20 - ($nbAbsences * 0.25);
        return max($note, 0);
    }
}
