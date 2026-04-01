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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
        $table->enum('type', ['Bac', 'Diplome', 'Attestation', 'Bulletin']);
        $table->enum('level_year', [1, 2])->nullable();
        $table->enum('status', ['Stock', 'Temp_Out', 'Final_Out', 'Remis']);
        $table->string('reference_number')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
