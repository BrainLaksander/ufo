<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('type', ['proposal', 'laporan', 'dokumen'])->default('proposal');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'revision'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('submissions');
    }
};
