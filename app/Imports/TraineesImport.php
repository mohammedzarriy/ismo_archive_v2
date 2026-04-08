<?php

namespace App\Imports;

use App\Models\Filiere;
use App\Models\Trainee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TraineesImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $extId = $this->pick($row, ['id_inscriptionsessionprogramme']);
        $cin = $this->pick($row, ['cin']);
        $matricule = $this->pick($row, ['matriculeetudiant']);

        if (!$extId && !$cin && !$matricule) return null;

        $nom = $this->pick($row, ['nom']);
        $prenom = $this->pick($row, ['prenom']);

        if (!$nom && !$prenom) return null;

        $filiereCode = $this->pick($row, ['codediplome', 'filiere']);
        $filiere = $filiereCode
            ? Filiere::where('code_filiere', $filiereCode)->first()
            : null;

        $filiereId = $filiere?->id ?? 1; // default filiere

        if (!$cin) $cin = $matricule ? 'M-'.$matricule : 'EXT-'.$extId;

        $data = [
            'filiere_id' => $filiereId,
            'cin' => $cin,
            'cef' => $this->pick($row, ['cef']),
            'first_name' => $prenom,
            'last_name' => $nom,
            'group' => $this->pick($row, ['groupe']) ?? 'G1',
            'graduation_year' => $this->year($row),

            'date_naissance' => $this->date($this->pick($row, ['datenaissance'])),
            'phone' => $this->pick($row, ['ntelelephone']),
            'tel_tuteur' => $this->pick($row, ['ntel_du_tuteur']),

            'id_inscription_session_programme' => $extId,
            'matricule_etudiant' => $matricule,
            'sexe' => $this->pick($row, ['sexe']),
            'etudiant_actif' => $this->bool($this->pick($row, ['etudiantactif'])),

            'diplome' => $this->pick($row, ['diplome']),
            'principale' => $this->bool($this->pick($row, ['principale'])),
            'libelle_long' => $this->pick($row, ['libellelong']),
            'code_diplome' => $this->pick($row, ['codediplome']),
            'inscription_code' => $this->pick($row, ['code']),
            'etudiant_payant' => $this->bool($this->pick($row, ['etudiantpayant'])),

            'code_diplome_1' => $this->pick($row, ['codediplome1']),
            'prenom_2' => $this->pick($row, ['prenom2']),
            'site' => $this->pick($row, ['site']),
            'regime_inscription' => $this->pick($row, ['regimeinscription']),
            'date_inscription' => $this->date($this->pick($row, ['dateinscription'])),
            'date_dossier_complet' => $this->date($this->pick($row, ['datedossiercomplet'])),

            'lieu_naissance' => $this->pick($row, ['lieunaissance']),
            'motif_admission' => $this->pick($row, ['motifadmission']),
            'adresse' => $this->pick($row, ['adresse']),
            'nationalite' => $this->pick($row, ['nationalite']),
            'annee_etude' => $this->pick($row, ['anneeetude']),

            'nom_arabe' => $this->pick($row, ['nom_arabe']),
            'prenom_arabe' => $this->pick($row, ['prenom_arabe']),
            'niveau_scolaire' => $this->pick($row, ['niveauscolaire']),
        ];

        Trainee::updateOrCreate(['cin' => $cin], $data);

        return null;
    }

    private function pick($row, $keys)
    {
        foreach ($keys as $k) {
            if (!empty($row[$k])) return trim($row[$k]);
        }
        return null;
    }

    private function date($v)
    {
        if (!$v) return null;
        if (is_numeric($v)) return ExcelDate::excelToDateTimeObject($v)->format('Y-m-d');
        return Carbon::parse($v)->format('Y-m-d');
    }

    private function bool($v)
    {
        return in_array(strtolower($v), ['oui','yes','1']) ? 1 : 0;
    }

    private function year($row)
    {
        $y = $this->pick($row, ['annee']);
        return $y ? (int)$y : date('Y');
    }
}