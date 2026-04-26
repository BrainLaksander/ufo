<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kemahasiswaan_schedules')) {
            Schema::table('kemahasiswaan_schedules', function (Blueprint $table) {
                if (!Schema::hasColumn('kemahasiswaan_schedules', 'category')) {
                    $table->string('category', 40)->nullable()->after('title');
                }

                if (!Schema::hasColumn('kemahasiswaan_schedules', 'description')) {
                    $table->longText('description')->nullable()->after('location');
                }
            });

            DB::table('kemahasiswaan_schedules')
                ->whereNull('category')
                ->update([
                    'category' => 'org',
                    'updated_at' => now(),
                ]);
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        $now = now();
        $records = [
            'kmh_calendar_modal_title' => 'Tambah Kegiatan Baru',
            'kmh_calendar_modal_subtitle' => 'Masukkan jadwal kegiatan kampus yang akan muncul di kalender.',
            'kmh_calendar_field_title' => 'Nama Kegiatan',
            'kmh_calendar_field_start_date' => 'Tanggal Mulai',
            'kmh_calendar_field_end_date' => 'Tanggal Selesai',
            'kmh_calendar_field_category' => 'Kategori',
            'kmh_calendar_field_organization' => 'Penyelenggara',
            'kmh_calendar_field_location' => 'Lokasi',
            'kmh_calendar_field_description' => 'Deskripsi',
            'kmh_calendar_field_title_placeholder' => 'Masukkan nama kegiatan',
            'kmh_calendar_field_location_placeholder' => 'Masukkan lokasi kegiatan',
            'kmh_calendar_field_description_placeholder' => 'Masukkan deskripsi kegiatan',
            'kmh_calendar_category_akademik_value' => 'Kegiatan Akademik',
            'kmh_calendar_category_organisasi_value' => 'Kegiatan Organisasi',
            'kmh_calendar_category_masa_tenang_value' => 'Masa Tidak Boleh Berorganisasi',
            'kmh_calendar_category_libur_value' => 'Libur Akademik',
            'kmh_calendar_category_event_besar_value' => 'Event Kampus Besar',
            'kmh_calendar_save_button' => 'Simpan',
            'kmh_calendar_cancel_button' => 'Batal',
        ];

        $sortBase = (int) DB::table('workflow_reference_values')
            ->where('domain', 'ui_text')
            ->max('sort_order');

        $counter = 1;
        foreach ($records as $code => $label) {
            DB::table('workflow_reference_values')->updateOrInsert(
                ['domain' => 'ui_text', 'code' => $code],
                [
                    'label' => $label,
                    'payload' => null,
                    'sort_order' => $sortBase + $counter,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $counter++;
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kemahasiswaan_schedules')) {
            Schema::table('kemahasiswaan_schedules', function (Blueprint $table) {
                if (Schema::hasColumn('kemahasiswaan_schedules', 'description')) {
                    $table->dropColumn('description');
                }

                if (Schema::hasColumn('kemahasiswaan_schedules', 'category')) {
                    $table->dropColumn('category');
                }
            });
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        DB::table('workflow_reference_values')
            ->where('domain', 'ui_text')
            ->whereIn('code', [
                'kmh_calendar_modal_title',
                'kmh_calendar_modal_subtitle',
                'kmh_calendar_field_title',
                'kmh_calendar_field_start_date',
                'kmh_calendar_field_end_date',
                'kmh_calendar_field_category',
                'kmh_calendar_field_organization',
                'kmh_calendar_field_location',
                'kmh_calendar_field_description',
                'kmh_calendar_field_title_placeholder',
                'kmh_calendar_field_location_placeholder',
                'kmh_calendar_field_description_placeholder',
                'kmh_calendar_category_akademik_value',
                'kmh_calendar_category_organisasi_value',
                'kmh_calendar_category_masa_tenang_value',
                'kmh_calendar_category_libur_value',
                'kmh_calendar_category_event_besar_value',
                'kmh_calendar_save_button',
                'kmh_calendar_cancel_button',
            ])
            ->delete();
    }
};
