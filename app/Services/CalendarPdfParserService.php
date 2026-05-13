<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CalendarPdfParserService
{
    /**
     * Parse a PDF file and extract calendar events.
     *
     * @param string $filePath Full path to the PDF file
     * @return array Array of extracted events
     */
    public function parse(string $filePath): array
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        return $this->extractEvents($text);
    }

    protected function extractEvents(string $text): array
    {
        $events = [];

        // Normalize line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = explode("\n", $text);

        // Indonesian month names mapping
        $monthMap = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
            'january' => 1, 'february' => 2, 'march' => 3, 'may' => 5,
            'june' => 6, 'july' => 7, 'august' => 8, 'october' => 10,
            'december' => 12,
        ];

        $monthNames = implode('|', array_keys($monthMap));

        // Expanded Category Keywords
        $categoryKeywords = [
            'Libur Akademik' => ['libur', 'cuti', 'holiday', 'recess', 'vacation', 'istirahat', 'long weekend', 'sabbath'],
            'Masa Tidak Boleh Berorganisasi' => ['tidak boleh berorganisasi', 'freeze', 'moratorium', 'dilarang berorganisasi'],
            'Event Kampus Besar' => ['dies natalis', 'graduation', 'wisuda', 'konser', 'festival', 'pekan', 'week', 'camp meeting', 'spiritual', 'retreat', 'consecration', 'field school', 'faculty week'],
            'Ujian' => ['ujian', 'uts', 'uas', 'exam', 'skripsi'],
            'Kegiatan Akademik' => ['kuliah', 'perkuliahan', 'registrasi', 'pendaftaran', 'akademik', 'semester', 'bimbingan', 'praktikum', 'krs', 'ospek', 'orientasi', 'remedial', 'yudisium', 'add & drop', 'evaluasi', 'presensi', 'daring', 'terbimbing'],
        ];

        // 1. Detect Year from Text
        $defaultYear = date('Y');
        if (preg_match('/(?:KALENDER|CALENDAR).*?(\d{4})/i', $text, $ym)) {
            $defaultYear = (int) $ym[1];
        }

        foreach ($lines as $i => $line) {
            $line = trim($line);
            
            // 2. Skip Noise
            if (empty($line) || strlen($line) < 5) continue;
            // Skip lines that are just numbers/spaces
            if (preg_match('/^[\d\s]+$/', $line)) continue;
            // Skip days of week lines
            if (preg_match('/^(Min|Sen|Sel|Rab|Kam|Jum|Sab|Sun|Mon|Tue|Wed|Thu|Fri|Sat)\b/i', $line)) continue;

            $matched = false;
            $parsedEvents = []; // To support multiple events per line

            // A. POLA RENTANG TANPA SIMBOL "-" ATAU DENGAN "-"
            // Format: "5 Januari 8 Januari Pendaftaran Kuliah" atau "5 Januari 8 Mei Masa Perkuliahan" atau "1-5 Januari"
            // Kami gabung deteksi rentang jadi lebih fleksibel
            if (preg_match('/^(\d{1,2})\s*[-–]?\s*(' . $monthNames . ')?\s*[-–]?\s*(\d{1,2})\s+(' . $monthNames . ')\s+(.+)$/iu', $line, $m)) {
                $dayStart = (int) $m[1];
                $monthStartStr = strtolower($m[2]);
                $dayEnd = (int) $m[3];
                $monthEndStr = strtolower($m[4]);
                $title = trim($m[5]);

                // Jika bulan awal kosong, pakai bulan akhir
                $monthStart = !empty($monthStartStr) ? ($monthMap[$monthStartStr] ?? null) : ($monthMap[$monthEndStr] ?? null);
                $monthEnd = $monthMap[$monthEndStr] ?? null;

                if ($monthStart && $monthEnd) {
                    $startDate = $this->safeDate($defaultYear, $monthStart, $dayStart);
                    $endDate = $this->safeDate($defaultYear, $monthEnd, $dayEnd);
                    $parsedEvents[] = ['start' => $startDate, 'end' => $endDate, 'title' => $title];
                    $matched = true;
                }
            }

            // B. POLA MULTI-TANGGAL
            // Format: "20, 23, & 24 Maret Libur"
            if (!$matched && preg_match('/^([\d,\s&]+)\s+(' . $monthNames . ')\s+(.+)$/iu', $line, $m)) {
                $datesStr = $m[1];
                $monthStr = strtolower($m[2]);
                $title = trim($m[3]);
                
                // Cek apakah string tanggal memiliki lebih dari satu angka
                preg_match_all('/\d{1,2}/', $datesStr, $dayMatches);
                $days = $dayMatches[0];
                
                if (count($days) > 1) {
                    $month = $monthMap[$monthStr] ?? null;
                    if ($month) {
                        foreach ($days as $d) {
                            $date = $this->safeDate($defaultYear, $month, (int)$d);
                            $parsedEvents[] = ['start' => $date, 'end' => $date, 'title' => $title];
                        }
                        $matched = true;
                    }
                }
            }

            // C. POLA SINGLE DATE NORMAL
            // Format: "5 Januari Pendaftaran Kuliah"
            if (!$matched && preg_match('/^(\d{1,2})\s+(' . $monthNames . ')\s+(.+)$/iu', $line, $m)) {
                $day = (int) $m[1];
                $monthStr = strtolower($m[2]);
                $title = trim($m[3]);
                
                $month = $monthMap[$monthStr] ?? null;
                if ($month) {
                    $date = $this->safeDate($defaultYear, $month, $day);
                    $parsedEvents[] = ['start' => $date, 'end' => $date, 'title' => $title];
                    $matched = true;
                }
            }
            
            // D. POLA ISO "2026-01-05 Title"
            if (!$matched && preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\s*[-–:;]?\s*(.*)$/u', $line, $m)) {
                $year = (int) $m[1];
                $month = (int) $m[2];
                $day = (int) $m[3];
                $title = trim($m[4]);
                if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    $date = $this->safeDate($year, $month, $day);
                    $parsedEvents[] = ['start' => $date, 'end' => $date, 'title' => $title];
                    $matched = true;
                }
            }

            if (!$matched || empty($parsedEvents)) continue;

            // Proses semua event yang berhasil diparsing
            foreach ($parsedEvents as $pe) {
                $title = $pe['title'];
                $startDate = $pe['start'];
                $endDate = $pe['end'];

                if (!$startDate) continue;

                // Multiline description / title continuation
                if (empty($title) && isset($lines[$i + 1])) {
                    $nextLine = trim($lines[$i + 1]);
                    if (!preg_match('/^\d/', $nextLine) && strlen($nextLine) > 3 && strlen($nextLine) < 200) {
                        $title = $nextLine;
                    }
                }

                $title = preg_replace('/^[-–:;,.\s]+/', '', $title);
                $title = trim($title);

                if (empty($title) || strlen($title) < 3) continue;

                // Detect category
                $category = 'Kegiatan Akademik'; // default
                $lowerTitle = strtolower($title);
                $lowerLine = strtolower($line);

                foreach ($categoryKeywords as $cat => $keywords) {
                    foreach ($keywords as $kw) {
                        if (Str::contains($lowerTitle, $kw) || Str::contains($lowerLine, $kw)) {
                            $category = $cat;
                            break 2;
                        }
                    }
                }

                $events[] = [
                    'title' => Str::limit($title, 255),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'category' => $category,
                    'location' => null,
                    'organizer' => 'Import PDF',
                    'description' => 'Auto-imported from PDF calendar',
                    'raw_line' => Str::limit($line, 500),
                ];
            }
        }

        // Deduplicate by title + start_date
        $unique = [];
        foreach ($events as $event) {
            $key = strtolower($event['title']) . '|' . $event['start_date'];
            if (!isset($unique[$key])) {
                $unique[$key] = $event;
            }
        }

        return array_values($unique);
    }

    /**
     * Safely create a date string, handling invalid day/month combos.
     */
    protected function safeDate(int $year, int $month, int $day): ?string
    {
        try {
            $day = min($day, Carbon::create($year, $month, 1)->daysInMonth);
            return Carbon::create($year, $month, $day)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
