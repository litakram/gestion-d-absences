<?php

namespace Database\Factories;

use App\Models\etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

class EtudiantFactory extends Factory
{
    protected $model = etudiant::class;

    public function definition(): array
    {
        return [
            'id_etudiant' => 'ETU' . str_pad(fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'filiere' => fake()->randomElement(['Developpement Digital', 'Génie Civil', 'Électronique', 'Mécanique', 'Gestion']),
        ];
    }
}
