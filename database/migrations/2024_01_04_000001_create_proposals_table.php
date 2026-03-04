<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Proposals table (Pengajuan)
 * Menyimpan pengajuan dari organisasi (dana, acara, dll)
 * 
 * Reason: Pengajuan perlu approval workflow lengkap dengan catatan
 * Skenario: Organisasi submit → Admin review → approve/reject dengan alasan
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('submitted_by');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['dana', 'acara', 'fasilitas', 'lainnya'])->default('lainnya');
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft');
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('review_notes')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('submitted_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
