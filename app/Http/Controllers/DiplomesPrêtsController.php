<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\Filiere;
use Illuminate\Http\Request;

class DiplomesPrêtsController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');
        $years    = Trainee::distinct()->pluck('graduation_year')->sortDesc();

        $trainees = Trainee::with('filiere', 'documents', 'validation')
            ->where('statut', 'diplome')
            ->when($request->filiere_id, fn($q) =>
                $q->where('filiere_id', $request->filiere_id))
            ->when($request->group, fn($q) =>
                $q->where('group', $request->group))
            ->when($request->graduation_year, fn($q) =>
                $q->where('graduation_year', $request->graduation_year))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('diplomes_prets.index', compact(
            'trainees', 'filieres', 'groups', 'years'
        ));
    }
}