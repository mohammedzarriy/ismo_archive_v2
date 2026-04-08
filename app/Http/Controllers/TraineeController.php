<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\Filiere;
use Illuminate\Http\Request;
use App\Imports\TraineesImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TraineeController extends Controller
{
    public function index(Request $request)
    {
        $trainees = Trainee::with(['filiere', 'documents'])
            ->when($request->filled('filiere_id'), function ($query) use ($request) {
                $query->where('filiere_id', $request->filiere_id);
            })
            ->when($request->filled('group'), function ($query) use ($request) {
                $query->where('group', $request->group);
            })
            ->when($request->filled('graduation_year'), function ($query) use ($request) {
                $query->where('graduation_year', $request->graduation_year);
            })
            ->orderBy('last_name')
            ->paginate(15)
            ->withQueryString();

        return view('trainees.index', [
            'trainees' => $trainees,
            'filieres' => Filiere::all(),
            'groups'   => Trainee::select('group')->distinct()->pluck('group'),
            'years'    => Trainee::select('graduation_year')->distinct()->pluck('graduation_year'),
        ]);
    }

    public function create()
    {
        $filieres = Filiere::all();
        return view('trainees.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'filiere_id'      => 'required|exists:filieres,id',
            'cin'             => 'required|unique:trainees,cin',
            'cef'             => 'nullable|string',
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'sexe'            => 'nullable|in:M,F',
            'date_naissance'  => 'nullable|date',
            'phone'           => 'nullable|string|max:20',
            'group'           => 'required|string|max:10',
            'graduation_year' => 'required|digits:4',
        ]);

        Trainee::create($validated);

        return redirect()->route('trainees.index')
                         ->with('success', 'Stagiaire ajouté avec succès ✅');
    }

    public function show(Trainee $trainee)
    {
        $trainee->load(['filiere.secteur', 'documents.movements.user']);
        return view('trainees.show', compact('trainee'));
    }

    public function edit(Trainee $trainee)
    {
        $filieres = Filiere::all();
        return view('trainees.edit', compact('trainee', 'filieres'));
    }

    public function update(Request $request, Trainee $trainee)
    {
        $validated = $request->validate([
            'filiere_id'      => 'required|exists:filieres,id',
            'cin'             => 'required|unique:trainees,cin,' . $trainee->id,
            'cef'             => 'nullable|string',
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'sexe'            => 'nullable|in:M,F',
            'date_naissance'  => 'nullable|date',
            'phone'           => 'nullable|string|max:20',
            'group'           => 'required|string|max:10',
            'graduation_year' => 'required|digits:4',
        ]);

        $trainee->update($validated);

        return redirect()->route('trainees.index')
                         ->with('success', 'Stagiaire modifié avec succès ✅');
    }

    public function destroy(Trainee $trainee)
    {
        $trainee->delete();
        return redirect()->route('trainees.index')
                         ->with('success', 'Stagiaire supprimé ✅');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv']
        ]);

        try {
            Excel::import(new TraineesImport, $request->file('file'));
            return back()->with('success', 'Import réussi ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur import : ' . $e->getMessage());
        }
    }

    public function downloadReport(Trainee $trainee)
    {
        $trainee->load(['filiere.secteur', 'documents.movements.user']);

        $pdf = Pdf::loadView('reports.trainee', compact('trainee'))
                  ->setPaper('a4', 'portrait');

        $filename = sprintf('rapport_%s_%s.pdf', $trainee->cin, now()->format('Ymd'));

        return $pdf->download($filename);
    }
}
