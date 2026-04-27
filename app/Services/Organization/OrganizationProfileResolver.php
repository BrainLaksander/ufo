<?php

namespace App\Services\Organization;

use Illuminate\Support\Str;

class OrganizationProfileResolver
{
    /**
     * Resolve organization profile (type, level, category, field) from name
     */
    public function resolve(string $name, array $input = []): array
    {
        $normalizedName = Str::lower(trim($name));
        $normalizedShortname = Str::lower(trim((string) ($input['shortname'] ?? '')));
        $nameBlob = trim($normalizedName . ' ' . $normalizedShortname);

        $hasBemSignals = Str::contains($nameBlob, [
            'bem',
            'badan eksekutif mahasiswa',
            'senat mahasiswa',
            'dpm',
            'dewan perwakilan mahasiswa',
        ]);

        $hasFacultySignals = Str::contains($nameBlob, [
            'fakultas',
            'himpunan mahasiswa',
            'hima',
            'himaf',
            'himatika',
            'himti',
            'himsi',
            'fkep',
            'fkip',
            'filsafat',
            'fakultas kedokteran',
        ]);

        $resolvedType = $this->resolveType($hasBemSignals, $input);
        $resolvedLevel = $this->resolveLevel($hasFacultySignals, $input);
        $resolvedCategory = $this->resolveCategory($nameBlob, $resolvedType, $input);
        $resolvedField = $this->resolveField($nameBlob, $resolvedType, $resolvedCategory, $input);

        return [
            'category' => $resolvedCategory,
            'type' => $resolvedType,
            'level' => $resolvedLevel,
            'field' => Str::limit($resolvedField, 120, ''),
        ];
    }

    private function resolveType(bool $hasBemSignals, array $input): string
    {
        $resolvedType = Str::upper(trim((string) ($input['type'] ?? '')));
        if (!in_array($resolvedType, ['BEM', 'UKM'], true)) {
            $resolvedType = $hasBemSignals ? 'BEM' : 'UKM';
        }
        return $resolvedType;
    }

    private function resolveLevel(bool $hasFacultySignals, array $input): string
    {
        $resolvedLevel = trim((string) ($input['level'] ?? ''));
        if (!in_array($resolvedLevel, ['Universitas', 'Fakultas', 'Umum'], true)) {
            $resolvedLevel = $hasFacultySignals ? 'Fakultas' : 'Universitas';
        }
        return $resolvedLevel;
    }

    private function resolveCategory(string $nameBlob, string $type, array $input): string
    {
        $resolvedCategory = trim((string) ($input['category'] ?? ''));
        if ($resolvedCategory === '') {
            if ($type === 'BEM') {
                $resolvedCategory = 'BEM';
            } elseif (Str::contains($nameBlob, ['kerohanian', 'rohani', 'ministry', 'pelayanan'])) {
                $resolvedCategory = 'Kerohanian';
            } elseif (Str::contains($nameBlob, ['paduan suara', 'choir', 'musik', 'seni'])) {
                $resolvedCategory = 'Minat & Bakat';
            } elseif (Str::contains($nameBlob, ['kedaerahan', 'ikm', 'ikatan daerah', 'maluku', 'minahasa'])) {
                $resolvedCategory = 'Kedaerahan';
            } elseif (Str::contains($nameBlob, ['teknologi', 'tech', 'it', 'developer', 'coding', 'ai', 'robot', 'pasar modal', 'kspm'])) {
                $resolvedCategory = 'Akademik & Teknologi';
            } else {
                $resolvedCategory = 'UKM Umum';
            }
        }
        return $resolvedCategory;
    }

    private function resolveField(string $nameBlob, string $type, string $category, array $input): string
    {
        $resolvedField = trim((string) ($input['field'] ?? ''));
        if ($resolvedField === '') {
            if ($type === 'BEM') {
                $resolvedField = 'Pemerintahan Mahasiswa';
            } elseif ($category === 'Kerohanian') {
                $resolvedField = 'Kerohanian';
            } elseif ($category === 'Minat & Bakat') {
                $resolvedField = '';
            } elseif ($category === 'Kedaerahan') {
                $resolvedField = 'Organisasi Kedaerahan';
            } elseif ($category === 'Akademik & Teknologi') {
                $resolvedField = '';
            } else {
                $resolvedField = '';
            }
        }
        return $resolvedField;
    }
}
