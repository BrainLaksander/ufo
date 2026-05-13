<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('field')->nullable();
            $table->string('level')->nullable();
            $table->text('description')->nullable();

            // Ketua
            $table->string('chair_name')->nullable();
            $table->string('chair_phone')->nullable();
            $table->string('chair_email')->nullable();

            // Sekretaris
            $table->string('secretary_name')->nullable();
            $table->string('secretary_phone')->nullable();
            $table->string('secretary_email')->nullable();

            // Bendahara
            $table->string('treasurer_name')->nullable();
            $table->string('treasurer_phone')->nullable();
            $table->string('treasurer_email')->nullable();

            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
