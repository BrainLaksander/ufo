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

        // Register uiText helper function globally
        if (!function_exists('uiText')) {
            $getUiText = function (string $code): string {
                try {
                    $label = DB::table('workflow_reference_values')
                        ->where('domain', 'ui_text')
                        ->where('code', $code)
                        ->where('is_active', true)
                        ->value('label');

                    if (!empty($label)) {
                        return (string) $label;
                    }
                } catch (\Throwable $e) {
                    // Database error, continue to fallback
                }

                return config("ui_text.ui_text.{$code}", '');
            };

            // Make it available to all views
            \Illuminate\Support\Facades\View::share('uiText', $getUiText);
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
