<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\ActivitySubmission;
use App\Models\Announcement;
use App\Policies\OrganizationPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model policies
        Gate::policy(Organization::class, OrganizationPolicy::class);

        View::composer('layouts.app', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::check()) {
                $user = Auth::user();
                // Use real Laravel database notifications
                $dbNotifications = $user->unreadNotifications()->take(10)->get();
                $unreadCount = $user->unreadNotifications()->count();

                foreach ($dbNotifications as $notif) {
                    $notifications->push((object)[
                        'id' => $notif->id,
                        'title' => $notif->data['title'] ?? 'Notifikasi',
                        'message' => $notif->data['message'] ?? '',
                        'action_url' => $notif->data['action_url'] ?? null,
                        'time' => $notif->created_at->diffForHumans(),
                    ]);
                }
            } else {
                $recentAnnouncements = Announcement::where('status', 'terpublikasi')
                    ->orderBy('published_at', 'desc')
                    ->take(5)
                    ->get();
                foreach ($recentAnnouncements as $ann) {
                    $notifications->push((object)[
                        'id' => 'announcement_' . $ann->id,
                        'title' => 'Pengumuman: ' . $ann->category,
                        'message' => $ann->title,
                        'action_url' => route('pengumuman.index'),
                        'time' => $ann->published_at ? $ann->published_at->diffForHumans() : '',
                    ]);
                }
                $unreadCount = $notifications->count();
            }

            $view->with('notifications', $notifications->isEmpty() ? null : $notifications);
            $view->with('unreadNotifCount', $unreadCount);
        });
    }
}
