<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $message;
    protected string $type;
    protected string $icon;
    protected ?string $actionUrl;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string $type      One of: pengajuan_kegiatan, revisi_kegiatan, laporan_masuk, status_update, pengumuman, informasi_penting
     * @param string $icon      One of: document, edit, report, message, users, info
     * @param string|null $actionUrl
     */
    public function __construct(string $title, string $message, string $type = 'informasi_penting', string $icon = 'info', ?string $actionUrl = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->icon = $icon;
        $this->actionUrl = $actionUrl;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'icon' => $this->icon,
            'action_url' => $this->actionUrl,
        ];
    }
}
