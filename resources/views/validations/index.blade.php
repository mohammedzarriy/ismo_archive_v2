@extends('adminlte::page')
@section('title', 'Registre des validations')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-check-double"></i> Registre des validations</h1>
        <div>
            <span class="badge badge-success mr-2" style="font-size:13px">
                <i class="fas fa-check-circle"></i> {{ $totalValides }} validés
            </span>
            <span class="badge badge-info" style="font-size:13px">
                <i class="fas fa-users"></i> {{ $trainees->total() }} stagiaires
            </span>
        </div>
    </div>
@stop

@section('content')

{{-- Filtres --}}
<div class="card mb-3">
    <div class="card-header bg-light">
        <h3 class="card-title"><i class="fas fa-filter"></i> Filtres</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('validations.index') }}">
            <div class="row">
                <div class="col-md-2">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="CIN, Nom..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label>Filière</label>
                    <select name="filiere_id" class="form-control select2">
                        <option value="">— Toutes —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id }}"
                                {{ request('filiere_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Groupe</label>
                    <select name="group" class="form-control">
                        <option value="">— Tous —</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}"
                                {{ request('group') == $g ? 'selected' : '' }}>
                                {{ $g }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Année</label>
                    <select name="graduation_year" class="form-control">
                        <option value="">— Toutes —</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}"
                                {{ request('graduation_year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('validations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead class="bg-success text-white">
                <tr>
                    <th>#</th>
                    <th>Stagiaire</th>
                    <th>CIN / CEF</th>
                    <th>Filière</th>
                    <th>Grp</th>
                    <th>Année</th>
                    <th class="text-center">Bac</th>
                    <th class="text-center">Diplôme</th>
                    <th class="text-center">Attestation</th>
                    <th class="text-center">Bulletin</th>
                    <th class="text-center">Validation</th>
                    <th class="text-center">Validé par</th>
                    <th class="text-center">Signature</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainees as $trainee)
                @php
                    $docs = $trainee->documents->groupBy('type');
                    $val  = $trainee->validation;

                    $statusConfig = [
                        'Remis'     => ['class' => 'badge-success',   'icon' => 'fa-check',        'label' => 'Remis'],
                        'Final_Out' => ['class' => 'badge-danger',    'icon' => 'fa-sign-out-alt', 'label' => 'Définitif'],
                        'Temp_Out'  => ['class' => 'badge-warning',   'icon' => 'fa-clock',        'label' => 'Temp.'],
                        'Stock'     => ['class' => 'badge-secondary', 'icon' => 'fa-archive',      'label' => 'Stock'],
                        'Ecoule'    => ['class' => 'badge-dark',      'icon' => 'fa-hourglass-end','label' => 'Écoulé'],
                    ];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('trainees.show', $trainee) }}" class="font-weight-bold">
                            {{ $trainee->last_name }} {{ $trainee->first_name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-light border">{{ $trainee->cin }}</span>
                        @if($trainee->cef)
                            <br><small class="text-muted">{{ $trainee->cef }}</small>
                        @endif
                    </td>
                    <td>
                        <small>{{ $trainee->filiere->nom_filiere ?? '—' }}</small>
                    </td>
                    <td>{{ $trainee->group }}</td>
                    <td>{{ $trainee->graduation_year }}</td>

                    {{-- Documents --}}
                    @foreach(['Bac','Diplome','Attestation','Bulletin'] as $type)
                    @php
                        $doc = isset($docs[$type]) ? $docs[$type]->first() : null;
                        $cfg = $doc ? ($statusConfig[$doc->status] ?? null) : null;
                    @endphp
                    <td class="text-center">
                        @if(!$doc)
                            <span class="badge badge-light border text-danger"
                                  title="Non enregistré">
                                <i class="fas fa-times"></i>
                            </span>
                        @else
                            <span class="badge {{ $cfg['class'] }}"
                                  title="{{ $doc->status }}">
                                <i class="fas {{ $cfg['icon'] }}"></i>
                                {{ $cfg['label'] }}
                            </span>
                            {{-- Historique mouvements --}}
                            @if($doc->movements->count() > 0)
                                <br>
                                <small class="text-muted"
                                       title="Dernière action : {{ $doc->movements->last()?->action_type }} — {{ $doc->movements->last()?->date_action }}">
                                    <i class="fas fa-history"></i>
                                    {{ $doc->movements->count() }} mvt
                                </small>
                            @endif
                        @endif
                    </td>
                    @endforeach

                    {{-- Validation --}}
                    <td class="text-center">
                        @if($val)
                            <span class="badge badge-success">
                                <i class="fas fa-check-double"></i>
                                {{ \Carbon\Carbon::parse($val->date_validation)->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="badge badge-light border text-muted">
                                <i class="fas fa-minus"></i> Non validé
                            </span>
                        @endif
                    </td>

                    {{-- Validé par --}}
                    <td class="text-center">
                        @if($val)
                            <small>{{ $val->user->name ?? '—' }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Signature --}}
                    <td class="text-center">
                        @if($val && $val->signature_scan)
                            <a href="{{ asset('storage/' . $val->signature_scan) }}"
                               target="_blank"
                               class="btn btn-xs btn-info"
                               title="Voir la signature">
                                <i class="fas fa-file-image"></i>
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="text-center">
                        <a href="{{ route('trainees.show', $trainee) }}"
                           class="btn btn-xs btn-info" title="Voir le stagiaire">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('trainees.report', $trainee) }}"
                           target="_blank"
                           class="btn btn-xs btn-dark" title="Rapport PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        @if(!$val)
                            <a href="{{ route('validations.create', $trainee) }}"
                               class="btn btn-xs btn-success" title="Valider">
                                <i class="fas fa-check-double"></i>
                            </a>
                        @else
                            <form action="{{ route('validations.destroy', $val) }}"
                                  method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-xs btn-danger"
                                        onclick="return confirm('Supprimer cette validation?')"
                                        title="Supprimer validation">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        Aucun stagiaire trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $trainees->links() }}
    </div>
</div>
@stop

@section("js")
<script>
    $(".select2").select2();
</script>
@stop
