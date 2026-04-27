<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('organizations')) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('shortname')->unique();
                $table->string('logo')->nullable();
                $table->string('banner')->nullable();
                $table->text('description')->nullable();
                $table->text('vision')->nullable();
                $table->text('mission')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('instagram')->nullable();
                $table->string('line_id')->nullable();
                $table->enum('profile_status', ['lengkap', 'belum_lengkap'])->default('belum_lengkap');
                $table->integer('profile_completion_percentage')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });

            return;
        }

        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'line_id')) {
                $table->string('line_id')->nullable();
            }

            if (!Schema::hasColumn('organizations', 'profile_completion_percentage')) {
                $table->integer('profile_completion_percentage')->default(0);
            }

            if (!Schema::hasColumn('organizations', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void {
        Schema::dropIfExists('organizations');
    }
};
