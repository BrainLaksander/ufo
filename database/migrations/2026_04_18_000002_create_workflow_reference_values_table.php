<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_reference_values', function (Blueprint $table) {
            $table->id();
            $table->string('domain', 120);
            $table->string('code', 120);
            $table->string('label', 255)->nullable();
            $table->json('payload')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['domain', 'code'], 'wrk_ref_domain_code_unique');
            $table->index(['domain', 'is_active'], 'wrk_ref_domain_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_reference_values');
    }
};
