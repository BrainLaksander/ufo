<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $accounts = DB::table('kemahasiswaan_ukm_accounts')->get();
    $count = 0;
    foreach ($accounts as $account) {
        $exists = DB::table('members')
            ->where('organization_id', $account->organization_id)
            ->where('email', $account->email)
            ->exists();
        
        if (!$exists) {
            DB::table('members')->insert([
                'organization_id' => $account->organization_id,
                'name' => $account->name,
                'nim' => 'ADM-' . str_pad((string) $account->organization_id, 6, '0', STR_PAD_LEFT),
                'email' => $account->email,
                'faculty' => 'Universitas',
                'major' => 'Staff UKM',
                'position' => 'ketua',
                'status' => 'aktif',
                'join_type' => 'founder',
                'join_date' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }
    }
    echo "Backfilled $count members for existing UKM accounts.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
