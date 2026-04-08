<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    use HasFactory;

    protected $fillable = [
        'filiere_id',
        'id_inscription_session_programme',
        'matricule_etudiant',
        'cin',
        'cef',
        'first_name',
        'last_name',
        'sexe',
        'etudiant_actif',
        'diplome',
        'principale',
        'libelle_long',
        'code_diplome',
        'inscription_code',
        'etudiant_payant',
        'code_diplome_1',
        'prenom_2',
        'date_naissance',
        'phone',
        'site',
        'regime_inscription',
        'date_inscription',
        'date_dossier_complet',
        'lieu_naissance',
        'motif_admission',
        'tel_tuteur',
        'adresse',
        'nationalite',
        'annee_etude',
        'nom_arabe',
        'prenom_arabe',
        'niveau_scolaire',
        'group',
        'graduation_year',
        'image_profile',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'date_inscription' => 'date',
            'date_dossier_complet' => 'date',
            'etudiant_actif' => 'boolean',
            'principale' => 'boolean',
            'etudiant_payant' => 'boolean',
        ];
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function validation()
    {
        return $this->hasOne(Validation::class);
    }
}
