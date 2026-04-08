@extends('adminlte::page')
@section('title', 'Calendrier des relances')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-calendar-alt"></i> Calendrier des relances</h1>
        <a href="{{ route('documents.bac.temp-out') }}" class="btn btn-warning">
            <i class="fas fa-clock"></i> Voir tous les retraits temporaires
        </a>
    </div>
@stop

@section('content')

{{-- Stats --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['expired'] }}</h3>
                <p>Délai dépassé</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['today'] }}</h3>
                <p>Échéance aujourd'hui</p>
            </div>
            <div class="icon"><i class="fas fa-bell"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total en attente</p>
            </div>
            <div class="icon"><i class="fas fa-list"></i></div>
        </div>
    </div>
</div>

{{-- Navigation شهر --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            @php
                $prevMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->subMonth();
                $nextMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->addMonth();
            @endphp
            <a href="{{ route('calendrier', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
               class="btn btn-secondary">
                <i class="fas fa-chevron-left"></i> {{ $prevMonth->translatedFormat('F Y') }}
            </a>
            <h4 class="mb-0 text-primary">
                <i class="fas fa-calendar"></i>
                {{ $startOfMonth->translatedFormat('F Y') }}
            </h4>
            <a href="{{ route('calendrier', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
               class="btn btn-secondary">
                {{ $nextMonth->translatedFormat('F Y') }} <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Calendrier --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered mb-0" id="calendar-table">
            <thead>
                <tr class="bg-primary text-white text-center">
                    <th>Lun</th>
                    <th>Mar</th>
                    <th>Mer</th>
                    <th>Jeu</th>
                    <th>Ven</th>
                    <th class="bg-secondary">Sam</th>
                    <th class="bg-secondary">Dim</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $firstDay    = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                    $lastDay     = $startOfMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
                    $currentDay  = $firstDay->copy();
                @endphp

                @while($currentDay <= $lastDay)
                <tr>
                    @for($i = 0; $i < 7; $i++)
                    @php
                        $day        = $currentDay->day;
                        $isCurrentMonth = $currentDay->month == $month;
                        $isToday    = $currentDay->isToday();
                        $isWeekend  = $currentDay->isWeekend();
                        $dayEvents  = $events->get($day, collect());
                        $hasExpired = $isCurrentMonth && $dayEvents->where('is_expired', true)->count() > 0;
                        $hasToday   = $isCurrentMonth && $isToday && $dayEvents->count() > 0;
                    @endphp
                    <td style="width:14.28%; min-height:100px; vertical-align:top; padding:6px;
                               {{ !$isCurrentMonth ? 'background:#f8f9fa; color:#ccc;' : '' }}
                               {{ $isWeekend && $isCurrentMonth ? 'background:#fff8f0;' : '' }}
                               {{ $isToday ? 'border: 2px solid #007bff !important;' : '' }}">

                        {{-- رقم اليوم --}}
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-weight:{{ $isToday ? 'bold' : 'normal' }};
                                         color:{{ $isToday ? '#007bff' : 'inherit' }};
                                         font-size:14px">
                                {{ $isCurrentMonth ? $day : '' }}
                            </span>
                            @if($isToday && $isCurrentMonth)
                                <span class="badge badge-primary" style="font-size:9px">Aujourd'hui</span>
                            @endif
                        </div>

                        {{-- Events --}}
                        @if($isCurrentMonth)
                            @foreach($dayEvents as $event)
                            <a href="{{ $event['doc_url'] }}"
                               class="d-block mb-1 p-1 rounded text-decoration-none"
                               style="font-size:11px; line-height:1.3;
                                      background: {{ $event['is_expired'] ? '#f8d7da' : ($isToday ? '#fff3cd' : '#d1ecf1') }};
                                      color: {{ $event['is_expired'] ? '#721c24' : ($isToday ? '#856404' : '#0c5460') }};
                                      border-left: 3px solid {{ $event['is_expired'] ? '#dc3545' : ($isToday ? '#ffc107' : '#17a2b8') }}">
                                <strong>{{ Str::limit($event['trainee'], 15) }}</strong>
                                <br>
                                <span>{{ $event['filiere'] }}</span>
                                @if($event['is_expired'])
                                    <br>
                                    <span style="color:#dc3545; font-weight:bold">
                                        ⚠ Retard: {{ $event['overdue'] }}
                                    </span>
                                @endif
                                @if($event['phone'])
                                    <br>
                                    <a href="tel:{{ $event['phone'] }}"
                                       style="color:inherit; font-size:10px">
                                        📞 {{ $event['phone'] }}
                                    </a>
                                @endif
                            </a>
                            @endforeach
                        @endif
                    </td>
                    @php $currentDay->addDay() @endphp
                    @endfor
                </tr>
                @endwhile
            </tbody>
        </table>
    </div>
</div>

{{-- Légende --}}
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body py-2">
                <span class="mr-3">
                    <span style="display:inline-block; width:12px; height:12px; background:#f8d7da; border-left:3px solid #dc3545; margin-right:4px"></span>
                    Délai dépassé
                </span>
                <span class="mr-3">
                    <span style="display:inline-block; width:12px; height:12px; background:#fff3cd; border-left:3px solid #ffc107; margin-right:4px"></span>
                    Échéance aujourd'hui
                </span>
                <span>
                    <span style="display:inline-block; width:12px; height:12px; background:#d1ecf1; border-left:3px solid #17a2b8; margin-right:4px"></span>
                    Échéance à venir
                </span>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
#calendar-table td { height: 100px; }
#calendar-table td a:hover { opacity: 0.85; }
</style>
@stop