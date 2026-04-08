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
                        <label>CIN *</label>
                        <input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" value="{{ old('cin') }}">
                        @error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>CEF</label>
                        <input type="text" name="cef" class="form-control @error('cef') is-invalid @enderror" value="{{ old('cef') }}">
                        @error('cef')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Date de naissance</label>
                        <input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance') }}">
                        @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Filière -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Filière *</label>
                        <select name="filiere_id" class="form-control select2 @error('filiere_id') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->secteur->nom_secteur }} — {{ $filiere->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Groupe -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Groupe *</label>
                        <input type="text" name="group" class="form-control @error('group') is-invalid @enderror" value="{{ old('group') }}">
                        @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Année -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Année de promotion *</label>
                        <input type="number" name="graduation_year"
                               class="form-control @error('graduation_year') is-invalid @enderror"
                               value="{{ old('graduation_year', date('Y')) }}">
                        @error('graduation_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- ✅ Statut -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Statut *</label>
                        <select name="statut" class="form-control @error('statut') is-invalid @enderror">
                            <option value="en_formation" {{ old('statut','en_formation')=='en_formation'?'selected':'' }}>🎓 En formation</option>
                            <option value="diplome" {{ old('statut')=='diplome'?'selected':'' }}>✅ Diplômé</option>
                            <option value="abandon" {{ old('statut')=='abandon'?'selected':'' }}>❌ Abandon</option>
                            <option value="redoublant" {{ old('statut')=='redoublant'?'selected':'' }}>🔄 Redoublant</option>
                        </select>
                        @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Photo -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Photo</label>
                        <input type="file" name="image_profile" class="form-control">
                    </div>
                </div>

            </div>

            <button class="btn btn-primary mt-3">Enregistrer</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
$('.select2').select2();
</script>
@stop