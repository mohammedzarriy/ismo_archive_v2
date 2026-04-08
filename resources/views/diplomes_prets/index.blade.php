@extends('adminlte::page')
@section('title', 'Diplômés — Documents à récupérer')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-graduation-cap text-success"></i>
            Diplômés — Documents à récupérer
        </h1>
        <span class="badge badge-success" style="font-size:14px">
            {{ $trainees->total() }} diplômés
        </span>
    </div>
@stop

@section('content')

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('diplomes.prets') }}">
            <div class="row">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <select name="graduation_year" class="form-control">
                        <option value="">— Toutes les années —</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}"
                                {{ request('graduation_year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('diplomes.prets') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table id="prets-table" class="table table-bordered table-hover">
            <thead class="bg-success">
                <tr>
                    <th>#</th>
                    <th>Stagiaire</th>
                    <th>CIN</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th>Année</th>
                    <th>Bac</th>
                    <th>Diplôme</th>
                    <th>Attestation</th>
                    <th>Bulletin</th>
                    <th>Validation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainees as $t)
                @php
                    $docs = $t->documents->groupBy('type');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('trainees.show', $t) }}">
                            <strong>{{ $t->last_name }} {{ $t->first_name }}</strong>
                        </a>
                        @if($t->phone)
                            <br><small><a href="tel:{{ $t->phone }}">📞 {{ $t->phone }}</a></small>
                        @endif
                    </td>
                    <td>{{ $t->cin }}</td>
                    <td>{{ $t->filiere->nom_filiere }}</td>
                    <td>{{ $t->group }}</td>
                    <td>{{ $t->graduation_year }}</td>

                    {{-- حالة كل وثيقة --}}
                    @foreach(['Bac','Diplome','Attestation','Bulletin'] as $type)
                    @php $doc = isset($docs[$type]) ? $docs[$type]->first() : null; @endphp
                    <td class="text-center">
                        @if(!$doc)
                            <span class="badge badge-light border">
                                <i class="fas fa-times text-danger"></i> Manquant
                            </span>
                        @elseif(in_array($doc->status, ['Final_Out','Remis']))
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Remis
                            </span>
                        @elseif($doc->status == 'Temp_Out')
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> Temp.
                            </span>
                        @else
                            <span class="badge badge-info">
                                <i class="fas fa-archive"></i> En stock
                            </span>
                        @endif
                    </td>
                    @endforeach

                    <td class="text-center">
                        @if($t->validation)
                            <span class="badge badge-success">
                                <i class="fas fa-check-double"></i>
                                {{ $t->validation->date_validation->format('d/m/Y') }}
                            </span>
                        @else
                            <a href="{{ route('validations.create', $t) }}"
                               class="btn btn-sm btn-outline-success">
                                <i class="fas fa-signature"></i> Valider
                            </a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('trainees.show', $t) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center py-4 text-muted">
                        <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                        <br>Aucun diplômé trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $trainees->links() }}
    </div>
</div>
@stop

@section('js')
<script>
$('.select2').select2();
$('#prets-table').DataTable({
    "language": {"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"},
    "paging": false,
    "scrollX": true
});
</script>
@stop