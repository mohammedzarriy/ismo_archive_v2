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

        $status = $request->type === 'Bac'
            ? ($request->bac_status ?? 'Temp_Out')
            : 'Stock';

        $document = Document::create([
            'trainee_id'       => $request->trainee_id,
            'type'             => $request->type,
            'level_year'       => $request->level_year,
            'status'           => $status,
            'reference_number' => $request->reference_number,
        ]);

        $actionType = ($request->type === 'Bac' && $status !== 'Stock')
            ? 'Sortie'
            : 'Saisie';

        $deadline = ($status === 'Temp_Out') ? now()->addHours(48) : null;

        Movement::create([
            'document_id'  => $document->id,
            'user_id'      => Auth::id(),
            'action_type'  => $actionType,
            'date_action'  => now(),
            'deadline'     => $deadline,
            'observations' => match ($status) {
                'Temp_Out'  => 'Retrait temporaire (48h)',
                'Final_Out' => 'Retrait définitif',
                default     => 'Document enregistré',
            },
        ]);

        return match ($status) {
            'Temp_Out'  => redirect()->route('documents.bac.temp-out')
                ->with('success', 'Bac en retrait temporaire ✅'),

            'Final_Out' => redirect()->route('documents.bac.final-out')
                ->with('success', 'Bac en retrait définitif ✅'),

            default => redirect()->route('documents.index')
                ->with('success', 'Document ajouté ✅'),
        };
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
            ->with('success', 'Sortie enregistrée ✅');
    }

    public function retour(Request $request, Document $document)
    {
        $document->update(['status' => 'Stock']);

        Movement::create([
            'document_id'  => $document->id,
            'user_id'      => Auth::id(),
            'action_type'  => 'Retour',
            'date_action'  => now(),
            'observations' => $request->observations ?? 'Retour du document',
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Retour effectué ✅');
    }

    // 🟡 Retraits temporaires (UPDATED)
    public function tempOut(Request $request)
    {
        $filieres = Filiere::all();

        $groups = Trainee::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        $years = Trainee::select('graduation_year')
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year');

        $annees_etude = Trainee::select('annee_etude')
            ->whereNotNull('annee_etude')
            ->distinct()
            ->orderBy('annee_etude')
            ->pluck('annee_etude');

        $documents = Document::with(['trainee.filiere', 'movements'])
            ->where('type', 'Bac')
            ->where('status', 'Temp_Out')
<<<<<<< HEAD
            ->whereHas('movements', function($q) {
                $q->where('action_type', 'Sortie')
                  ->where('deadline', '>=', now());
            })
=======

>>>>>>> c60b001b9a0eea696220532c2e90993880ff71ce
            ->when($request->filiere_id, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('filiere_id', $request->filiere_id)))

            ->when($request->group, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('group', $request->group)))

            ->when($request->graduation_year, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('graduation_year', $request->graduation_year)))

            ->when($request->annee_etude, fn($q) =>
                $q->whereHas('trainee', fn($q) =>
                    $q->where('annee_etude', $request->annee_etude)))

            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('documents.temp-out', compact(
            'documents',
            'filieres',
            'groups',
            'years',
            'annees_etude'
        ));
    }

<<<<<<< HEAD
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
=======
    // 🔴 Retraits définitifs
>>>>>>> c60b001b9a0eea696220532c2e90993880ff71ce
    public function finalOut(Request $request)
    {
        $filieres = Filiere::all();
        $groups   = Trainee::distinct()->pluck('group');

        $documents = Document::with('trainee.filiere')
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

    // 🎓 Diplômes en stock
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