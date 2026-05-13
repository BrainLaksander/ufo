<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnouncementMail;

class PublishScheduledAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled announcements that have reached their publish time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $announcements = Announcement::where('status', 'terjadwal')
            ->where('published_at', '<=', now())
            ->get();

        if ($announcements->isEmpty()) {
            $this->info('No scheduled announcements to publish at this time.');
            return;
        }

        foreach ($announcements as $announcement) {
            $announcement->status = 'terpublikasi';
            $announcement->save();

            $emailTarget = $announcement->target;
            if ($emailTarget === 'Semua Mahasiswa') {
                $emailTarget = 'student252@student.unklab.ac.id';
            }

            if (filter_var($emailTarget, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($emailTarget)->send(new AnnouncementMail($announcement));
                    $this->info("Published and sent email for announcement ID: {$announcement->id} to {$emailTarget}");
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Email failed for scheduled announcement: ' . $e->getMessage());
                    $this->error("Failed to send email for announcement ID: {$announcement->id}");
                }
            } else {
                $this->info("Published announcement ID: {$announcement->id} (No valid email target)");
            }
        }
    }
}
