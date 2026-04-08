@extends('adminlte::page')
@section('title', 'Documents écoulés — Bac')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-exclamation-triangle text-danger"></i> Bac — Documents Écoulés (>48h)</h1>
        <span class="badge badge-danger" style="font-size:14px">
            {{ $documents->total() }} écoulés
        </span>
    </div>
@stop

@section('content')

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-header bg-light">
        <h3 class="card-title"><i class="fas fa-filter"></i> Filtres</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('documents.bac.ecoule') }}">
            <div class="row">
                <div class="col-md-4">
                    <label>Filière</label>
                    <select name="filiere_id" class="form-control select2">
                        <option value="">— Toutes les filières —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id }}"
                                {{ request('filiere_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Groupe</label>
                    <select name="group" class="form-control">
                        <option value="">— Tous les groupes —</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}"
                                {{ request('group') == $g ? 'selected' : '' }}>
                                {{ $g }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('documents.bac.ecoule') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- No stats rapides needed for Ecoule, all are expired --}}

{{-- Table --}}
<div class="card">
    <div class="card-body table-responsive">
        <table id="tempout-table" class="table table-bordered table-hover">
            <thead class="bg-warning">
                <tr>
                    <th>#</th>
                    <th>Stagiaire</th>
                    <th>CIN</th>
                    <th>Téléphone</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th>Date retrait</th>
                    <th>Deadline (48h)</th>
                    <th>Statut / Retard</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                @php
                    $lastSortie = $doc->movements
                        ->where('action_type', 'Sortie')
                        ->sortByDesc('date_action')
                        ->first();
                    $deadline   = $lastSortie?->deadline
                        ? \Carbon\Carbon::parse($lastSortie->deadline)
                        : null;
                    $diff    = $deadline ? $deadline->diff(now()) : null;
                    $overdue = $diff
                        ? ($diff->days > 0
                            ? $diff->days . 'j ' . $diff->h . 'h ' . $diff->i . 'min'
                            : $diff->h . 'h ' . $diff->i . 'min')
                        : '—';
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('trainees.show', $doc->trainee) }}">
                            {{ $doc->trainee->last_name }} {{ $doc->trainee->first_name }}
                        </a>
                    </td>
                    <td>{{ $doc->trainee->cin }}</td>
                    <td>
                        @if($doc->trainee->phone)
                            <a href="tel:{{ $doc->trainee->phone }}">
                                {{ $doc->trainee->phone }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $doc->trainee->filiere->nom_filiere }}</td>
                    <td>{{ $doc->trainee->group }}</td>
                    <td>
                        {{ $lastSortie
                            ? \Carbon\Carbon::parse($lastSortie->date_action)->format('d/m/Y H:i')
                            : '—' }}
                    </td>
                    <td>
                        {{ $deadline ? $deadline->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td>
                        <span class="badge badge-danger">
                            <i class="fas fa-exclamation-triangle"></i> Expiré
                        </span>
                        <br>
                        <small class="text-danger font-weight-bold">
                            <i class="fas fa-hourglass-end"></i> Retard: {{ $overdue }}
                        </small>
                    </td>
                    <td>
                        <a href="{{ route('documents.show', $doc) }}"
                           class="btn btn-sm btn-info"
                           title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('documents.retour', $doc) }}"
                              method="POST" style="display:inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-success"
                                    title="Confirmer le retour"
                                    onclick="return confirm('Confirmer le retour du Bac?')">
                                <i class="fas fa-undo"></i> Retour
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                        <br>
                        <strong>Aucun retrait temporaire en cours</strong>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $documents->links() }}
    </div>
</div>
@stop

@section('js')
<script>
    $('#tempout-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"
        },
        "paging": false,
        "order": [[8, "desc"]]
    });
    $('.select2').select2();
</script>
@stop