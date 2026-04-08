<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('name');
            $table->string('nim')->unique();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('division')->nullable();
            $table->enum('position', ['ketua', 'sekretaris', 'bendahara', 'staff'])->default('staff');
            $table->enum('status', ['aktif', 'nonaktif', 'cuti'])->default('aktif');
            $table->date('join_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('members');
    }
};
