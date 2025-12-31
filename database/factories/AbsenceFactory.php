<?php

namespace Database\Factories;

use App\Models\absence;
use App\Models\etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    protected $model = absence::class;

    public function definition(): array
    {
        return [
            'Id_Absence' => 'ABS' . str_pad(fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'date_absence' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'seance' => fake()->numberBetween(1, 4),
            'justifie' => fake()->boolean(30), 
            'id_etudiant' => null, 
        ];
    }

    public function forEtudiant(etudiant $etudiant): static
    {
        return $this->state(fn (array $attributes) => [
            'id_etudiant' => $etudiant->id_etudiant,
        ]);
    }

    public function justifie(): static
    {
        return $this->state(fn (array $attributes) => [
            'justifie' => true,
        ]);
    }

    public function nonJustifie(): static
    {
        return $this->state(fn (array $attributes) => [
            'justifie' => false,
        ]);
    }
}
