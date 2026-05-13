<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lost_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->enum('type', ['lost', 'found']); // 'lost' (kehilangan), 'found' (menemukan)
            $table->string('title');
            $table->text('description');
            $table->date('date');
            $table->string('location');
            $table->string('contact_person');
            $table->string('contact_phone')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('status', ['active', 'resolved'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_items');
    }
};
