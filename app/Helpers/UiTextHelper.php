<?php

if (!function_exists('uiText')) {
    /**
     * Get UI text from database or config fallback
     */
    function uiText(string $code): string
    {
        // Try database first
        try {
            $label = \Illuminate\Support\Facades\DB::table('workflow_reference_values')
                ->where('domain', 'ui_text')
                ->where('code', $code)
                ->where('is_active', true)
                ->value('label');

            if (!empty($label)) {
                return (string) $label;
            }
        } catch (\Throwable $e) {
            // Database error or table missing, continue to fallback
        }

        // Fallback to config
        return config("ui_text.ui_text.{$code}", '');
    }
}

if (!function_exists('uiTextMap')) {
    /**
     * @param  array<string, string>  $keyCodeMap
     * @return array<string, string>
     */
    function uiTextMap(array $keyCodeMap): array
    {
        $result = [];
        foreach ($keyCodeMap as $key => $code) {
            $result[$key] = uiText((string) $code);
        }

        return $result;
    }
}
