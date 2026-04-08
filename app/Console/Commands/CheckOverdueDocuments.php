<?php
// app/Console/Commands/CheckOverdueDocuments.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use App\Models\Movement;
use Carbon\Carbon;

class CheckOverdueDocuments extends Command
{
    protected $signature   = 'documents:check-overdue';
    protected $description = 'Move Temp_Out documents past 48h to Ecoule';

    public function handle(): void
    {
        $overdue = Document::where('status', 'Temp_Out')
            ->whereHas('movements', function ($q) {
                $q->where('action_type', 'Sortie')
                  ->where('date_action', '<=', Carbon::now()->subHours(48));
            })
            ->get();

        foreach ($overdue as $doc) {
            $doc->update(['status' => 'Ecoule']);
            $this->info("Document #{$doc->id} → Ecoule");
        }

        $this->info("Done. {$overdue->count()} document(s) updated.");
    }
}