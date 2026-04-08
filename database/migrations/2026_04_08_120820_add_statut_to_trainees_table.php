<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('trainees', function (Blueprint $table) {
        $table->enum('statut', [
            'en_formation',   // لازال يتدرب
            'diplome',        // نجح وسالا
            'abandon',        // غادر
            'redoublant',     // أعاد السنة
        ])->default('en_formation')->after('graduation_year');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            //
        });
    }
};
