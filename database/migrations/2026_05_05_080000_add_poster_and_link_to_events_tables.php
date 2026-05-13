<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('poster_path')->nullable()->after('title');
            $table->string('registration_link')->nullable()->after('location');
        });

        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->string('poster_path')->nullable()->after('title');
            $table->string('registration_link')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['poster_path', 'registration_link']);
        });

        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->dropColumn(['poster_path', 'registration_link']);
        });
    }
};
