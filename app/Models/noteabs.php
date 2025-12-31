<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class noteabs extends Model
{
    use HasFactory;
    protected $primaryKey = 'Id_note';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Id_note',
        'note',
        'remarque',
        'id_etudiant',
    ];

    protected $casts = [
        'note' => 'decimal:2',
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(etudiant::class, 'id_etudiant', 'id_etudiant');
    }
}
