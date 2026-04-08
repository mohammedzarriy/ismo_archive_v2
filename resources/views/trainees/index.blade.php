@extends('adminlte::page')

@section('title', 'Liste des stagiaires')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-users"></i> Liste des stagiaires</h1>
    <div>
        <a href="{{ route('trainees.import') }}" class="btn btn-success mr-2">
            <i class="fas fa-file-excel"></i> Importer Excel
        </a>
        <a href="{{ route('trainees.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Ajouter
        </a>
    </div>
</div>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('success') }}
</div>
@endif

{{-- Filtres --}}
<div class="card mb-3">
    <div class="card-header bg-light">
        <h3 class="card-title"><i class="fas fa-filter"></i> Filtres</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('trainees.index') }}" id="filter-form">
            <div class="row">
                <div class="col-md-3">
                    <label>Filière</label>
                    <select name="filiere_id" id="filiere-select" class="form-control select2">
                        <option value="">— Toutes les filières —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id }}" {{ request('filiere_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->code_filiere }} — {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Groupe</label>
                    <select name="group" id="group-select" class="form-control">
                        <option value="">— Tous les groupes —</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}" {{ request('group') == $g ? 'selected' : '' }}>
                                {{ $g }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Année</label>
                    <select name="graduation_year" id="year-select" class="form-control">
                        <option value="">— Toutes les années —</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('graduation_year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body table-responsive">
        <table id="trainees-table" class="table table-bordered table-hover">
            <thead class="bg-primary">
                <tr>
                    <th>#</th>
                    <th>CIN</th>
                    <th>CEF</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Sexe</th>
                    <th>Date naissance</th>
                    <th>Téléphone</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th>Année</th>
                    <th>Statut</th>
                    <th>Documents</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainees as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->cin }}</td>
                    <td>{{ $t->cef ?? '—' }}</td>
                    <td>{{ $t->last_name }}</td>
                    <td>{{ $t->first_name }}</td>
                    <td>{{ $t->sexe ?? '—' }}</td>
                    <td>{{ $t->date_naissance
                        ? \Carbon\Carbon::parse($t->date_naissance)->format('d/m/Y')
                        : '—' }}</td>
                    <td>
                        @if($t->phone)
                            <a href="tel:{{ $t->phone }}">{{ $t->phone }}</a>
                        @else
                            — 
                        @endif
                    </td>
                    <td>{{ $t->filiere->nom_filiere ?? '—' }}</td>
                    <td>{{ $t->group }}</td>
                    <td>{{ $t->graduation_year }}</td>

                    {{-- ✅ Statut Badge --}}
                    <td>
                        @if($t->statut == 'diplome')
                            <span class="badge badge-success">
                                <i class="fas fa-graduation-cap"></i> Diplômé
                            </span>
                        @elseif($t->statut == 'abandon')
                            <span class="badge badge-danger">
                                <i class="fas fa-times"></i> Abandon
                            </span>
                        @elseif($t->statut == 'redoublant')
                            <span class="badge badge-warning">
                                <i class="fas fa-redo"></i> Redoublant
                            </span>
                        @else
                            <span class="badge badge-info">
                                <i class="fas fa-book"></i> En formation
                            </span>
                        @endif
                    </td>

                    {{-- Documents --}}
                    <td>
                        @php
                            $docs  = $t->documents->groupBy('type');
                            $types = ['Bac','Diplome','Attestation','Bulletin'];
                        @endphp
                        @foreach($types as $type)
                            @if(isset($docs[$type]))
                                @php $doc = $docs[$type]->first(); @endphp
                                @if(in_array($doc->status, ['Remis','Final_Out']))
                                    <span class="badge badge-success" title="{{ $type }}">
                                        <i class="fas fa-check"></i> {{ $type }}
                                    </span>
                                @elseif($doc->status == 'Temp_Out')
                                    <span class="badge badge-warning" title="{{ $type }}">
                                        <i class="fas fa-clock"></i> {{ $type }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary" title="{{ $type }}">
                                        <i class="fas fa-archive"></i> {{ $type }}
                                    </span>
                                @endif
                            @else
                                <span class="badge badge-light border" title="{{ $type }}">
                                    <i class="fas fa-times text-danger"></i> {{ $type }}
                                </span>
                            @endif
                        @endforeach
                    </td>

                    {{-- Actions --}}
                    <td>
                        <a href="{{ route('trainees.show', $t) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('trainees.edit', $t) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('trainees.destroy', $t) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Confirmer la suppression?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <a href="{{ route('trainees.report', $t) }}" target="_blank"
                           title="Télécharger rapport PDF" class="btn btn-sm btn-dark">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $trainees->links() }}
    </div>
</div>

@stop

@section('js')
<script>
$('.select2').select2();

$('#filiere-select').on('change', function() {
    var filiereId = $(this).val();
    var groupSelect = $('#group-select');
    var yearSelect  = $('#year-select');

    groupSelect.html('<option value="">— Chargement... —</option>');
    yearSelect.html('<option value="">— Chargement... —</option>');

    if (!filiereId) {
        groupSelect.html('<option value="">— Tous les groupes —</option>');
        yearSelect.html('<option value="">— Toutes les années —</option>');
        return;
    }

    $.get('/api/filiere/' + filiereId + '/groups', function(data) {
        var options = '<option value="">— Tous les groupes —</option>';
        data.groups.forEach(function(g) { options += '<option value="' + g + '">' + g + '</option>'; });
        groupSelect.html(options);
    });

    $.get('/api/filiere/' + filiereId + '/years', function(data) {
        var options = '<option value="">— Toutes les années —</option>';
        data.years.forEach(function(y) { options += '<option value="' + y + '">' + y + '</option>'; });
        yearSelect.html(options);
    });
});

$('#trainees-table').DataTable({
    "language": {"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"},
    "paging": false
});
</script>
@stop