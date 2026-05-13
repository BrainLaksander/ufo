<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('submission_id')->nullable()->after('user_id')->constrained('activity_submissions')->nullOnDelete();
        });

        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('user_id')->constrained('events')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submission_id');
        });
    }
};
