<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Filiere;

class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $filieres = [
        ['secteur_id' => 1, 'code_filiere' => 'DD',  'nom_filiere' => 'Développement Digital',         'niveau' => 'TS'],
        ['secteur_id' => 1, 'code_filiere' => 'IDO', 'nom_filiere' => 'Infrastructure Digitale',        'niveau' => 'TS'],
        ['secteur_id' => 1, 'code_filiere' => 'IDO', 'nom_filiere' => 'Intelligence Artificielle',        'niveau' => 'TS'],
        ['secteur_id' => 2, 'code_filiere' => 'IPP',  'nom_filiere' => 'Infographie Prépresse',        'niveau' => 'T'],
    ];

    foreach ($filieres as $filiere) {
        Filiere::create($filiere);
    }
}
}
