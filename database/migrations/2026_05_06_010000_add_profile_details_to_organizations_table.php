<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('motto')->nullable()->after('description');
            $table->text('visi')->nullable()->after('motto');
            $table->text('misi')->nullable()->after('visi');
            $table->text('budaya_nilai')->nullable()->after('misi');
            $table->text('program_kegiatan')->nullable()->after('budaya_nilai');
            $table->string('instagram')->nullable()->after('program_kegiatan');
            $table->string('whatsapp')->nullable()->after('instagram');
            $table->string('website')->nullable()->after('whatsapp');
            $table->integer('member_count')->default(0)->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['motto','visi','misi','budaya_nilai','program_kegiatan','instagram','whatsapp','website','member_count']);
        });
    }
};
