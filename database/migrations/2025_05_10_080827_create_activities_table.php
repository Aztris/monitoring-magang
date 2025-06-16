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
        Schema::create('activities', function (Blueprint $table) {
            $table->id(); // id: primary key, auto-increment
            $table->foreignId('internship_id')->constrained()->onDelete('cascade'); // foreign key to internships table
            $table->date('date'); // date: tanggal kegiatan
            $table->string('title'); // title: judul kegiatan
            $table->text('description')->nullable(); // description: deskripsi kegiatan
            $table->string('activity_photo')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending'); // verification_status: status verifikasi
            // $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // foreign key to users table
            // $table->timestamp('verified_at')->nullable(); // verified_at: waktu verifikasi
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
