<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Events table
 * Menyimpan data event/kegiatan organisasi
 * 
 * Reason: Event memiliki poster, deskripsi, jadwal, status publikasi
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('created_by');
            $table->string('title');
            $table->text('description');
            $table->string('poster')->nullable();
            $table->dateTime('event_date');
            $table->string('location');
            $table->enum('category', ['rapat', 'event', 'akademik', 'sosial'])->default('event');
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->integer('capacity')->nullable();
            $table->integer('registered')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
