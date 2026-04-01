<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Secteur;

class SecteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $secteurs = [
        ['nom_secteur' => 'Digital et Intelligence Artificielle'],
        ['nom_secteur' => 'Arts et Industries Graphiques'],
    ];

    foreach ($secteurs as $secteur) {
        Secteur::create($secteur);
    }
}
}
