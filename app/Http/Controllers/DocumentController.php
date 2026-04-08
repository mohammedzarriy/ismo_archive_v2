<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Trainee;
use App\Models\Filiere;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');

        $documents = Document::with('trainee.filiere')
            ->when($type, fn($q) => $q->where('type', $type))
            ->latest()
            ->paginate(15);

        return view('documents.index', compact('documents', 'type'));
    }

    public function create()
    {
        $trainees = Trainee::orderBy('last_name')->get();
        return view('documents.create', compact('trainees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainee_id'       => 'required|exists:trainees,id',
            'type'             => 'required|in:Bac,Diplome,Attestation,Bulletin',
            'level_year'       => 'nullable|in:1,2',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $document = Document::create([
            'trainee_id'       => $request->trainee_id,
            'type'             => $request->type,
            'level_year'       => $request->level_year,
            'status'           => 'Stock',
            'reference_number' => $request->reference_number,
        ]);

        Movement::create([
            'document_id'  => $document->id,
            'user_id'      => Auth::id(),
            'action_type'  => 'Saisie',
            'date_action'  => now(),
            'observations' => 'Document enregistré',
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document ajouté avec succès!');
    }

    public function show(Document $document)
    {
        $document->load('trainee.filiere', 'movements.user');
        return view('documents.show', compact('document'));
    }

    public function sortie(Request $request, Document $document)
    {
        $request->validate([
            'action_type'  => 'required|in:Temp_Out,Final_Out',
            'observations' => 'nullable|string',
        ]);

        $document->update([
            'status' => $request->action_type
        ]);

        $deadline = $request->action_type === 'Temp_Out'
            ? now()->addHours(48)
            : null;

        Movement::create([
            'document_id'  => $document->id,
            'user_id'      => Auth::id(),
            'action_type'  => 'Sortie',
            'date_action'  => now(),
            'deadline'     => $deadline,
            'observations' => $request->observations,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document sorti avec succès!');
    }

    public function retour(Request $request, Document $document)
    {
        $document->update(['status' => 'Stock']);

        Movement::create([
            'document_id'  => $document->id,
            'user_id'      => Auth::id(),
            'action_type'  => 'Retour',
            'date_action'  => now(),
            'observations' => $request->observations,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document retourné avec succès!');
    }

    // 🔶 Retraits temporaires
    public function tempOut(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');

        $documents = Document::with('trainee.filiere', 'movements')
            ->where('type', 'Bac')
            ->where('status', 'Temp_Out')
            ->whereHas('movements', function($q) {
                $q->where('action_type', 'Sortie')
                  ->where('deadline', '>=', now());
            })
            ->when($request->filiere_id, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('filiere_id', $request->filiere_id)))
            ->when($request->group, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('group', $request->group)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('documents.temp-out', compact('documents', 'filieres', 'groups'));
    }

    // 🔴 Retraits écoulés (NEW)
    public function ecoule(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');

        $documents = Document::with('trainee.filiere', 'movements')
            ->where('type', 'Bac')
            ->where('status', 'Temp_Out')
            ->whereHas('movements', function($q) {
                $q->where('action_type', 'Sortie')
                  ->where('deadline', '<', now());
            })
            ->when($request->filiere_id, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('filiere_id', $request->filiere_id)))
            ->when($request->group, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('group', $request->group)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('documents.ecoule', compact('documents', 'filieres', 'groups'));
    }

    // 🔴 Retraits définitifs (NEW)
    public function finalOut(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');

        $documents = Document::with('trainee.filiere', 'movements')
            ->where('type', 'Bac')
            ->where('status', 'Final_Out')
            ->when($request->filiere_id, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('filiere_id', $request->filiere_id)))
            ->when($request->group, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('group', $request->group)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('documents.final-out', compact('documents', 'filieres', 'groups'));
    }

    public function prets()
    {
        $documents = Document::with('trainee.filiere')
            ->where('type', 'Diplome')
            ->where('status', 'Stock')
            ->latest()
            ->paginate(15);

        return view('documents.prets', compact('documents'));
    }
}