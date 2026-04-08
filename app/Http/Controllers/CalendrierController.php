<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Movement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendrierController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // كل الـ temp-out لي deadline ديالهم في هاد الشهر
        $events = Movement::with('document.trainee.filiere')
            ->where('action_type', 'Sortie')
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [$startOfMonth, $endOfMonth])
            ->whereHas('document', fn($q) =>
                $q->where('status', 'Temp_Out'))
            ->get()
            ->map(function ($mv) {
                $isExpired = now()->gt($mv->deadline);
                $diff      = $mv->deadline->diff(now());
                $overdue   = $isExpired
                    ? ($diff->days > 0
                        ? $diff->days . 'j ' . $diff->h . 'h'
                        : $diff->h . 'h ' . $diff->i . 'min')
                    : null;

                return [
                    'id'         => $mv->id,
                    'trainee'    => $mv->document->trainee->last_name . ' ' . $mv->document->trainee->first_name,
                    'cin'        => $mv->document->trainee->cin,
                    'phone'      => $mv->document->trainee->phone,
                    'filiere'    => $mv->document->trainee->filiere->nom_filiere,
                    'group'      => $mv->document->trainee->group,
                    'deadline'   => $mv->deadline,
                    'day'        => $mv->deadline->day,
                    'is_expired' => $isExpired,
                    'overdue'    => $overdue,
                    'doc_url'    => route('documents.show', $mv->document),
                ];
            })
            ->groupBy('day');

        // إحصائيات
        $stats = [
            'total'   => Movement::where('action_type', 'Sortie')
                ->whereNotNull('deadline')
                ->whereHas('document', fn($q) => $q->where('status', 'Temp_Out'))
                ->count(),
            'expired' => Movement::where('action_type', 'Sortie')
                ->whereNotNull('deadline')
                ->where('deadline', '<', now())
                ->whereHas('document', fn($q) => $q->where('status', 'Temp_Out'))
                ->count(),
            'today'   => Movement::where('action_type', 'Sortie')
                ->whereNotNull('deadline')
                ->whereDate('deadline', today())
                ->whereHas('document', fn($q) => $q->where('status', 'Temp_Out'))
                ->count(),
        ];

        return view('calendrier.index', compact(
            'events', 'month', 'year', 'startOfMonth', 'stats'
        ));
    }
}