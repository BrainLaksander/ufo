<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $kemahasiswaanUser = User::where('email', 'kemahasiswaan@unklab.ac.id')->first();
        if (!$kemahasiswaanUser) return;

        DB::table('notifications')->where('notifiable_id', $kemahasiswaanUser->id)->delete();

        $notifications = [
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'pengajuan_kegiatan',
                    'title' => 'Pengajuan Kegiatan Baru',
                    'message' => 'HMTI mengajukan kegiatan "Seminar Nasional Teknologi dan Inovasi"',
                    'icon' => 'document'
                ]),
                'created_at' => Carbon::now()->subMinutes(5),
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'pesan_baru',
                    'title' => 'Pesan Baru',
                    'message' => 'HMTI mengirim pesan konsultasi: "Apakah proposal sudah bisa direview?"',
                    'icon' => 'message'
                ]),
                'created_at' => Carbon::now()->subMinutes(30),
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'revisi_kegiatan',
                    'title' => 'Revisi Kegiatan',
                    'message' => 'BEM merevisi proposal "Latihan Dasar Kepemimpinan"',
                    'icon' => 'edit'
                ]),
                'created_at' => Carbon::now()->subHour(),
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'laporan_masuk',
                    'title' => 'Laporan Masuk',
                    'message' => 'UKM Musik telah mengunggah LPJ "Konser Akhir Tahun"',
                    'icon' => 'report'
                ]),
                'created_at' => Carbon::now()->subHours(2),
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'informasi_penting',
                    'title' => 'Informasi Penting',
                    'message' => 'Tenggat waktu pengumpulan LPJ semester ganjil tersisa 3 hari.',
                    'icon' => 'info'
                ]),
                'created_at' => Carbon::now()->subDay(),
                'read_at' => Carbon::now()->subDay(),
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'perubahan_pengurus',
                    'title' => 'Perubahan Pengurus',
                    'message' => 'HIMAKOM telah memperbarui struktur kepengurusan.',
                    'icon' => 'users'
                ]),
                'created_at' => Carbon::now()->subDays(2),
                'read_at' => Carbon::now()->subDays(2),
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'pengajuan_kegiatan',
                    'title' => 'Pengajuan Kegiatan Baru',
                    'message' => 'BEM mengajukan kegiatan "Bakti Sosial Mahasiswa"',
                    'icon' => 'document'
                ]),
                'created_at' => Carbon::now()->subDays(3),
                'read_at' => Carbon::now()->subDays(3),
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'laporan_masuk',
                    'title' => 'Laporan Masuk',
                    'message' => 'HIMAFAR mengunggah LPJ kegiatan "Seminar Kesehatan Dasar"',
                    'icon' => 'report'
                ]),
                'created_at' => Carbon::now()->subDays(4),
                'read_at' => Carbon::now()->subDays(4),
            ],
            [
                'type' => 'App\Notifications\KemahasiswaanNotification',
                'data' => json_encode([
                    'type' => 'perubahan_pengurus',
                    'title' => 'Perubahan Pengurus',
                    'message' => 'UKM Olahraga memperbarui data Ketua dan Bendahara.',
                    'icon' => 'users'
                ]),
                'created_at' => Carbon::now()->subWeeks(1),
                'read_at' => Carbon::now()->subWeeks(1),
            ],
        ];

        foreach ($notifications as $notif) {
            DB::table('notifications')->insert(array_merge($notif, [
                'id' => Str::uuid()->toString(),
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $kemahasiswaanUser->id,
                'updated_at' => Carbon::now()
            ]));
        }
    }
}
