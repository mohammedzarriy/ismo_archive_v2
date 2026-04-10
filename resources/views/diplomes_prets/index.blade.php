@extends('adminlte::page')
@section('title', 'Diplômés — Documents à récupérer')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-graduation-cap text-success"></i>
            Diplômés — Documents à récupérer
        </h1>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-success mr-2" style="font-size:14px">
                {{ $trainees->total() }} diplômés
            </span>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPromouvoir">
                <i class="fas fa-user-graduate"></i> Promouvoir un diplômé
            </button>
        </div>
    </div>
@stop

@section('content')

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('diplomes.prets') }}">
            <div class="row">
                <div class="col-md-3">
                    <select name="filiere_id" class="form-control select2">
                        <option value="">— Toutes les filières —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id }}" {{ request('filiere_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="group" class="form-control">
                        <option value="">— Tous les groupes —</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}" {{ request('group') == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="graduation_year" class="form-control">
                        <option value="">— Toutes les années —</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('graduation_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex">
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
            <thead class="bg-success text-white">
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
                    <th>Signature</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainees as $t)
                @php
                    $docs       = $t->documents->groupBy('type');
                    $allPresent = collect(['Bac','Diplome','Attestation','Bulletin'])
                        ->every(fn($type) => isset($docs[$type]) && $docs[$type]->isNotEmpty());
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
                        @if($allPresent)
                            <br>
                            <span class="badge badge-success mt-1">
                                <i class="fas fa-check-circle"></i> Complet
                            </span>
                        @else
                            <br>
                            <button class="btn btn-xs btn-warning mt-1 btn-promote" data-id="{{ $t->id }}">
                                <i class="fas fa-plus-circle"></i> Ajouter
                            </button>
                        @endif
                    </td>
                    <td>{{ $t->cin }}</td>
                    <td>{{ $t->filiere->nom_filiere }}</td>
                    <td>{{ $t->group }}</td>
                    <td>{{ $t->graduation_year }}</td>

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
                            <a href="{{ route('validations.create', $t) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-signature"></i> Valider
                            </a>
                        @endif
                    </td>

                    {{-- Colonne Signature --}}
                    <td class="text-center">
                        @if($t->validation && $t->validation->signature_path)
                            <img src="{{ Storage::url($t->validation->signature_path) }}"
                                 alt="Signature"
                                 style="max-height:40px;border:1px solid #ccc;border-radius:4px;cursor:pointer;"
                                 onclick="viewSignature('{{ Storage::url($t->validation->signature_path) }}')">
                        @else
                            <button class="btn btn-sm btn-outline-primary btn-scan-signature"
                                    data-id="{{ $t->id }}"
                                    data-name="{{ $t->last_name }} {{ $t->first_name }}">
                                <i class="fas fa-camera"></i> Scanner
                            </button>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('trainees.show', $t) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="text-center py-4 text-muted">
                        <i class="fas fa-graduation-cap fa-2x mb-2"></i><br>
                        Aucun diplômé trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $trainees->links() }}
    </div>
</div>

{{-- MODALE CAMÉRA --}}
<div class="modal fade" id="signatureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-camera"></i> Scanner la signature —
                    <span id="sigModalName"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <label class="font-weight-bold mb-2">📷 Caméra en direct</label>
                        <div style="position:relative;border:2px solid #28a745;border-radius:8px;overflow:hidden;">
                            <video id="cameraStream" autoplay playsinline
                                   style="width:100%;max-height:300px;background:#000;"></video>
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                                        width:80%;height:60%;border:2px dashed rgba(255,255,255,0.7);
                                        border-radius:4px;pointer-events:none;"></div>
                        </div>
                        <div class="mt-2">
                            <button id="btnCapture" class="btn btn-success mr-2">
                                <i class="fas fa-camera"></i> Capturer
                            </button>
                            <button id="btnSwitchCamera" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Changer
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <label class="font-weight-bold mb-2">✅ Aperçu</label>
                        <div style="border:2px solid #dee2e6;border-radius:8px;min-height:300px;
                                    display:flex;align-items:center;justify-content:center;background:#f8f9fa;">
                            <canvas id="captureCanvas" style="max-width:100%;max-height:300px;display:none;"></canvas>
                            <span id="noCapture" class="text-muted">
                                <i class="fas fa-arrow-left fa-2x"></i><br>
                                Cliquez sur "Capturer"
                            </span>
                        </div>
                        <div class="mt-2">
                            <button id="btnRetake" class="btn btn-warning mr-2" style="display:none;">
                                <i class="fas fa-redo"></i> Reprendre
                            </button>
                            <button id="btnSaveSignature" class="btn btn-primary" style="display:none;">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALE VISIONNEUSE --}}
<div class="modal fade" id="viewSignatureModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Signature enregistrée</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <img id="viewSignatureImg" src="" style="max-width:100%;">
            </div>
        </div>
    </div>
</div>

{{-- MODAL PROMOUVOIR UN DIPLÔMÉ --}}
<div class="modal fade" id="modalPromouvoir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-graduate"></i> Promouvoir un stagiaire en diplômé
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle"></i>
                    Sélectionnez un stagiaire pour changer son statut en <strong>Diplômé</strong>.
                    Il apparaîtra ensuite dans cette liste.
                </p>
                <div class="form-group">
                    <label>Rechercher un stagiaire</label>
                    <select id="selectTraineePromote" class="form-control select2" style="width:100%">
                        <option value="">— Tapez un nom ou CIN —</option>
                        @foreach(\App\Models\Trainee::whereIn('statut', ['en_formation','redoublant','abandon'])->orderBy('last_name')->get() as $tr)
                            <option value="{{ $tr->id }}">
                                {{ $tr->last_name }} {{ $tr->first_name }}
                                &nbsp;({{ $tr->cin }}) — {{ $tr->filiere?->nom_filiere ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="promoteResult" class="mt-2" style="display:none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" id="btnConfirmPromote" class="btn btn-success" disabled>
                    <i class="fas fa-check"></i> Confirmer la promotion
                </button>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
$('.select2').select2();
$('#selectTraineePromote').select2({ dropdownParent: $('#modalPromouvoir'), width: '100%' });
$('#prets-table').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json' },
    paging: false,
    scrollX: true
});

// ── Modal : Promouvoir un diplômé ──
$('#selectTraineePromote').on('change', function () {
    const val = $(this).val();
    $('#btnConfirmPromote').prop('disabled', !val);
    $('#promoteResult').hide();
});

$('#btnConfirmPromote').on('click', function () {
    const traineeId = $('#selectTraineePromote').val();
    if (!traineeId) return;
    const $btn = $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> En cours...');

    $.ajax({
        url: `/trainees/${traineeId}/promouvoir`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        }
    })
    .done(() => {
        toastr.success('Stagiaire promu en Diplômé avec succès !');
        $('#modalPromouvoir').modal('hide');
        setTimeout(() => location.reload(), 1000);
    })
    .fail(xhr => {
        const msg = xhr.responseJSON?.message ?? 'Erreur serveur.';
        toastr.error(msg);
        $('#promoteResult')
            .html(`<div class="alert alert-danger py-2"><i class="fas fa-times-circle"></i> ${msg}</div>`)
            .show();
    })
    .always(() => $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Confirmer la promotion'));
});

// ── Promotion ──
$(document).on('click', '.btn-promote', function () {
    const id   = $(this).data('id');
    const $btn = $(this);
    if (!confirm('Vérifier et promouvoir ce stagiaire ?')) return;
    $.post(`/diplomes-prets/${id}/check-promote`, { _token: '{{ csrf_token() }}' })
        .done(res => {
            if (res.success) {
                toastr.success(res.message);
                $btn.replaceWith('<span class="badge badge-success mt-1"><i class="fas fa-check-circle"></i> Complet</span>');
            } else {
                toastr.warning(res.message);
            }
        })
        .fail(() => toastr.error('Erreur serveur.'));
});

// ── Caméra ──
let stream = null, traineeId = null, facingMode = 'environment';
const video  = document.getElementById('cameraStream');
const canvas = document.getElementById('captureCanvas');
const ctx    = canvas.getContext('2d');

async function startCamera() {
    if (stream) stream.getTracks().forEach(t => t.stop());
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode, width: { ideal: 1280 } }
        });
        video.srcObject = stream;
    } catch (e) {
        toastr.error("Caméra inaccessible : " + e.message);
    }
}

$(document).on('click', '.btn-scan-signature', function () {
    traineeId = $(this).data('id');
    $('#sigModalName').text($(this).data('name'));
    canvas.style.display = 'none';
    $('#noCapture').show();
    $('#btnRetake, #btnSaveSignature').hide();
    $('#signatureModal').modal('show');
    startCamera();
});

$('#signatureModal').on('hidden.bs.modal', () => {
    if (stream) stream.getTracks().forEach(t => t.stop());
});

$('#btnSwitchCamera').on('click', () => {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    startCamera();
});

$('#btnCapture').on('click', () => {
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    canvas.style.display = 'block';
    $('#noCapture').hide();
    $('#btnRetake, #btnSaveSignature').show();
});

$('#btnRetake').on('click', () => {
    canvas.style.display = 'none';
    $('#noCapture').show();
    $('#btnRetake, #btnSaveSignature').hide();
});

$('#btnSaveSignature').on('click', function () {
    const $btn = $(this).prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i>');
    $.post(`/diplomes-prets/${traineeId}/signature`, {
        _token: '{{ csrf_token() }}',
        signature: canvas.toDataURL('image/png')
    })
    .done(res => {
        if (res.success) {
            toastr.success(res.message);
            $('#signatureModal').modal('hide');
            $(`.btn-scan-signature[data-id="${traineeId}"]`)
                .replaceWith(`<img src="${res.path}"
                    style="max-height:40px;border:1px solid #ccc;border-radius:4px;cursor:pointer;"
                    onclick="viewSignature('${res.path}')">`);
        } else {
            toastr.error(res.message);
        }
    })
    .fail(() => toastr.error('Erreur enregistrement.'))
    .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer'));
});

function viewSignature(path) {
    $('#viewSignatureImg').attr('src', path);
    $('#viewSignatureModal').modal('show');
}
</script>
@stop
