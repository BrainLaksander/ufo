<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->string('jenis_kegiatan')->nullable()->after('title');
            $table->string('penanggung_jawab')->nullable()->after('jenis_kegiatan');
            $table->string('waktu')->nullable()->after('event_date');
            $table->string('lokasi')->nullable()->after('waktu');
            $table->integer('estimasi_peserta')->nullable()->after('lokasi');
            $table->string('proposal_path')->nullable()->after('poster_path');
            $table->string('lpj_path')->nullable()->after('proposal_path');
            $table->text('lpj_catatan')->nullable()->after('lpj_path');
        });
    }

    public function down(): void
    {
        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_kegiatan',
                'penanggung_jawab',
                'waktu',
                'lokasi',
                'estimasi_peserta',
                'proposal_path',
                'lpj_path',
                'lpj_catatan',
            ]);
        });
    }
};
