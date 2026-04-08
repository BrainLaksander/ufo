<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Announcements table
 * Menyimpan pengumuman organisasi
 * 
 * Reason: Pengumuman perlu approval workflow, publish schedule, tracking
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('created_by');
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['draft', 'pending', 'approved', 'published', 'rejected'])->default('draft');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
