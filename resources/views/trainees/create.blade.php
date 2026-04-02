@extends('adminlte::page')

@section('title', 'Ajouter un stagiaire')

@section('content_header')
    <h1><i class="fas fa-user-plus"></i> Ajouter un stagiaire</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('trainees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>CIN <span class="text-danger">*</span></label>
                        <input type="text" name="cin"
                               class="form-control @error('cin') is-invalid @enderror"
                               value="{{ old('cin') }}">
                        @error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Filière <span class="text-danger">*</span></label>
                        <select name="filiere_id"
                                class="form-control select2 @error('filiere_id') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}"
                                    {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->secteur->nom_secteur }} — {{ $filiere->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}">
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nom <span class="text-danger">*</span></label>
                        <input type="text" name="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}">
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Groupe <span class="text-danger">*</span></label>
                        <input type="text" name="group"
                               class="form-control @error('group') is-invalid @enderror"
                               value="{{ old('group') }}">
                        @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Année de promotion <span class="text-danger">*</span></label>
                        <input type="number" name="graduation_year"
                               class="form-control @error('graduation_year') is-invalid @enderror"
                               value="{{ old('graduation_year', date('Y')) }}"
                               min="2000" max="2099">
                        @error('graduation_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Photo (optionnel)</label>
                        <input type="file" name="image_profile"
                               class="form-control @error('image_profile') is-invalid @enderror"
                               accept="image/*">
                        @error('image_profile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    $('.select2').select2();
</script>
@stop