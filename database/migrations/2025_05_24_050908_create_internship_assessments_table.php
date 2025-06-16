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
        Schema::create('internship_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->onDelete('cascade'); // siswa
            $table->foreignId('assessment_criteria_id')->constrained()->onDelete('cascade'); // kriteria
            $table->foreignId('assessor_id')->constrained('users')->onDelete('cascade'); // user company penilai
            $table->unsignedTinyInteger('nilai'); // nilai 0-100 misalnya
            $table->timestamps();

           
            // Define a shorter unique constraint name
            $table->unique(['internship_id', 'assessment_criteria_id'], 'internship_assessment_unique');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_assessments');
    }
};
