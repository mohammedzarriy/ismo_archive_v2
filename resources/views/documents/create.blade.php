@extends('adminlte::page')
@section('title', 'Ajouter document')
@section('content_header')
    <h1><i class="fas fa-plus"></i> Ajouter un document</h1>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('documents.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Stagiaire <span class="text-danger">*</span></label>
                        <select name="trainee_id" class="form-control select2 @error('trainee_id') is-invalid @enderror" required>
                            <option value="">-- Choisir --</option>
                            @foreach($trainees as $t)
                                <option value="{{ $t->id }}" {{ old('trainee_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->last_name }} {{ $t->first_name }} — {{ $t->cin }}
                                </option>
                            @endforeach
                        </select>
                        @error('trainee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Type <span class="text-danger">*</span></label>
                        <select name="type" id="type-select" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="">-- Choisir --</option>
                            <option value="Bac" {{ old('type') == 'Bac' ? 'selected' : '' }}>Baccalauréat</option>
                            <option value="Diplome" {{ old('type') == 'Diplome' ? 'selected' : '' }}>Diplôme</option>
                            <option value="Attestation" {{ old('type') == 'Attestation' ? 'selected' : '' }}>Attestation</option>
                            <option value="Bulletin" {{ old('type') == 'Bulletin' ? 'selected' : '' }}>Bulletin de notes</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- يظهر فقط إذا اختار Bac --}}
                <div class="col-md-6" id="bac-status-div" style="display:none">
                    <div class="form-group">
                        <label>Type de retrait <span class="text-danger">*</span></label>
                        <select name="bac_status" id="bac-status" class="form-control">
                            <option value="Temp_Out">Retrait temporaire</option>
                            <option value="Final_Out">Retrait définitif</option>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Temporaire = retour obligatoire | Définitif = remis définitivement
                        </small>
                    </div>
                </div>

                {{-- يظهر فقط إذا مش Bac --}}
                <div class="col-md-6" id="level-year-div">
                    <div class="form-group">
                        <label>Année (1 ou 2)</label>
                        <select name="level_year" class="form-control">
                            <option value="">— Non applicable —</option>
                            <option value="1">Année 1</option>
                            <option value="2">Année 2</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Numéro de référence</label>
                        <input type="text" name="reference_number"
                               class="form-control"
                               value="{{ old('reference_number') }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
$('.select2').select2();

$('#type-select').on('change', function() {
    var type = $(this).val();

    if (type === 'Bac') {
        $('#bac-status-div').show();
        $('#level-year-div').hide();
    } else {
        $('#bac-status-div').hide();
        $('#level-year-div').show();
    }
});

if ('{{ old('type') }}' === 'Bac') {
    $('#bac-status-div').show();
    $('#level-year-div').hide();
}
</script>
@stop