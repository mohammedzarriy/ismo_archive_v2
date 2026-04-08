@extends('adminlte::page')

@section('title', 'Tableau de bord | ISMO')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord</h1>
        <span class="text-muted">{{ now()->format('d/m/Y') }}</span>
    </div>
@stop

@section('content')

{{-- 🔴 Alerte globale --}}
@if($stats['bac_expired'] > 0)
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5><i class="fas fa-exclamation-triangle"></i> Attention</h5>
    <strong>{{ $stats['bac_expired'] }}</strong> stagiaire(s) ont dépassé le délai de 48h
    <a href="{{ url('documents/bac/temp-out') }}" class="btn btn-sm btn-danger ml-2">
        Voir la liste
    </a>
</div>
@endif

{{-- 📊 Statistiques --}}
<div class="row">

    {{-- Total stagiaires --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_stagiaires'] }}</h3>
                <p>Total des stagiaires</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ url('trainees') }}" class="small-box-footer">Voir tout</a>
        </div>
    </div>

    {{-- Bac temp --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['bac_temp_out'] }}</h3>
                <p>Bac — Temporaire</p>
            </div>
            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            <a href="{{ url('documents/bac/temp-out') }}" class="small-box-footer">Voir</a>
        </div>
    </div>

    {{-- Diplômes prêts --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['diplomes_prets'] }}</h3>
                <p>Diplômes prêts</p>
            </div>
            <div class="icon"><i class="fas fa-certificate"></i></div>
            <a href="{{ url('documents/diplome') }}" class="small-box-footer">Voir</a>
        </div>
    </div>

    {{-- Mouvements --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['mouvements_today'] }}</h3>
                <p>Mouvements aujourd'hui</p>
            </div>
            <div class="icon"><i class="fas fa-exchange-alt"></i></div>
            <a href="{{ url('movements/today') }}" class="small-box-footer">Voir</a>
        </div>
    </div>

    {{-- 🔥 Diplômes en attente (NEW) --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $stats['diplomes_en_attente'] }}</h3>
                <p>Diplômés — En attente</p>
            </div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="{{ url('diplomes/en-attente') }}" class="small-box-footer">
                Voir tout
            </a>
        </div>
    </div>

</div>

{{-- ⚠️ Alertes détaillées --}}
@if($bac_alerts->count())
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Alertes Bac (40h+)</h3>
    </div>
    <div class="card-body">
        @foreach($bac_alerts as $doc)
            <div class="alert {{ $doc->alert_level == 'ecoule' ? 'alert-danger' : 'alert-warning' }}">
                <strong>{{ $doc->trainee->first_name }} {{ $doc->trainee->last_name }}</strong>
                — {{ $doc->hours_out }}h
            </div>
        @endforeach
    </div>
</div>
@endif

<div class="row">

    {{-- Bac non retourné --}}
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Bac non retourné</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>CIN</th>
                            <th>Filière</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bac_alerts as $doc)
                        <tr>
                            <td>{{ $doc->trainee->first_name }}</td>
                            <td>{{ $doc->trainee->cin }}</td>
                            <td>{{ $doc->trainee->filiere->code_filiere ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3">Aucune alerte</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Derniers mouvements --}}
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Derniers mouvements</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Doc</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_movements as $mov)
                        <tr>
                            <td>{{ $mov->document->trainee->first_name ?? '-' }}</td>
                            <td>{{ $mov->document->type ?? '-' }}</td>
                            <td>{{ $mov->action_type }}</td>
                            <td>{{ $mov->date_action }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@stop

@section('css')
<style>
.small-box { position: relative; }
.small-box .icon {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 60px;
    opacity: 0.2;
}
</style>
@stop