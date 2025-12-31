<?php

namespace Database\Factories;

use App\Models\noteabs;
use App\Models\etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteabsFactory extends Factory
{
    protected $model = noteabs::class;

    public function definition(): array
    {
        return [
            'Id_note' => 'NOTE' . str_pad(fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'note' => fake()->randomFloat(2, 0, 20),
            'remarque' => fake()->optional(0.4)->sentence(),
            'id_etudiant' => null, 
        ];
    }

    public function forEtudiant(etudiant $etudiant): static
    {
        return $this->state(fn (array $attributes) => [
            'id_etudiant' => $etudiant->id_etudiant,
        ]);
    }
}
