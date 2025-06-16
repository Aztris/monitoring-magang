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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $table->text('notes')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Composite unique key to prevent duplicate attendance records
            $table->unique(['internship_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
