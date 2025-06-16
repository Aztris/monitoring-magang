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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nis', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenkel', ['L', 'P']);
            $table->string('agama', 50)->nullable();
            $table->foreignId('class_room_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('no_hp_ortu', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
