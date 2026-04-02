<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\Filiere;
use Illuminate\Http\Request;

class TraineeController extends Controller
{
    public function index()
    {
        $trainees = Trainee::with('filiere.secteur')->latest()->paginate(15);
        return view('trainees.index', compact('trainees'));
    }

    public function create()
    {
        $filieres = Filiere::with('secteur')->get();
        return view('trainees.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'filiere_id'      => 'required|exists:filieres,id',
            'cin'             => 'required|string|unique:trainees,cin',
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'group'           => 'required|string|max:50',
            'graduation_year' => 'required|digits:4',
            'image_profile'   => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image_profile')) {
            $data['image_profile'] = $request->file('image_profile')
                ->store('profiles', 'public');
        }

        Trainee::create($data);

        return redirect()->route('trainees.index')
            ->with('success', 'Stagiaire ajouté avec succès!');
    }

    public function show(Trainee $trainee)
    {
        $trainee->load('filiere.secteur', 'documents.movements');
        return view('trainees.show', compact('trainee'));
    }

    public function edit(Trainee $trainee)
    {
        $filieres = Filiere::with('secteur')->get();
        return view('trainees.edit', compact('trainee', 'filieres'));
    }

    public function update(Request $request, Trainee $trainee)
    {
        $request->validate([
            'filiere_id'      => 'required|exists:filieres,id',
            'cin'             => 'required|string|unique:trainees,cin,' . $trainee->id,
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'group'           => 'required|string|max:50',
            'graduation_year' => 'required|digits:4',
            'image_profile'   => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image_profile')) {
            $data['image_profile'] = $request->file('image_profile')
                ->store('profiles', 'public');
        }

        $trainee->update($data);

        return redirect()->route('trainees.index')
            ->with('success', 'Stagiaire modifié avec succès!');
    }

    public function destroy(Trainee $trainee)
    {
        $trainee->delete();
        return redirect()->route('trainees.index')
            ->with('success', 'Stagiaire supprimé avec succès!');
    }
}