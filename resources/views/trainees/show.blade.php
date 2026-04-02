@extends('adminlte::page')

@section('title', 'Fiche stagiaire')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user"></i> {{ $trainee->last_name }} {{ $trainee->first_name }}</h1>
        <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body text-center">
                @if($trainee->image_profile)
                    <img src="{{ asset('storage/' . $trainee->image_profile) }}"
                         class="img-fluid rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover">
                @else
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center
                                justify-content-center mb-3"
                         style="width:120px;height:120px">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                @endif
                <h4>{{ $trainee->last_name }} {{ $trainee->first_name }}</h4>
                <p class="text-muted">{{ $trainee->filiere->nom_filiere }}</p>
            </div>
            <div class="card-footer">
                <table class="table table-sm">
                    <tr><th>CIN</th><td>{{ $trainee->cin }}</td></tr>
                    <tr><th>Secteur</th><td>{{ $trainee->filiere->secteur->nom_secteur }}</td></tr>
                    <tr><th>Groupe</th><td>{{ $trainee->group }}</td></tr>
                    <tr><th>Promotion</th><td>{{ $trainee->graduation_year }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">
                    <i class="fas fa-folder"></i> Documents
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Référence</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainee->documents as $doc)
                        <tr>
                            <td>{{ $doc->type }}</td>
                            <td>{{ $doc->reference_number ?? '—' }}</td>
                            <td>
                                @if($doc->status == 'Stock')
                                    <span class="badge badge-success">En stock</span>
                                @elseif($doc->status == 'Temp_Out')
                                    <span class="badge badge-warning">Retrait temporaire</span>
                                @elseif($doc->status == 'Final_Out')
                                    <span class="badge badge-danger">Retrait définitif</span>
                                @else
                                    <span class="badge badge-info">Remis</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('documents.show', $doc) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Aucun document enregistré
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