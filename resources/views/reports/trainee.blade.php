{{-- resources/views/reports/trainee.blade.php --}}
<!DOCTYPE html>
<html lang="fr" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            direction: rtl;
        }

        /* HEADER */
        .header {
            background: #1e3a5f;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header p  { font-size: 11px; opacity: 0.8; }
        .badge {
            background: white;
            color: #1e3a5f;
            padding: 6px 14px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .content { padding: 24px 30px; }

        /* SECTION */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a5f;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 4px;
            margin: 20px 0 12px;
        }

        /* INFO GRID */
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row { display: table-row; }
        .info-label, .info-value {
            display: table-cell;
            padding: 6px 10px;
            border: 1px solid #e0e0e0;
            width: 50%;
        }
        .info-label { background: #f5f7fa; font-weight: bold; color: #555; }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 12px;
        }
        thead tr { background: #1e3a5f; color: white; }
        th, td { padding: 8px 10px; border: 1px solid #ddd; text-align: right; }
        tbody tr:nth-child(even) { background: #f9f9f9; }

        /* STATUS BADGE */
        .status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-stock    { background:#d1fae5; color:#065f46; }
        .status-temp     { background:#fef3c7; color:#92400e; }
        .status-final    { background:#fee2e2; color:#991b1b; }
        .status-remis    { background:#dbeafe; color:#1e40af; }
        .status-ecoule   { background:#ffe4e6; color:#9f1239; }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            font-size: 10px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div>
        <h1>Rapport du stagiaire</h1>
        <p>ISMO Archive — Système de suivi des documents</p>
        <p>Date d’impression : {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <div class="badge">OFPPT</div>
</div>

<div class="content">

    {{-- 1. Informations du stagiaire --}}
    <div class="section-title">📋 Informations du stagiaire</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Nom complet</div>
            <div class="info-value">{{ $trainee->first_name }} {{ $trainee->last_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">CIN</div>
            <div class="info-value">{{ $trainee->cin }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Filière</div>
            <div class="info-value">{{ $trainee->filiere->nom_filiere }} ({{ $trainee->filiere->code_filiere }})</div>
        </div>
        <div class="info-row">
            <div class="info-label">Secteur</div>
            <div class="info-value">{{ $trainee->filiere->secteur->nom_secteur }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Groupe</div>
            <div class="info-value">{{ $trainee->group }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Année de graduation</div>
            <div class="info-value">{{ $trainee->graduation_year }}</div>
        </div>
    </div>

    {{-- 2. État des documents --}}
    <div class="section-title">📁 État des documents</div>
    <table>
        <thead>
            <tr>
                <th>Type de document</th>
                <th>Année</th>
                <th>Statut</th>
                <th>Référence</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainee->documents as $doc)
            @php
                $statusMap = [
                    'Stock'     => ['label' => 'En stock',            'class' => 'status-stock'],
                    'Temp_Out'  => ['label' => 'Sortie temporaire',   'class' => 'status-temp'],
                    'Final_Out' => ['label' => 'Sortie définitive',   'class' => 'status-final'],
                    'Remis'     => ['label' => 'Remis',               'class' => 'status-remis'],
                    'Ecoule'    => ['label' => 'Écoulé',              'class' => 'status-ecoule'],
                ];
                $s = $statusMap[$doc->status] ?? ['label' => $doc->status, 'class' => ''];
            @endphp
            <tr>
                <td>{{ $doc->type }}</td>
                <td>{{ $doc->level_year ?? '—' }}</td>
                <td><span class="status {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                <td>{{ $doc->reference_number ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;color:#888;">Aucun document</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 3. Historique des mouvements --}}
    <div class="section-title">🕒 Historique des mouvements</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Document</th>
                <th>Opération</th>
                <th>Responsable</th>
                <th>Observations</th>
            </tr>
        </thead>
        <tbody>
            @php
                $allMovements = $trainee->documents->flatMap->movements->sortByDesc('date_action');
            @endphp
            @forelse($allMovements as $mv)
            <tr>
                <td>{{ \Carbon\Carbon::parse($mv->date_action)->format('d/m/Y H:i') }}</td>
                <td>{{ $mv->document->type ?? '—' }}</td>
                <td>{{ $mv->action_type }}</td>
                <td>{{ $mv->user->name ?? '—' }}</td>
                <td>{{ $mv->observations ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#888;">Aucun mouvement</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- FOOTER --}}
<div class="footer">
    ISMO Archive &mdash; Document interne &mdash; {{ now()->format('d/m/Y') }}
</div>

</body>
</html>