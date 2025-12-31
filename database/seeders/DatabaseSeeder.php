<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\etudiant;
use App\Models\absence;
use App\Models\noteabs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        
        $etudiants = etudiant::factory(50)->create();

        
        foreach ($etudiants as $etudiant) {
        
            $nombreAbsences = rand(0, 15);
            
            for ($i = 0; $i < $nombreAbsences; $i++) {
                absence::factory()
                    ->forEtudiant($etudiant)
                    ->create();
            }

           
            $noteCalculee = $etudiant->calculerNoteAbsence();
            
            noteabs::factory()
                ->forEtudiant($etudiant)
                ->create([
                    'note' => $noteCalculee,
                    'remarque' => $noteCalculee < 10 
                        ? 'Attention: trop d\'absences non justifiées' 
                        : null
                ]);
        }
    }
}
