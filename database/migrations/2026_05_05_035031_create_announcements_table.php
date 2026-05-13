<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Pengumuman Umum');
            $table->string('target')->default('Semua Organisasi'); // 'Semua Organisasi', 'Organisasi Tertentu', 'Semua Mahasiswa'
            $table->text('content')->nullable();
            $table->string('status')->default('draft'); // 'draft', 'terjadwal', 'terpublikasi'
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
