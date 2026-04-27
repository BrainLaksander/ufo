<?php

use App\Services\Kemahasiswaan\AnnouncementEmailService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('server:migrate {--create-db : Force create database if it does not exist (MySQL/MariaDB)} {--seed : Run seeder after migrations}', function () {
    $connectionName = (string) config('database.default', '');
    $connection = config("database.connections.{$connectionName}", []);
    $isLocal = app()->environment('local');
    $isCreateDbRequested = (bool) $this->option('create-db');
    $shouldCreateDb = $isCreateDbRequested || $isLocal;
    $identifierPattern = '/^[A-Za-z0-9_]+$/';

    $sanitizeMysqlIdentifier = static function (string $value, string $fallback) use ($identifierPattern): string {
        return preg_match($identifierPattern, $value) ? $value : $fallback;
    };

    $createDatabaseIfNeeded = function (array $connection) use ($identifierPattern, $sanitizeMysqlIdentifier): int {
        $driver = (string) ($connection['driver'] ?? '');

        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            $this->warn('Skipping database creation: --create-db only supports MySQL/MariaDB.');

            return self::SUCCESS;
        }

        $database = (string) ($connection['database'] ?? '');

        if ($database === '' || !preg_match($identifierPattern, $database)) {
            $this->error('Invalid DB_DATABASE value. Use only letters, numbers, and underscore.');

            return self::FAILURE;
        }

        $host = (string) ($connection['host'] ?? '127.0.0.1');
        $port = (string) ($connection['port'] ?? '3306');
        $username = (string) ($connection['username'] ?? 'root');
        $password = (string) ($connection['password'] ?? '');
        $socket = (string) ($connection['unix_socket'] ?? '');
        $charset = $sanitizeMysqlIdentifier((string) ($connection['charset'] ?? 'utf8mb4'), 'utf8mb4');
        $collation = $sanitizeMysqlIdentifier((string) ($connection['collation'] ?? 'utf8mb4_unicode_ci'), 'utf8mb4_unicode_ci');

        try {
            $dsn = $socket !== ''
                ? "mysql:unix_socket={$socket};charset={$charset}"
                : "mysql:host={$host};port={$port};charset={$charset}";

            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $safeDatabase = str_replace('`', '``', $database);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$safeDatabase}` CHARACTER SET {$charset} COLLATE {$collation}");

            $this->info("Database [{$database}] is ready.");
        } catch (\Throwable $e) {
            $this->error('Failed to create database automatically.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    };

    if (!is_array($connection) || $connection === []) {
        $this->error("Database connection [{$connectionName}] is not configured.");

        return self::FAILURE;
    }

    if ($shouldCreateDb) {
        if ($isLocal && !$isCreateDbRequested) {
            $this->info('Local environment detected: database auto-create is enabled.');
        }

        $createDbExitCode = $createDatabaseIfNeeded($connection);

        if ($createDbExitCode !== self::SUCCESS) {
            return $createDbExitCode;
        }
    }

    $this->info('Running migrations...');
    $migrateExitCode = $this->call('migrate', ['--force' => true]);

    if ($migrateExitCode !== self::SUCCESS) {
        return $migrateExitCode;
    }

    if ((bool) $this->option('seed')) {
        $this->info('Running seeders...');
        $seedExitCode = $this->call('db:seed', ['--force' => true]);

        if ($seedExitCode !== self::SUCCESS) {
            return $seedExitCode;
        }
    }

    $this->info('Server migration finished successfully.');

    return self::SUCCESS;
})->purpose('Prepare a new server by creating database (optional), then running migrate and optional seed');

Artisan::command('calendar:import {path=data/calendar.json : Relative path to JSON file} {--truncate : Refresh imported data by clearing previously imported calendar sync rows first} {--purge-events-history : Hard delete all synced event history before importing}', function () {
    if (!Schema::hasTable('kemahasiswaan_schedules')) {
        $this->error('Table kemahasiswaan_schedules tidak ditemukan. Jalankan migrasi dulu.');

        return self::FAILURE;
    }

    $relativePath = (string) $this->argument('path');
    $absolutePath = base_path($relativePath);

    if (!File::exists($absolutePath)) {
        $this->error("File JSON tidak ditemukan: {$relativePath}");

        return self::FAILURE;
    }

    $decoded = json_decode((string) File::get($absolutePath), true);
    if (!is_array($decoded)) {
        $this->error('Format JSON tidak valid. Pastikan root berupa array.');

        return self::FAILURE;
    }

    $rows = collect($decoded)
        ->filter(fn ($item) => is_array($item))
        ->values();

    if ($rows->isEmpty()) {
        $this->warn('Tidak ada data untuk diimpor.');

        return self::SUCCESS;
    }

    $categoryMap = [
        'academic' => 'acad',
        'exam' => 'acad',
        'holiday' => 'holiday',
        'admission' => 'campus',
        'organization' => 'org',
        'org' => 'org',
        'restricted' => 'restricted',
        'campus' => 'campus',
    ];

    $today = now()->startOfDay();
    $syncMarker = 'calendar_sync:json';

    $scheduleInserted = 0;
    $scheduleUpdated = 0;
    $announcementInserted = 0;
    $announcementUpdated = 0;
    $eventInserted = 0;
    $eventUpdated = 0;
    $skipped = 0;

    $normalizedRows = [];

    $bemOrganizationId = null;
    if (Schema::hasTable('organizations')) {
        $bemOrganizationId = DB::table('organizations')
            ->where(function ($query) {
                $query->whereRaw('LOWER(name) like ?', ['%bem%'])
                    ->orWhereRaw('LOWER(shortname) like ?', ['%bem%']);
            })
            ->orderBy('id')
            ->value('id');

        if (!$bemOrganizationId) {
            $bemOrganizationId = DB::table('organizations')->orderBy('id')->value('id');
        }
    }

    $bemUkmAccountId = null;
    if ($bemOrganizationId && Schema::hasTable('kemahasiswaan_ukm_accounts')) {
        $bemUkmAccountId = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $bemOrganizationId)
            ->orderBy('id')
            ->value('id');
    }

    DB::beginTransaction();

    try {
        if ((bool) $this->option('truncate')) {
            DB::table('kemahasiswaan_schedules')->delete();

            if (Schema::hasTable('kemahasiswaan_announcements')) {
                DB::table('kemahasiswaan_announcements')
                    ->where('content', 'like', '%' . $syncMarker . '%')
                    ->delete();
            }

            if (Schema::hasTable('events')) {
                $eventsQuery = DB::table('events')
                    ->where(function ($query) use ($syncMarker) {
                        $query->where('description', 'like', '%' . $syncMarker . '%')
                            ->orWhere('name', 'like', '[Kalender] %');
                    });

                if ((bool) $this->option('purge-events-history')) {
                    $eventsQuery->delete();
                } elseif (Schema::hasColumn('events', 'deleted_at')) {
                    $eventsQuery->update([
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $eventsQuery->delete();
                }
            }

            $this->info('Mode --truncate: data kalender sebelumnya telah diganti penuh untuk proses re-import.');
        }

        foreach ($rows as $index => $row) {
            $title = trim((string) ($row['title'] ?? ''));
            $startRaw = trim((string) ($row['start'] ?? ''));
            $endRaw = trim((string) ($row['end'] ?? $startRaw));

            if ($title === '' || $startRaw === '') {
                $skipped++;
                $this->warn('Baris #' . ($index + 1) . ' dilewati (title/start kosong).');
                continue;
            }

            try {
                $startAt = \Carbon\Carbon::parse($startRaw)->startOfDay();
                $endAt = \Carbon\Carbon::parse($endRaw !== '' ? $endRaw : $startRaw)->endOfDay();
            } catch (\Throwable) {
                $skipped++;
                $this->warn('Baris #' . ($index + 1) . ' dilewati (format tanggal tidak valid).');
                continue;
            }

            $sourceCategory = Str::lower(trim((string) ($row['category'] ?? '')));
            $mappedCategory = $categoryMap[$sourceCategory] ?? 'campus';

            $status = 'planned';
            if ($endAt->lt($today)) {
                $status = 'completed';
            } elseif ($startAt->lte($today) && $endAt->gte($today)) {
                $status = 'ongoing';
            }

            $description = 'Imported from calendar.json | ' . $syncMarker;
            if ($sourceCategory !== '') {
                $description .= ' | source_category: ' . $sourceCategory;
            }

            $normalizedRows[] = [
                'title' => $title,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'source_category' => $sourceCategory,
                'mapped_category' => $mappedCategory,
                'status' => $status,
                'description' => $description,
                'location' => 'Kalender Akademik Kampus',
            ];

            $payload = [
                'organization_id' => null,
                'title' => $title,
                'category' => $mappedCategory,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'location' => 'Kalender Akademik Kampus',
                'description' => $description,
                'status' => $status,
                'created_by' => null,
                'updated_at' => now(),
            ];

            $existing = DB::table('kemahasiswaan_schedules')
                ->where('organization_id', null)
                ->where('title', $title)
                ->whereDate('start_at', $startAt->toDateString())
                ->whereDate('end_at', $endAt->toDateString())
                ->first();

            if ($existing) {
                DB::table('kemahasiswaan_schedules')
                    ->where('id', $existing->id)
                    ->update($payload);
                $scheduleUpdated++;
                continue;
            }

            $payload['created_at'] = now();
            DB::table('kemahasiswaan_schedules')->insert($payload);
            $scheduleInserted++;
        }

        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $hasSubmitAction = Schema::hasColumn('kemahasiswaan_announcements', 'submit_action');

            foreach ($normalizedRows as $item) {
                $summary = Str::limit($item['title'], 150);
                $content = implode(PHP_EOL, [
                    '[' . $syncMarker . ']',
                    'Kategori: ' . ($item['source_category'] !== '' ? $item['source_category'] : $item['mapped_category']),
                    'Tanggal: ' . $item['start_at']->toDateString() . ' s/d ' . $item['end_at']->toDateString(),
                    'Lokasi: ' . $item['location'],
                    '',
                    $item['description'],
                ]);

                $announcementPayload = [
                    'ukm_account_id' => $bemUkmAccountId,
                    'title' => '[Kalender] ' . $item['title'],
                    'category' => 'campus_calendar',
                    'target_audience' => 'all',
                    'summary' => $summary,
                    'content' => $content,
                    'publish_at' => $item['start_at']->copy()->startOfDay(),
                    'publish_status' => 'published',
                    'email_review_status' => 'approved',
                    'email_review_note' => 'Auto approved by calendar importer',
                    'updated_at' => now(),
                ];

                if ($hasSubmitAction) {
                    $announcementPayload['submit_action'] = 'publish_now';
                }

                $existingAnnouncement = DB::table('kemahasiswaan_announcements')
                    ->where('title', '[Kalender] ' . $item['title'])
                    ->whereDate('publish_at', $item['start_at']->toDateString())
                    ->where('content', 'like', '%' . $syncMarker . '%')
                    ->first();

                if ($existingAnnouncement) {
                    DB::table('kemahasiswaan_announcements')
                        ->where('id', $existingAnnouncement->id)
                        ->update($announcementPayload);
                    $announcementUpdated++;
                } else {
                    $announcementPayload['created_at'] = now();
                    DB::table('kemahasiswaan_announcements')->insert($announcementPayload);
                    $announcementInserted++;
                }
            }
        }

        if (Schema::hasTable('events') && $bemOrganizationId) {
            $eventStatus = 'approved';
            $hasCurrentParticipants = Schema::hasColumn('events', 'current_participants');
            $hasParticipantsCount = Schema::hasColumn('events', 'participants_count');
            $hasCreatedBy = Schema::hasColumn('events', 'created_by');
            $hasInternalNotes = Schema::hasColumn('events', 'internal_notes');
            $hasDeletedAt = Schema::hasColumn('events', 'deleted_at');
            $defaultMemberId = null;

            if ($hasCreatedBy && Schema::hasTable('members')) {
                $defaultMemberId = DB::table('members')
                    ->where('organization_id', $bemOrganizationId)
                    ->orderBy('id')
                    ->value('id');
            }

            if ($hasCreatedBy && !$defaultMemberId) {
                $this->warn('Sinkronisasi events dilewati: kolom created_by wajib tetapi member organisasi default tidak ditemukan.');
            } else {

                foreach ($normalizedRows as $item) {
                $eventDescription = $item['description'];
                $eventPayload = [
                    'organization_id' => $bemOrganizationId,
                    'name' => '[Kalender] ' . $item['title'],
                    'description' => $eventDescription,
                    'start_date' => $item['start_at']->copy()->startOfDay(),
                    'end_date' => $item['end_at']->copy()->endOfDay(),
                    'location' => $item['location'],
                    'quota' => 9999,
                    'status' => $eventStatus,
                    'updated_at' => now(),
                ];

                if ($hasCurrentParticipants) {
                    $eventPayload['current_participants'] = 0;
                }

                if ($hasParticipantsCount) {
                    $eventPayload['participants_count'] = 0;
                }

                if ($hasInternalNotes) {
                    $eventPayload['internal_notes'] = 'Autogenerated by calendar importer';
                }

                if ($hasCreatedBy) {
                    $eventPayload['created_by'] = $defaultMemberId;
                }

                $existingEventQuery = DB::table('events')
                    ->where('organization_id', $bemOrganizationId)
                    ->where('name', '[Kalender] ' . $item['title'])
                    ->whereDate('start_date', $item['start_at']->toDateString())
                    ->whereDate('end_date', $item['end_at']->toDateString());

                if ($hasDeletedAt) {
                    $existingEventQuery->whereNull('deleted_at');
                }

                $existingEvent = $existingEventQuery->first();

                if ($existingEvent) {
                    DB::table('events')
                        ->where('id', $existingEvent->id)
                        ->update($eventPayload);
                    $eventUpdated++;
                } else {
                    $eventPayload['created_at'] = now();
                    DB::table('events')->insert($eventPayload);
                    $eventInserted++;
                }
            }
            }
        }

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        $this->error('Import gagal: ' . $e->getMessage());

        return self::FAILURE;
    }

    $this->info('Import kalender selesai.');
    $this->line('Schedules baru: ' . $scheduleInserted);
    $this->line('Schedules diperbarui: ' . $scheduleUpdated);
    $this->line('Announcements baru: ' . $announcementInserted);
    $this->line('Announcements diperbarui: ' . $announcementUpdated);
    $this->line('Events baru: ' . $eventInserted);
    $this->line('Events diperbarui: ' . $eventUpdated);
    $this->line('Data dilewati: ' . $skipped);

    if (!$bemOrganizationId && Schema::hasTable('events')) {
        $this->warn('Sinkronisasi events dilewati karena organisasi BEM/default tidak ditemukan.');
    }

    return self::SUCCESS;
})->purpose('Import data kalender JSON ke kemahasiswaan_schedules agar sinkron lintas portal');

Artisan::command('pengumuman:dispatch-scheduled', function () {
    $result = app(AnnouncementEmailService::class)->processScheduledAnnouncements();

    $this->info('Pengumuman terjadwal diproses.');
    $this->line('Dipindai: ' . $result['processed']);
    $this->line('Terkirim: ' . $result['sent']);
    $this->line('Gagal: ' . $result['failed']);
})->purpose('Kirim pengumuman yang jadwal publish-nya sudah jatuh tempo');

Schedule::command('pengumuman:dispatch-scheduled')->everyMinute()->withoutOverlapping();
