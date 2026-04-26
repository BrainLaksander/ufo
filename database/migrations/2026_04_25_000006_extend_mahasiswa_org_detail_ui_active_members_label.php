<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        $row = DB::table('workflow_reference_values')
            ->select(['id', 'payload'])
            ->where('domain', 'mahasiswa_public_ui')
            ->where('code', 'organisasi_detail')
            ->first();

        if (!$row) {
            return;
        }

        $payload = [];

        if (is_string($row->payload) && trim($row->payload) !== '') {
            $decoded = json_decode($row->payload, true);
            $payload = is_array($decoded) ? $decoded : [];
        }

        if (!array_key_exists('active_members_label', $payload) || $payload['active_members_label'] === null || $payload['active_members_label'] === '') {
            $payload['active_members_label'] = 'Anggota Aktif';

            DB::table('workflow_reference_values')
                ->where('id', $row->id)
                ->update([
                    'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Intentionally no-op, only adds a non-breaking UI key.
    }
};
