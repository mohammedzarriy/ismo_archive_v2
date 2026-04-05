<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->string('id_inscription_session_programme', 80)->nullable()->unique()->after('id');
            $table->string('matricule_etudiant', 80)->nullable()->unique()->after('cef');

            $table->string('sexe', 20)->nullable()->after('last_name');
            $table->boolean('etudiant_actif')->nullable()->after('sexe');
            $table->string('diplome', 255)->nullable()->after('etudiant_actif');
            $table->boolean('principale')->nullable()->after('diplome');
            $table->string('libelle_long', 500)->nullable()->after('principale');
            $table->string('code_diplome', 100)->nullable()->after('libelle_long');
            $table->string('inscription_code', 100)->nullable()->after('code_diplome');
            $table->boolean('etudiant_payant')->nullable()->after('inscription_code');
            $table->string('code_diplome_1', 100)->nullable()->after('etudiant_payant');
            $table->string('prenom_2', 100)->nullable()->after('code_diplome_1');

            $table->string('site', 255)->nullable()->after('phone');
            $table->string('regime_inscription', 100)->nullable()->after('site');
            $table->date('date_inscription')->nullable()->after('regime_inscription');
            $table->date('date_dossier_complet')->nullable()->after('date_inscription');
            $table->string('lieu_naissance', 255)->nullable()->after('date_dossier_complet');
            $table->string('motif_admission', 255)->nullable()->after('lieu_naissance');
            $table->string('tel_tuteur', 50)->nullable()->after('motif_admission');
            $table->text('adresse')->nullable()->after('tel_tuteur');
            $table->string('nationalite', 100)->nullable()->after('adresse');
            $table->string('annee_etude', 50)->nullable()->after('nationalite');
            $table->string('nom_arabe', 150)->nullable()->after('annee_etude');
            $table->string('prenom_arabe', 150)->nullable()->after('nom_arabe');
            $table->string('niveau_scolaire', 150)->nullable()->after('prenom_arabe');
        });
    }

    public function down(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->dropColumn([
                'id_inscription_session_programme',
                'matricule_etudiant',
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
            ]);
        });
    }
};
