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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // $table->string('foto_profil')->nullable();
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenkel', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
