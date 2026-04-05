@extends('adminlte::page')

@section('title', 'Importer stagiaires')

@section('content_header')
    <h1><i class="fas fa-file-excel"></i> Importer depuis Excel</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="alert alert-info mb-3">
            <strong>En-têtes Excel attendues (ligne 1)</strong> — correspondance automatique (casse / accents tolérés) :
            <ul class="mb-0 mt-2 small">
                <li><code>id_inscriptionsessionprogramme</code>, <code>MatriculeEtudiant</code>, <code>Nom</code>, <code>Prenom</code>, <code>Sexe</code>, <code>EtudiantActif</code></li>
                <li><code>diplome</code>, <code>Principale</code>, <code>LibelleLong</code>, <code>CodeDiplome</code>, <code>Code</code>, <code>EtudiantPayant</code>, <code>codediplome1</code>, <code>prenom2</code></li>
                <li><code>DateNaissance</code>, <code>Site</code>, <code>Regimeinscription</code>, <code>DateInscription</code>, <code>DateDossierComplet</code>, <code>LieuNaissance</code>, <code>MotifAdmission</code></li>
                <li><code>CIN</code>, <code>NTelelephone</code>, <code>NTel_du_Tuteur</code>, <code>Adresse</code>, <code>Nationalite</code>, <code>anneeEtude</code></li>
                <li><code>Nom_Arabe</code>, <code>Prenom_arabe</code>, <code>NiveauScolaire</code></li>
                <li class="mt-1">Optionnel : <code>filiere</code> / <code>CodeDiplome</code> = code filière (table <code>filieres.code_filiere</code>), <code>groupe</code>, <code>cef</code>, <code>annee</code> (promotion)</li>
            </ul>
        </div>

        <form action="{{ route('trainees.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Fichier Excel <span class="text-danger">*</span></label>
                <input type="file" name="file"
                       class="form-control @error('file') is-invalid @enderror"
                       accept=".xlsx,.xls,.csv" required>
                @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-upload"></i> Importer
            </button>
            <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </form>
    </div>
</div>
@stop
