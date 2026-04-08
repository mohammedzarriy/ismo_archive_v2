<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use App\Models\Trainee;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');
        $years    = Trainee::distinct()->pluck('graduation_year')->sortDesc();

        // Tous les stagiaires avec leurs documents et mouvements
        $trainees = Trainee::with([
                'filiere.secteur',
                'documents.movements.user',
                'validation.user'
            ])
            ->when($request->filiere_id, fn($q) =>
                $q->where('filiere_id', $request->filiere_id)
            )
            ->when($request->group, fn($q) =>
                $q->where('group', $request->group)
            )
            ->when($request->graduation_year, fn($q) =>
                $q->where('graduation_year', $request->graduation_year)
            )
            ->when($request->search, fn($q) =>
                $q->where('cin', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('first_name', 'like', '%'.$request->search.'%')
            )
            ->orderBy('last_name')
            ->paginate(20)
            ->withQueryString();

        $totalValides = Trainee::whereHas('validation')->count();

        return view('validations.index', compact(
            'trainees',
            'filieres',
            'groups',
            'years',
            'totalValides'
        ));
    }

    public function create(Trainee $trainee)
    {
        $docs  = $trainee->documents;
        $types = ['Bac', 'Diplome', 'Attestation', 'Bulletin'];
        $missing = [];

        foreach ($types as $type) {
            $doc = $docs->where('type', $type)->first();
            if (!$doc || !in_array($doc->status, ['Final_Out', 'Remis'])) {
                $missing[] = $type;
            }
        }

        return view('validations.create', compact('trainee', 'missing'));
    }

    public function store(Request $request, Trainee $trainee)
    {
        $request->validate([
            'date_validation' => 'required|date',
            'signature_scan'  => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
            'observations'    => 'nullable|string',
        ]);

        $path = $request->file('signature_scan')->store('signatures', 'public');

        Validation::create([
            'trainee_id'      => $trainee->id,
            'user_id'         => Auth::id(),
            'date_validation' => $request->date_validation,
            'signature_scan'  => $path,
            'observations'    => $request->observations,
        ]);

        return redirect()->route('validations.index')
            ->with('success', 'Validation enregistrée avec succès ✅');
    }

    public function show(Trainee $trainee)
    {
        $trainee->load([
            'filiere.secteur',
            'documents.movements.user',
            'validation.user'
        ]);

        $validation = $trainee->validation;

        if (!$validation) {
            return redirect()->route('trainees.show', $trainee)
                ->with('error', 'Aucune validation trouvée !');
        }

        return view('validations.show', compact('trainee', 'validation'));
    }

    public function destroy(Validation $validation)
    {
        $trainee = $validation->trainee;
        $validation->delete();

        return redirect()->route('validations.index')
            ->with('success', 'Validation supprimée !');
    }
}
