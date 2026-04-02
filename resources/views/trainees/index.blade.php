@extends('adminlte::page')

@section('title', 'Liste des stagiaires')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users"></i> Liste des stagiaires</h1>
        <a href="{{ route('trainees.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Ajouter
        </a>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <table id="trainees-table" class="table table-bordered table-hover">
            <thead class="bg-primary">
                <tr>
                    <th>#</th>
                    <th>CIN</th>
                    <th>Nom complet</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainees as $trainee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trainee->cin }}</td>
                    <td>{{ $trainee->last_name }} {{ $trainee->first_name }}</td>
                    <td>{{ $trainee->filiere->nom_filiere }}</td>
                    <td>{{ $trainee->group }}</td>
                    <td>{{ $trainee->graduation_year }}</td>
                    <td>
                        <a href="{{ route('trainees.show', $trainee) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('trainees.edit', $trainee) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('trainees.destroy', $trainee) }}"
                              method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Confirmer la suppression?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
    $('#trainees-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"
        },
        "paging": false
    });
</script>
@stop