<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Trainee;
use App\Models\Document;
use App\Models\Movement;
use App\Models\Validation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔹 Statistiques générales
        $stats = [
            // Nombre total des stagiaires
            'total_stagiaires'  => Trainee::count(),

            // Nombre de Bac en sortie temporaire
            'bac_temp_out'      => Document::where('type', 'Bac')
                                           ->where('status', 'Temp_Out')
                                           ->count(),

            // Nombre de Bac sortis définitivement
            'bac_final_out'     => Document::where('type', 'Bac')
                                           ->where('status', 'Final_Out')
                                           ->count(),

            // Nombre de Bac expirés (délai dépassé)
            'bac_expired'       => DB::table('documents')
                                     ->join('movements', 'documents.id', '=', 'movements.document_id')
                                     ->where('documents.type', 'Bac')
                                     ->where('documents.status', 'Temp_Out')
                                     ->where('movements.action_type', 'Sortie')
                                     ->whereNotNull('movements.deadline')
                                     ->where('movements.deadline', '<', now())
                                     ->count(),

            // Nombre de diplômes disponibles en stock
            'diplomes_prets'    => Document::where('type', 'Diplome')
                                           ->where('status', 'Stock')
                                           ->count(),

            // Nombre de mouvements effectués aujourd'hui
            'mouvements_today'  => Movement::whereDate('date_action', today())
                                           ->count(),

            // Nombre total des validations
            'total_validations' => Validation::count(),
        ];

        // 🔹 Récupération des 10 derniers mouvements
        $recent_movements = Movement::with(['document.trainee', 'user'])
                                    ->latest('date_action')
                                    ->take(10)
                                    ->get();

        // 🔹 Alertes pour les Bac en sortie temporaire
        $bac_alerts = Document::where('type', 'Bac')
                              ->where('status', 'Temp_Out')
                              ->with([
                                  'trainee',
                                  'movements' => function ($q) {
                                      // On récupère uniquement les mouvements de sortie
                                      $q->where('action_type', 'Sortie')
                                        ->latest('date_action');
                                  }
                              ])
                              ->get()
                              ->map(function ($doc) {
                                  // Dernier mouvement de sortie
                                  $sortie = $doc->movements->first();

                                  // Calcul du nombre d'heures depuis la sortie
                                  $hours = $sortie
                                      ? Carbon::parse($sortie->date_action)->diffInHours(now())
                                      : null;

                                  // Ajout des informations calculées
                                  $doc->hours_out = $hours;

                                  // Définition du niveau d'alerte
                                  $doc->alert_level = match (true) {
                                      $hours >= 48 => 'ecoule', // délai dépassé
                                      $hours >= 40 => 'danger', // قريب من انتهاء المهلة
                                      default      => 'normal', // حالة عادية
                                  };

                                  return $doc;
                              })
                              // Filtrer uniquement les documents ≥ 40h
                              ->filter(fn($d) => $d->hours_out !== null && $d->hours_out >= 40);

        // 🔹 Documents déjà écoulés
        $ecouleDocs = Document::where('status', 'Ecoule')
                              ->with('trainee')
                              ->latest()
                              ->get();

        // 🔹 Envoi des données à la vue dashboard
        return view('dashboard', compact(
            'stats',
            'recent_movements',
            'bac_alerts',
            'ecouleDocs'
        ));
    }
}