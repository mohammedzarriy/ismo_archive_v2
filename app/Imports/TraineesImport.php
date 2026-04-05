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
    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row)
    {
        $extId = $this->pick($row, ['id_inscriptionsessionprogramme', 'id_inscription_session_programme']);
        $cin = $this->pick($row, ['cin', 'c_i_n']);
        $matricule = $this->pick($row, ['matriculeetudiant', 'matricule_etudiant']);

        if ($extId === null && $cin === null && $matricule === null) {
            return null;
        }

        $nom = $this->pick($row, ['nom', 'last_name', 'lastname']);
        $prenom = $this->pick($row, ['prenom', 'first_name', 'firstname']);

        if ($nom === null && $prenom === null) {
            return null;
        }

        $filiereCode = $this->pick($row, ['codediplome', 'code_diplome', 'filiere', 'code_filiere']);
        $filiere = $filiereCode
            ? Filiere::where('code_filiere', $filiereCode)->first()
            : null;
        $filiereId = $filiere?->id ?? Filiere::query()->orderBy('id')->value('id');
        if ($filiereId === null) {
            return null;
        }

        if ($cin === null) {
            $cin = $matricule !== null ? 'M-'.preg_replace('/\s+/', '', (string) $matricule) : 'EXT-'.preg_replace('/\W/', '', (string) $extId);
        }

        $group = $this->pick($row, ['groupe', 'group']) ?? '—';
        $gradYear = $this->resolveGraduationYear($row);

        $attributes = [
            'filiere_id' => $filiereId,
            'cin' => $cin,
            'cef' => $this->pick($row, ['cef']),
            'first_name' => $prenom ?? '—',
            'last_name' => $nom ?? '—',
            'group' => (string) $group,
            'graduation_year' => $gradYear,
            'date_naissance' => $this->parseDate($this->pick($row, ['datenaissance', 'date_naissance', 'date_de_naissance'])),
            'phone' => $this->pick($row, ['ntelelephone', 'n_telephone', 'telephone', 'phone']),

            'id_inscription_session_programme' => $extId !== null ? (string) $extId : null,
            'matricule_etudiant' => $matricule !== null ? (string) $matricule : null,
            'sexe' => $this->pick($row, ['sexe', 'gender']),
            'etudiant_actif' => $this->parseBool($this->pick($row, ['etudiantactif', 'etudiant_actif'])),
            'diplome' => $this->pick($row, ['diplome']),
            'principale' => $this->parseBool($this->pick($row, ['principale'])),
            'libelle_long' => $this->pick($row, ['libellelong', 'libelle_long']),
            'code_diplome' => $this->pick($row, ['codediplome', 'code_diplome']),
            'inscription_code' => $this->pick($row, ['code']),
            'etudiant_payant' => $this->parseBool($this->pick($row, ['etudiantpayant', 'etudiant_payant'])),
            'code_diplome_1' => $this->pick($row, ['codediplome1', 'code_diplome_1']),
            'prenom_2' => $this->pick($row, ['prenom2', 'prenom_2']),
            'site' => $this->pick($row, ['site']),
            'regime_inscription' => $this->pick($row, ['regimeinscription', 'regime_inscription']),
            'date_inscription' => $this->parseDate($this->pick($row, ['dateinscription', 'date_inscription'])),
            'date_dossier_complet' => $this->parseDate($this->pick($row, ['datedossiercomplet', 'date_dossier_complet'])),
            'lieu_naissance' => $this->pick($row, ['lieunaissance', 'lieu_naissance']),
            'motif_admission' => $this->pick($row, ['motifadmission', 'motif_admission']),
            'tel_tuteur' => $this->pick($row, ['ntel_du_tuteur', 'tel_tuteur', 'n_tel_du_tuteur']),
            'adresse' => $this->pick($row, ['adresse', 'address']),
            'nationalite' => $this->pick($row, ['nationalite']),
            'annee_etude' => $this->pick($row, ['anneeetude', 'annee_etude', 'annee']),
            'nom_arabe' => $this->pick($row, ['nom_arabe', 'nomarabe']),
            'prenom_arabe' => $this->pick($row, ['prenom_arabe', 'prenom_arabe']),
            'niveau_scolaire' => $this->pick($row, ['niveauscolaire', 'niveau_scolaire']),
        ];

        $trainee = null;
        if ($extId !== null) {
            $trainee = Trainee::query()
                ->where('id_inscription_session_programme', (string) $extId)
                ->first();
        }
        if ($trainee === null && $cin !== null) {
            $trainee = Trainee::query()->where('cin', (string) $cin)->first();
        }
        if ($trainee === null && $matricule !== null) {
            $trainee = Trainee::query()->where('matricule_etudiant', (string) $matricule)->first();
        }

        if ($trainee !== null) {
            $trainee->fill($attributes);
            $trainee->save();
        } else {
            Trainee::query()->create($attributes);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function pick(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $row)) {
                continue;
            }
            $v = $row[$key];
            if ($v === null || $v === '') {
                continue;
            }
            if (is_numeric($v) && ! is_string($v)) {
                return (string) $v;
            }

            return is_string($v) ? trim($v) : trim((string) $v);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function resolveGraduationYear(array $row): int
    {
        $y = $this->pick($row, ['annee', 'anneeetude', 'annee_etude', 'promotion', 'graduation_year']);
        if ($y === null) {
            return (int) date('Y');
        }
        if (preg_match('/(\d{4})/', $y, $m)) {
            return (int) $m[1];
        }

        return (int) date('Y');
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }
        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseBool(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_bool($value)) {
            return $value;
        }
        $v = strtolower(trim((string) $value));

        return match ($v) {
            '1', 'true', 'oui', 'yes', 'o', 'y' => true,
            '0', 'false', 'non', 'no', 'n' => false,
            default => null,
        };
    }
}
