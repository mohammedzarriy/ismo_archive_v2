@extends('adminlte::page')

@section('title', 'Fiche stagiaire')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-user"></i>
            {{ $trainee->last_name }} {{ $trainee->first_name }}
        </h1>
        <div>
            @if(!$trainee->validation)
                <a href="{{ route('validations.create', $trainee) }}"
                   class="btn btn-success mr-2">
                    <i class="fas fa-check-double"></i> Validation finale
                </a>
            @else
                <a href="{{ route('validations.show', $trainee) }}"
                   class="btn btn-success mr-2">
                    <i class="fas fa-check-circle"></i> Voir validation
                    <span class="badge badge-light">
                        {{ $trainee->validation->date_validation->format('d/m/Y') }}
                    </span>
                </a>
            @endif
            <a href="{{ route('trainees.edit', $trainee) }}"
               class="btn btn-warning mr-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
@stop

@section('content')

{{-- Badge validation en haut --}}
@if($trainee->validation)
<div class="alert alert-success">
    <i class="fas fa-check-double"></i>
    <strong>Stagiaire validé le {{ $trainee->validation->date_validation->format('d/m/Y') }}</strong>
    — par {{ $trainee->validation->user->name }}
</div>
@endif

<div class="row">

    {{-- Colonne gauche: Info stagiaire --}}
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body text-center">
                @if($trainee->image_profile)
                    <img src="{{ asset('storage/' . $trainee->image_profile) }}"
                         class="img-fluid rounded-circle mb-3"
                         style="width:120px;height:120px;object-fit:cover"
                         alt="Photo">
                @else
                    <div class="bg-secondary rounded-circle d-inline-flex
                                align-items-center justify-content-center mb-3"
                         style="width:120px;height:120px">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                @endif
                <h4>{{ $trainee->last_name }} {{ $trainee->first_name }}</h4>
                <p class="text-muted mb-0">{{ $trainee->filiere?->nom_filiere ?? '—' }}</p>
                <p class="text-muted">
                    <small>{{ $trainee->filiere?->secteur?->nom_secteur ?? '—' }}</small>
                </p>

                @if($trainee->validation)
                    <span class="badge badge-success badge-lg p-2">
                        <i class="fas fa-check-double"></i> VALIDÉ
                    </span>
                @else
                    <span class="badge badge-secondary badge-lg p-2">
                        <i class="fas fa-clock"></i> En cours
                    </span>
                @endif
            </div>
            <div class="card-footer p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <th class="pl-3">CIN</th>
                        <td>{{ $trainee->cin }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">CEF</th>
                        <td>{{ $trainee->cef ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Date naissance</th>
                        <td>
                            {{ $trainee->date_naissance
                                ? \Carbon\Carbon::parse($trainee->date_naissance)->format('d/m/Y')
                                : '—' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="pl-3">Téléphone</th>
                        <td>
                            @if($trainee->phone)
                                <a href="tel:{{ $trainee->phone }}">{{ $trainee->phone }}</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="pl-3">Groupe</th>
                        <td>{{ $trainee->group }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Promotion</th>
                        <td>{{ $trainee->graduation_year }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @php
            $extraRows = [
                ['id_inscription_session_programme', 'ID inscription session', $trainee->id_inscription_session_programme],
                ['matricule_etudiant', 'Matricule étudiant', $trainee->matricule_etudiant],
                ['sexe', 'Sexe', $trainee->sexe],
                ['etudiant_actif', 'Étudiant actif', $trainee->etudiant_actif === null ? null : ($trainee->etudiant_actif ? 'Oui' : 'Non')],
                ['diplome', 'Diplôme', $trainee->diplome],
                ['principale', 'Principale', $trainee->principale === null ? null : ($trainee->principale ? 'Oui' : 'Non')],
                ['libelle_long', 'Libellé long', $trainee->libelle_long],
                ['code_diplome', 'Code diplôme', $trainee->code_diplome],
                ['inscription_code', 'Code', $trainee->inscription_code],
                ['etudiant_payant', 'Étudiant payant', $trainee->etudiant_payant === null ? null : ($trainee->etudiant_payant ? 'Oui' : 'Non')],
                ['code_diplome_1', 'Code diplôme (1)', $trainee->code_diplome_1],
                ['prenom_2', 'Prénom 2', $trainee->prenom_2],
                ['site', 'Site', $trainee->site],
                ['regime_inscription', 'Régime inscription', $trainee->regime_inscription],
                ['date_inscription', 'Date inscription', $trainee->date_inscription?->format('d/m/Y')],
                ['date_dossier_complet', 'Date dossier complet', $trainee->date_dossier_complet?->format('d/m/Y')],
                ['lieu_naissance', 'Lieu naissance', $trainee->lieu_naissance],
                ['motif_admission', 'Motif admission', $trainee->motif_admission],
                ['tel_tuteur', 'Tél. tuteur', $trainee->tel_tuteur],
                ['adresse', 'Adresse', $trainee->adresse],
                ['nationalite', 'Nationalité', $trainee->nationalite],
                ['annee_etude', 'Année étude', $trainee->annee_etude],
                ['nom_arabe', 'Nom (arabe)', $trainee->nom_arabe],
                ['prenom_arabe', 'Prénom (arabe)', $trainee->prenom_arabe],
                ['niveau_scolaire', 'Niveau scolaire', $trainee->niveau_scolaire],
            ];
            $hasExtra = collect($extraRows)->contains(fn ($r) => filled($r[2]));
        @endphp
        @if($hasExtra)
        <div class="card card-outline card-secondary mt-3">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-id-card"></i> Données inscription (import)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <tbody>
                        @foreach($extraRows as [$key, $label, $val])
                            @continue(!filled($val))
                            <tr>
                                <th class="pl-3 text-nowrap" style="width:40%">{{ $label }}</th>
                                <td>{{ $val }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Colonne droite --}}
    <div class="col-md-8">

        {{-- État des documents --}}
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">
                    <i class="fas fa-folder"></i> État des documents
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @foreach(['Bac','Diplome','Attestation','Bulletin'] as $type)
                    @php
                        $doc = $trainee->documents->where('type', $type)->first();
                    @endphp
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded
                            {{ !$doc ? 'border-danger bg-light' :
                               (in_array($doc->status,['Final_Out','Remis'])
                                   ? 'border-success bg-light'
                                   : ($doc->status == 'Temp_Out'
                                       ? 'border-warning bg-light'
                                       : 'border-secondary bg-light')) }}">
                            @if(!$doc)
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                                <p class="mb-0 mt-1 font-weight-bold text-danger">{{ $type }}</p>
                                <small class="text-muted">Non enregistré</small>
                            @elseif(in_array($doc->status, ['Final_Out','Remis']))
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                                <p class="mb-0 mt-1 font-weight-bold text-success">{{ $type }}</p>
                                <small class="text-success">Remis</small>
                            @elseif($doc->status == 'Temp_Out')
                                <i class="fas fa-clock fa-2x text-warning"></i>
                                <p class="mb-0 mt-1 font-weight-bold text-warning">{{ $type }}</p>
                                <small class="text-warning">Retrait temp.</small>
                            @else
                                <i class="fas fa-archive fa-2x text-secondary"></i>
                                <p class="mb-0 mt-1 font-weight-bold text-secondary">{{ $type }}</p>
                                <small class="text-muted">En stock</small>
                            @endif

                            @if($doc)
                                <br>
                                <a href="{{ route('documents.show', $doc) }}"
                                   class="btn btn-xs btn-outline-primary mt-1"
                                   style="font-size:11px">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            @else
                                <br>
                                <a href="{{ route('documents.create') }}?trainee_id={{ $trainee->id }}"
                                   class="btn btn-xs btn-outline-danger mt-1"
                                   style="font-size:11px">
                                    <i class="fas fa-plus"></i> Ajouter
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Historique des mouvements --}}
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title text-white">
                    <i class="fas fa-history"></i> Historique des mouvements
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Action</th>
                            <th>Par</th>
                            <th>Date</th>
                            <th>Observations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainee->documents->flatMap->movements->sortByDesc('date_action') as $mv)
                        <tr>
                            <td>
                                <span class="badge badge-primary">
                                    {{ $mv->document->type ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($mv->action_type == 'Saisie')
                                    <span class="badge badge-info">Saisie</span>
                                @elseif($mv->action_type == 'Sortie')
                                    <span class="badge badge-warning">Sortie</span>
                                @else
                                    <span class="badge badge-success">Retour</span>
                                @endif
                            </td>
                            <td>{{ $mv->user->name ?? '—' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($mv->date_action)->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $mv->observations ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Aucun mouvement enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@stop