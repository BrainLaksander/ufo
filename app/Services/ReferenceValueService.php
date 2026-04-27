<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReferenceValueService
{
    private array $referenceCache = [];

    /**
     * Get reference domain map (code => [label, payload, ...])
     */
    public function getMap(string $domain): array
    {
        if (array_key_exists($domain, $this->referenceCache)) {
            return $this->referenceCache[$domain];
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            return [];
        }

        $rows = DB::table('workflow_reference_values')
            ->where('domain', '=', $domain)
            ->select(['code', 'label', 'payload'])
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $payload = [];
            if ($row->payload) {
                $decoded = json_decode((string) $row->payload, true);
                $payload = is_array($decoded) ? $decoded : [];
            }

            $map[(string) $row->code] = [
                'label' => $row->label,
                'payload' => $payload,
            ];
        }

        $this->referenceCache[$domain] = $map;

        return $map;
    }

    /**
     * Get label for code in domain
     */
    public function getLabel(string $domain, string $code): string
    {
        $map = $this->getMap($domain);
        return (string) (($map[$code]['label'] ?? ''));
    }

    /**
     * Get payload for code in domain
     */
    public function getPayload(string $domain, string $code): array
    {
        $map = $this->getMap($domain);
        $payload = $map[$code]['payload'] ?? [];
        return is_array($payload) ? $payload : [];
    }

    /**
     * Get status options from reference domain
     */
    public function getStatusOptions(string $domain): array
    {
        $map = $this->getMap($domain);
        return array_keys($map);
    }

    /**
     * Clear cache for specific domain or all
     */
    public function clearCache(?string $domain = null): void
    {
        if ($domain) {
            unset($this->referenceCache[$domain]);
        } else {
            $this->referenceCache = [];
        }
    }
}
