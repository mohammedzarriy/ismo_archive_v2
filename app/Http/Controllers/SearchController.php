<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query   = $request->get('q');
        $results = [];

        if ($query && strlen($query) >= 2) {
            $like = '%'.$query.'%';
            $results = Trainee::with('filiere', 'documents', 'validation')
                ->where(function ($q) use ($like) {
                    $q->where('cin', 'LIKE', $like)
                        ->orWhere('cef', 'LIKE', $like)
                        ->orWhere('first_name', 'LIKE', $like)
                        ->orWhere('last_name', 'LIKE', $like)
                        ->orWhere('matricule_etudiant', 'LIKE', $like);
                })
                ->limit(10)
                ->get()
                ->map(function ($t) {
                    return [
                        'id'         => $t->id,
                        'name'       => $t->last_name.' '.$t->first_name,
                        'cin'        => $t->cin,
                        'cef'        => $t->cef ?? '—',
                        'filiere'    => $t->filiere?->nom_filiere ?? '—',
                        'url'        => route('trainees.show', $t),
                        'validated'  => (bool) $t->validation,
                        'docs_count' => $t->documents->count(),
                    ];
                });
        }

        return response()->json($results);
    }
}