<?php

namespace App\Providers;

use App\Models\Core\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer([
            'components.pengurus.sidebar',
            'components.pengurus.burger',
        ], function ($view): void {
            $sessionUser = request()->session()->get('user');
            $organizationId = is_array($sessionUser) ? (int) ($sessionUser['organization_id'] ?? 0) : 0;
            $organizationName = is_array($sessionUser)
                ? trim((string) ($sessionUser['organization_name'] ?? ''))
                : '';
            $organizationLevel = '';

            if ($organizationId > 0 && Schema::hasTable('organizations')) {
                $organization = DB::table('organizations')
                    ->where('id', $organizationId)
                    ->select(['name', 'level'])
                    ->first();

                if ($organization) {
                    $organizationName = trim((string) ($organization->name ?? $organizationName));
                    $organizationLevel = trim((string) ($organization->level ?? ''));
                }
            }

            $view->with('canAccessLostAndFound', Organization::isUniversityBem($organizationName, $organizationLevel));
        });
    }
}
