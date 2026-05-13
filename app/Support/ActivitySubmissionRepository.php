<?php

namespace App\Support;

use App\Models\ActivitySubmission;
use App\Models\Event as OrgEvent;

class ActivitySubmissionRepository
{
    /**
     * Return submissions; if none exist in DB, fall back to static examples.
     *
     * @return array
     */
    public static function submissions(): array
    {
        try {
            $rows = ActivitySubmission::orderBy('created_at', 'desc')->get();
            if ($rows->isEmpty()) {
                return [];
            }

            return $rows->map(function ($r) {
                return [
                    'id' => $r->id,
                    'title' => $r->title,
                    'subtitle' => $r->subtitle,
                    'organization' => $r->organization_id,
                    'event_date' => optional($r->event_date)->toDateString(),
                    'status' => $r->status,
                    'status_label' => ucfirst($r->status),
                    'kind' => $r->kind,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return self::staticSubmissions();
        }
    }

    /**
     * Return events for pengurus; fallback to static examples.
     *
     * @return array
     */
    public static function pengurusEvents(): array
    {
        try {
            $rows = OrgEvent::orderBy('start_at', 'desc')->get();
            if ($rows->isEmpty()) {
                return [];
            }

            return $rows->map(function ($r) {
                return [
                    'id' => $r->id,
                    'title' => $r->title,
                    'event_date_label' => optional($r->start_at)->format('d F Y'),
                    'time_range' => optional($r->start_at)->format('H:i') . ' - ' . optional($r->end_at)->format('H:i'),
                    'location' => $r->location,
                    'participants' => $r->participants,
                    'status' => $r->status,
                    'status_label' => ucfirst($r->status),
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return self::staticEvents();
        }
    }

    protected static function staticSubmissions(): array
    {
        return [];
    }

    protected static function staticEvents(): array
    {
        return [];
    }
}

