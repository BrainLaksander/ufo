<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lost_found_items')) {
            return;
        }

        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('location_found');
            $table->enum('type', ['lost', 'found'])->default('lost');
            $table->enum('status', ['active', 'claimed', 'closed'])->default('active');
            $table->foreignId('claimed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['organization_id', 'type', 'status'], 'lfi_org_type_status_idx');
            $table->index(['reported_by', 'created_at'], 'lfi_reporter_created_idx');
            $table->index(['claimed_by', 'created_at'], 'lfi_claimer_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_items');
    }
};