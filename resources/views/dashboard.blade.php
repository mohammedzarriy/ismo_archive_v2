@extends('adminlte::page')

@section('title', 'Tableau de bord | ISMO')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord</h1>
        <span class="text-muted">{{ now()->format('d/m/Y') }}</span>
    </div>
@stop

@section('content')

{{-- 🔴 Alerte Bac dépassé --}}
@if($stats['bac_expired'] > 0)
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5><i class="fas fa-exclamation-triangle"></i> Attention !</h5>
    <strong>{{ $stats['bac_expired'] }}</strong> stagiaire(s) ont dépassé le délai de 48h
    pour le retour du Baccalauréat.
    <a href="{{ url('documents/bac/temp-out') }}" class="btn btn-sm btn-danger ml-2">
        <i class="fas fa-eye"></i> Voir la liste
    </a>
</div>
@endif

{{-- Stats Cards --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_stagiaires'] }}</h3>
                <p>Total Stagiaires</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ url('trainees') }}" class="small-box-footer">
                Voir tout <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['bac_temp_out'] }}</h3>
                <p>Bac — Retrait temporaire</p>
            </div>
            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            <a href="{{ url('documents/bac/temp-out') }}" class="small-box-footer">
                Voir tout <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['diplomes_prets'] }}</h3>
                <p>Diplômes prêts</p>
            </div>
            <div class="icon"><i class="fas fa-certificate"></i></div>
            <a href="{{ url('documents/diplome') }}" class="small-box-footer">
                Voir tout <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['mouvements_today'] }}</h3>
                <p>Mouvements aujourd'hui</p>
            </div>
            <div class="icon"><i class="fas fa-exchange-alt"></i></div>
            <a href="{{ url('movements/today') }}" class="small-box-footer">
                Voir tout <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Alertes Bac --}}
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Alertes — Bac non retourné
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Stagiaire</th>
                            <th>CIN</th>
                            <th>Filière</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bac_alerts as $doc)
                        <tr>
                            <td>{{ $doc->trainee->first_name }} {{ $doc->trainee->last_name }}</td>
                            <td>{{ $doc->trainee->cin }}</td>
                            <td>{{ $doc->trainee->filiere->code_filiere ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-success">
                                <i class="fas fa-check-circle mr-1"></i>Aucune alerte
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Derniers Mouvements --}}
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Derniers mouvements
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Stagiaire</th>
                            <th>Document</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_movements as $mov)
                        <tr>
                            <td>{{ $mov->document->trainee->first_name ?? '-' }}</td>
                            <td>{{ $mov->document->type ?? '-' }}</td>
                            <td>
                                @if($mov->action_type == 'Sortie')
                                    <span class="badge badge-warning">Sortie</span>
                                @elseif($mov->action_type == 'Retour')
                                    <span class="badge badge-success">Retour</span>
                                @else
                                    <span class="badge badge-info">Saisie</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($mov->date_action)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun mouvement</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    /* AdminLTE small-box: keep icon positioned and visible (fixed layout can confuse stacking) */
    .small-box {
        position: relative;
        overflow: hidden;
    }
    .small-box .inner {
        position: relative;
        z-index: 1;
    }
    .small-box .icon {
        position: absolute;
        right: 10px;
        top: 15px;
        line-height: 1;
        font-size: 70px;
        z-index: 0;
        color: rgba(0, 0, 0, 0.15);
    }
    .small-box .icon > i {
        display: inline-block;
        vertical-align: middle;
    }
</style>
@stop