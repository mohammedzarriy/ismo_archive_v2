<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Trainee;
use App\Models\Document;
use App\Models\Movement;
use App\Models\Validation;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔹 الإحصائيات العامة
        $stats = [
            // إجمالي المتدربين
            'total_stagiaires'  => Trainee::count(),

            // Bac Temp-Out (سحب مؤقت)
            'bac_temp_out'      => Document::where('type', 'Bac')
                                           ->where('status', 'Temp_Out')
                                           ->count(),

            // Bac Final-Out (تم إخراجه نهائيًا)
            'bac_final_out'     => Document::where('type', 'Bac')
                                           ->where('status', 'Final_Out')
                                           ->count(),

            // 🔴 Bac منتهي المهلة (Temp-Out + Deadline < الآن) باستخدام Query Builder لتسريع الأداء
            'bac_expired'       => DB::table('documents')
                                     ->join('movements', 'documents.id', '=', 'movements.document_id')
                                     ->where('documents.type', 'Bac')
                                     ->where('documents.status', 'Temp_Out')
                                     ->where('movements.action_type', 'Sortie')
                                     ->whereNotNull('movements.deadline')
                                     ->where('movements.deadline', '<', now())
                                     ->count(),

            // Diplomes جاهزة في المخزن
            'diplomes_prets'    => Document::where('type', 'Diplome')
                                           ->where('status', 'Stock')
                                           ->count(),

            // الحركات المسجلة اليوم
            'mouvements_today'  => Movement::whereDate('date_action', today())
                                           ->count(),

            // إجمالي الـ Validations
            'total_validations' => Validation::count(),
        ];

        // 🔹 آخر الحركات (10 أحدث)
        $recent_movements = Movement::with(['document.trainee', 'user'])
                                    ->latest('date_action')
                                    ->take(10)
                                    ->get();

        // 🔹 تنبيهات Bac Temp-Out (لكل Bac قيد السحب المؤقت)
        $bac_alerts = Document::with('trainee')
                              ->where('type', 'Bac')
                              ->where('status', 'Temp_Out')
                              ->get();

        return view('dashboard', compact('stats', 'recent_movements', 'bac_alerts'));
    }
}