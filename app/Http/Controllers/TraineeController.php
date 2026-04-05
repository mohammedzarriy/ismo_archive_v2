<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\Filiere;
use Illuminate\Http\Request;

class TraineeController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');
        $years    = Trainee::distinct()->pluck('graduation_year')->sortDesc();

        $trainees = Trainee::with('filiere.secteur', 'documents')
            ->when($request->filiere_id, fn($q) =>
                $q->where('filiere_id', $request->filiere_id))
            ->when($request->group, fn($q) =>
                $q->where('group', $request->group))
            ->when($request->graduation_year, fn($q) =>
                $q->where('graduation_year', $request->graduation_year))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('trainees.index', compact('trainees', 'filieres', 'groups', 'years'));
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
            'cef'             => 'nullable|string|unique:trainees,cef',
            'date_naissance'  => 'nullable|date',
            'phone'           => 'nullable|string|max:15',
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
        $trainee->load('filiere.secteur', 'documents.movements.user');
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
            'cef'             => 'nullable|string|unique:trainees,cef,' . $trainee->id,
            'date_naissance'  => 'nullable|date',
            'phone'           => 'nullable|string|max:15',
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