cat > resources/views/documents/bac/temp-out.blade.php << 'EOF'
@extends('adminlte::page')
@section('title', 'Retraits temporaires — Bac')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clock"></i> Bac — Retraits temporaires</h1>
        <span class="badge badge-warning" style="font-size:14px">
            {{ $documents->total() }} en cours
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
        <form method="GET" action="{{ route('documents.bac.temp-out') }}">
            <div class="row">

                <div class="col-md-3">
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
                    <label>Année promotion</label>
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

                <div class="col-md-2">
                    <label>Année d'étude</label>
                    <select name="annee_etude" class="form-control">
                        <option value="">— Toutes —</option>
                        @foreach($annees_etude as $a)
                            <option value="{{ $a }}"
                                {{ request('annee_etude') == $a ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('documents.bac.temp-out') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Stats rapides --}}
@php
    $expired = 0;
    $ok      = 0;
    foreach($documents as $doc) {
        $ls = $doc->movements->where('action_type','Sortie')->sortByDesc('date_action')->first();
        $dl = $ls?->deadline ? \Carbon\Carbon::parse($ls->deadline) : null;
        if ($dl && now()->gt($dl)) $expired++;
        else $ok++;
    }
@endphp

<div class="row mb-3">
    <div class="col-md-4">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $expired }}</h3>
                <p>Délai expiré</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $ok }}</h3>
                <p>Dans les délais</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $documents->total() }}</h3>
                <p>Total en cours</p>
            </div>
            <div class="icon"><i class="fas fa-list"></i></div>
        </div>
    </div>
</div>

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
                    <th>Signature</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($documents as $doc)
                @php
                    $lastSortie = $doc->movements->where('action_type', 'Sortie')->sortByDesc('date_action')->first();
                    $deadline   = $lastSortie?->deadline ? \Carbon\Carbon::parse($lastSortie->deadline) : null;
                    $isExpired  = $deadline && now()->gt($deadline);

                    $overdue = null;
                    if ($isExpired) {
                        $diff = $deadline->diff(now());
                        $overdue = $diff->days > 0
                            ? $diff->days.'j '.$diff->h.'h '.$diff->i.'min'
                            : $diff->h.'h '.$diff->i.'min';
                    }

                    $hoursLeft = (!$isExpired && $deadline)
                        ? now()->diffInHours($deadline, false)
                        : null;
                @endphp

                <tr class="{{ $isExpired ? 'table-danger' : '' }}">
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
                        @if($isExpired)
                            <span class="badge badge-danger">
                                Expiré
                            </span>
                            <br>
                            <small class="text-danger font-weight-bold">
                                Retard: {{ $overdue }}
                            </small>

                        @elseif($hoursLeft !== null)

                            @if($hoursLeft <= 6)
                                <span class="badge badge-danger">
                                    {{ $hoursLeft }}h restantes
                                </span>

                            @elseif($hoursLeft <= 24)
                                <span class="badge badge-warning">
                                    {{ $hoursLeft }}h restantes
                                </span>

                            @else
                                <span class="badge badge-success">
                                    {{ $hoursLeft }}h restantes
                                </span>
                            @endif

                        @else
                            <span class="badge badge-secondary">—</span>
                        @endif
                    </td>

                    {{-- ✅ COLONNE SIGNATURE / SCANNER --}}
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-warning btn-sm btn-scan-temp"
                                data-cin="{{ $doc->trainee->cin }}"
                                data-id="{{ $doc->id }}"
                                title="Scanner CIN / QR Code">
                            <i class="fas fa-qrcode"></i> Scanner
                        </button>
                        <div id="scan-result-{{ $doc->id }}" class="mt-1" style="display:none">
                            <span class="badge scan-badge-{{ $doc->id }} badge-success">
                                <i class="fas fa-check-circle"></i>
                                <span class="scan-text-{{ $doc->id }}"></span>
                            </span>
                        </div>
                    </td>

                    <td>
                        <a href="{{ route('documents.show', $doc) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>

                        <form action="{{ route('documents.retour', $doc) }}"
                              method="POST" style="display:inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Confirmer le retour du Bac?')">
                                <i class="fas fa-undo"></i> Retour
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="11" class="text-center py-4 text-success">
                        <strong>Aucun retrait temporaire en cours</strong>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $documents->links() }}
    </div>
</div>

{{-- ✅ MODAL SCANNER CIN / QR --}}
<div class="modal fade" id="modal-scanner-temp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode"></i>
                    Scanner CIN / QR Code
                    <small class="ml-2 text-dark font-weight-normal" id="modal-scan-cin-label"></small>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">

                {{-- Vidéo --}}
                <div style="position:relative; background:#000; border-radius:8px; overflow:hidden; min-height:200px">
                    <video id="modal-scanner-video"
                           style="width:100%; display:block"
                           autoplay playsinline></video>

                    {{-- Ligne scan animée --}}
                    <div style="
                        position:absolute; top:0; left:0; right:0;
                        height:3px;
                        background: linear-gradient(to right, transparent, #f39c12, transparent);
                        animation: scanLine 2s linear infinite;
                    "></div>

                    {{-- Cadre de visée --}}
                    <div style="
                        position:absolute; top:50%; left:50%;
                        transform:translate(-50%,-55%);
                        width:65%; height:55%;
                        border:2px solid #f39c12;
                        border-radius:8px;
                        pointer-events:none;
                    "></div>

                    {{-- Coins décoratifs --}}
                    <div style="position:absolute;top:22%;left:18%;width:18px;height:18px;border-top:3px solid #f39c12;border-left:3px solid #f39c12;border-radius:2px 0 0 0"></div>
                    <div style="position:absolute;top:22%;right:18%;width:18px;height:18px;border-top:3px solid #f39c12;border-right:3px solid #f39c12;border-radius:0 2px 0 0"></div>
                    <div style="position:absolute;bottom:22%;left:18%;width:18px;height:18px;border-bottom:3px solid #f39c12;border-left:3px solid #f39c12;border-radius:0 0 0 2px"></div>
                    <div style="position:absolute;bottom:22%;right:18%;width:18px;height:18px;border-bottom:3px solid #f39c12;border-right:3px solid #f39c12;border-radius:0 0 2px 0"></div>
                </div>

                <p class="text-center text-muted mt-2 mb-0">
                    <small><i class="fas fa-info-circle"></i>
                        Pointez la caméra vers le code CIN ou QR du stagiaire
                    </small>
                </p>

                {{-- Résultat --}}
                <div id="modal-scan-result" class="mt-2" style="display:none">
                    <div id="modal-scan-alert" class="alert mb-0 py-2">
                        <i class="fas fa-check-circle"></i>
                        <strong>Code détecté :</strong>
                        <span id="modal-scan-text" class="ml-1 font-weight-bold"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scanLine {
    0%   { top: 0%; }
    100% { top: 100%; }
}
</style>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    // ========== DATATABLE & SELECT2 ==========
    $('#tempout-table').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"
        },
        paging: false,
        order: [[8, "desc"]]
    });

    $('.select2').select2();

    // ========== SCANNER QR / CIN ==========
    (function () {
        var videoEl      = document.getElementById('modal-scanner-video');
        var canvas       = document.createElement('canvas');
        var ctx          = canvas.getContext('2d');
        var videoStream  = null;
        var scanInterval = null;
        var currentCIN   = '';
        var currentDocId = '';

        // Clic sur un bouton Scanner dans le tableau
        $(document).on('click', '.btn-scan-temp', function () {
            currentCIN   = $(this).data('cin');
            currentDocId = $(this).data('id');

            $('#modal-scan-cin-label').text(
                currentCIN ? '— CIN attendu : ' + currentCIN : ''
            );
            $('#modal-scan-result').hide();
            $('#modal-scan-text').text('');

            $('#modal-scanner-temp').modal('show');
        });

        // Démarrer la caméra à l'ouverture du modal
        $('#modal-scanner-temp').on('shown.bs.modal', function () {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(function (stream) {
                    videoStream = stream;
                    videoEl.srcObject = stream;
                    videoEl.play();

                    scanInterval = setInterval(function () {
                        if (videoEl.readyState !== videoEl.HAVE_ENOUGH_DATA) return;

                        canvas.width  = videoEl.videoWidth;
                        canvas.height = videoEl.videoHeight;
                        ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);

                        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        var code = jsQR(imageData.data, imageData.width, imageData.height);

                        if (code) {
                            stopCamera();
                            showScanResult(code.data);
                        }
                    }, 500);
                })
                .catch(function (err) {
                    alert('Impossible d\'accéder à la caméra : ' + err.message);
                    $('#modal-scanner-temp').modal('hide');
                });
        });

        // Arrêter la caméra à la fermeture du modal
        $('#modal-scanner-temp').on('hidden.bs.modal', function () {
            stopCamera();
        });

        function stopCamera() {
            clearInterval(scanInterval);
            scanInterval = null;
            if (videoStream) {
                videoStream.getTracks().forEach(function (t) { t.stop(); });
                videoStream = null;
            }
        }

        function showScanResult(data) {
            var alertEl = $('#modal-scan-alert');
            var match   = (data === currentCIN);

            alertEl.removeClass('alert-success alert-warning alert-danger');

            if (!currentCIN) {
                alertEl.addClass('alert-success');
                $('#modal-scan-text').text(data + ' ✅');
            } else if (match) {
                alertEl.addClass('alert-success');
                $('#modal-scan-text').text(data + ' ✅ CIN confirmé !');
            } else {
                alertEl.addClass('alert-warning');
                $('#modal-scan-text').text(data + ' ⚠️ CIN différent du stagiaire');
            }

            $('#modal-scan-result').show();

            // Mettre à jour le badge dans la ligne du tableau
            var badge = $('.scan-badge-' + currentDocId);
            var text  = $('.scan-text-'  + currentDocId);
            badge.removeClass('badge-success badge-warning')
                 .addClass(match ? 'badge-success' : 'badge-warning');
            text.text(match ? '✅ ' + data : '⚠️ ' + data);
            $('#scan-result-' + currentDocId).show();
        }
    })();
</script>
@stop
EOF