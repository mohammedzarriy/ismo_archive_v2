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
        $trainees = Trainee::with('filiere')
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv']
        ]);

        try {
            Excel::import(new TraineesImport, $request->file('file'));

            return back()->with('success', 'Import réussi ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    public function downloadReport(Trainee $trainee)
    {
        $trainee->load([
            'filiere.secteur',
            'documents.movements.user'
        ]);

        $pdf = Pdf::loadView('reports.trainee', [
                'trainee' => $trainee
            ])
            ->setPaper('a4', 'portrait');

        $filename = sprintf(
            'rapport_%s_%s.pdf',
            $trainee->cin,
            now()->format('Ymd')
        );

        return $pdf->download($filename);
    }
}