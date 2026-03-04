<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lost & Found Items table
 * Menyimpan barang hilang dan ditemukan
 * 
 * Reason: Dua skenario workflow
 * 1. User lapor hilang → Admin tandai ditemukan → User claim → Closed
 * 2. Admin input ditemukan → User klaim → Verifikasi → Closed
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('reported_by');
            $table->string('item_name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('location_found')->nullable();
            $table->enum('type', ['lost', 'found'])->default('lost');
            $table->enum('status', ['active', 'claimed', 'closed'])->default('active');
            $table->unsignedBigInteger('claimed_by')->nullable();
            $table->dateTime('claimed_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('reported_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('claimed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_items');
    }
};
