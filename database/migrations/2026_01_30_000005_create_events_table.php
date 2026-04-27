<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->dateTime('start_date');
                $table->dateTime('end_date')->nullable();
                $table->string('location')->nullable();
                $table->integer('quota')->default(100);
                $table->integer('participants_count')->default(0);
                $table->string('banner')->nullable();
                $table->enum('status', ['draft', 'approved', 'berjalan', 'selesai', 'cancelled'])->default('draft');
                $table->timestamps();
                $table->softDeletes();
            });

            return;
        }

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'participants_count')) {
                $table->integer('participants_count')->default(0);
            }

            if (!Schema::hasColumn('events', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void {
        Schema::dropIfExists('events');
    }
};
