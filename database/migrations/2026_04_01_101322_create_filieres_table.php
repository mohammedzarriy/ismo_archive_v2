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
    Schema::create('filieres', function (Blueprint $table) {
        $table->id();
        $table->foreignId('secteur_id')->constrained()->cascadeOnDelete();
        $table->string('code_filiere');
        $table->string('nom_filiere');
        $table->enum('niveau', ['TS', 'T', 'Q', 'S', 'BP']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
