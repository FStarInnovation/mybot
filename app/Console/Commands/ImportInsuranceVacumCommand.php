<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportInsuranceVacumCommand extends Command
{
    protected $signature = 'import:insurance-vacum
        {--url= : XLSX URL}
        {--source=pami : Data source label}
        {--source-date= : Date (YYYY-MM-DD). If omitted, extracted from URL (YYYYMMDD) or today}
        {--sheet= : Sheet name (optional)}
        {--list-sheets : List sheet names and exit}
        {--dump-headers=0 : Print normalized headers for first N rows and exit (0=disabled)}
        {--start-row=1 : Header row index (1-based)}
        {--header-scan-rows=30 : Rows to scan to auto-detect header row}
        {--chunk=500 : Insert chunk size}
        {--connection=neon : DB connection name}';

    protected $description = 'Import PAMI insurance vacum XLSX into insurance_vacum with history (source_date) using staging table';

    public function handle(): int
    {
        $url = (string) $this->option('url');
        if ($url === '') {
            $this->error('Missing --url');
            return Command::FAILURE;
        }

        $source = (string) $this->option('source');
        $sourceDate = $this->resolveSourceDate((string) $this->option('source-date'), $url);
        $connection = (string) $this->option('connection');
        $chunk = max(50, (int) $this->option('chunk'));
        $startRow = max(1, (int) $this->option('start-row'));
        $scanRows = max(1, (int) $this->option('header-scan-rows'));
        $dumpHeaders = max(0, (int) $this->option('dump-headers'));
        $sheetName = $this->option('sheet');

        $batchId = (string) Str::uuid();

        $this->info("Downloading XLSX...");
        $tmpPath = $this->downloadToTemp($url);

        $this->info("Parsing XLSX (batch={$batchId}, source={$source}, source_date={$sourceDate})...");

        $spreadsheet = IOFactory::load($tmpPath);

        if ((bool) $this->option('list-sheets')) {
            $this->info('Sheets:');
            foreach ($spreadsheet->getSheetNames() as $name) {
                $this->line($name);
            }
            return Command::SUCCESS;
        }

        $sheet = $sheetName ? $spreadsheet->getSheetByName((string) $sheetName) : $spreadsheet->getActiveSheet();
        if (!$sheet) {
            $this->error('Sheet not found');
            return Command::FAILURE;
        }

        $highestRow = (int) $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        if ($dumpHeaders > 0) {
            $max = min($highestRow, $dumpHeaders);
            $this->info("Sheet bounds: highestRow={$highestRow}, highestCol={$highestCol}");
            $this->info('Cell A1 raw: ' . $this->stringifyCell($sheet->getCell('A1')->getValue()));
            $this->info("Dumping normalized headers for rows 1..{$max}");
            for ($r = 1; $r <= $max; $r++) {
                $headers = $this->readHeaders($sheet, $highestCol, $r);
                $preview = array_slice($headers, 0, 12, true);
                $pairs = [];
                foreach ($preview as $col => $h) {
                    if ($h !== '') {
                        $pairs[] = "{$col}={$h}";
                    }
                }
                $line = implode(' | ', $pairs);
                $this->line("row {$r}: {$line}");
            }
            return Command::SUCCESS;
        }

        // Auto-detect header row if the provided start row doesn't match expected headers
        $headers = $this->readHeaders($sheet, $highestCol, $startRow);
        $map = $this->buildColumnMap($headers);

        if ($map === []) {
            $detected = $this->detectHeaderRow($sheet, $highestCol, 1, min($highestRow, $scanRows));
            if ($detected !== null && $detected !== $startRow) {
                $startRow = $detected;
                $this->info("Auto-detected header row: {$startRow}");
                $headers = $this->readHeaders($sheet, $highestCol, $startRow);
                $map = $this->buildColumnMap($headers);
            }
        }

        if ($map === []) {
            $this->warn('Could not detect expected headers. First detected header row (normalized):');
            $preview = array_slice($headers, 0, 20, true);
            foreach ($preview as $col => $h) {
                $this->line("{$col}: {$h}");
            }
        }

        $required = ['droga', 'marca', 'presentacion', 'laboratorio', 'cobertura', 'copago'];
        foreach ($required as $key) {
            if (!isset($map[$key])) {
                $this->warn("Column for '{$key}' not found in XLSX headers (normalized). Import will still run, missing values become NULL.");
            }
        }

        $db = DB::connection($connection);

        $stagingInsert = [];
        $upsertRows = [];
        $totalRows = 0;

        for ($r = $startRow + 1; $r <= $highestRow; $r++) {
            $row = $this->readRowValues($sheet, $highestCol, $r);

            // Skip completely empty rows
            $nonEmpty = false;
            foreach ($row as $v) {
                if ($v !== null && trim((string) $v) !== '') {
                    $nonEmpty = true;
                    break;
                }
            }
            if (!$nonEmpty) {
                continue;
            }

            $droga = $this->cell($row, $map['droga'] ?? null);
            $marca = $this->cell($row, $map['marca'] ?? null);
            $presentacion = $this->cell($row, $map['presentacion'] ?? null);
            $laboratorio = $this->cell($row, $map['laboratorio'] ?? null);

            $coberturaPct = $this->parsePercent($this->cell($row, $map['cobertura'] ?? null));
            $copago = $this->parseMoney($this->cell($row, $map['copago'] ?? null));

            $rowHash = hash('sha256', implode('|', [
                $source,
                $sourceDate,
                $this->normValue($droga),
                $this->normValue($marca),
                $this->normValue($presentacion),
                $this->normValue($laboratorio),
            ]));

            $data = [];
            foreach ($row as $col => $val) {
                $data[$headers[$col] ?: $col] = $val;
            }

            $stagingInsert[] = [
                'batch_id' => $batchId,
                'source' => $source,
                'source_date' => $sourceDate,
                'row_index' => $r,
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'row_hash' => $rowHash,
                'imported_at' => now(),
                'error' => null,
            ];

            $upsertRows[] = [
                'source' => $source,
                'source_date' => $sourceDate,
                'droga' => $droga,
                'marca' => $marca,
                'presentacion' => $presentacion,
                'laboratorio' => $laboratorio,
                'cobertura_pct' => $coberturaPct,
                'copago' => $copago,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $totalRows++;

            if (count($stagingInsert) >= $chunk) {
                $this->flush($db, $stagingInsert, $upsertRows);
                $stagingInsert = [];
                $upsertRows = [];
                $this->info("Processed {$totalRows} rows...");
            }
        }

        $this->flush($db, $stagingInsert, $upsertRows);

        $this->info("Done. Total imported rows: {$totalRows}. Batch: {$batchId}");
        return Command::SUCCESS;
    }

    private function detectHeaderRow($sheet, string $highestCol, int $fromRow, int $toRow): ?int
    {
        $expected = ['DROGA', 'MARCA', 'PRESENTACION', 'LABORATORIO', 'COBERTURA', 'COPAGO'];

        for ($r = $fromRow; $r <= $toRow; $r++) {
            $headers = $this->readHeaders($sheet, $highestCol, $r);
            $hits = 0;
            foreach ($headers as $h) {
                if (in_array($h, $expected, true)) {
                    $hits++;
                }
            }

            // Consider it a header row if at least 2 expected headers are present
            if ($hits >= 2) {
                return $r;
            }
        }

        return null;
    }

    private function readHeaders($sheet, string $highestCol, int $rowIndex): array
    {
        $row = $this->readRowValues($sheet, $highestCol, $rowIndex);
        $headers = [];
        foreach ($row as $col => $val) {
            $headers[$col] = $this->normalizeHeader($this->stringifyCell($val));
        }

        return $headers;
    }

    private function readRowValues($sheet, string $highestCol, int $rowIndex): array
    {
        $maxColIndex = Coordinate::columnIndexFromString($highestCol);
        $row = [];
        for ($c = 1; $c <= $maxColIndex; $c++) {
            $colLetter = Coordinate::stringFromColumnIndex($c);
            $cell = $sheet->getCell($colLetter . $rowIndex);
            $row[$colLetter] = $cell ? $cell->getValue() : null;
        }
        return $row;
    }

    private function stringifyCell(mixed $val): string
    {
        if ($val === null) {
            return '';
        }
        if ($val instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            return $val->getPlainText();
        }
        return (string) $val;
    }

    private function flush($db, array $stagingInsert, array $upsertRows): void
    {
        if ($stagingInsert !== []) {
            $db->table('insurance_vacum_import_rows')->insert($stagingInsert);
        }
        if ($upsertRows !== []) {
            $db->table('insurance_vacum')->upsert(
                $upsertRows,
                ['source', 'source_date', 'droga', 'marca', 'presentacion', 'laboratorio'],
                ['cobertura_pct', 'copago', 'updated_at']
            );
        }
    }

    private function downloadToTemp(string $url): string
    {
        $response = Http::timeout(120)->get($url);
        $response->throw();

        $tmpPath = tempnam(sys_get_temp_dir(), 'vacum_');
        if ($tmpPath === false) {
            throw new \RuntimeException('Failed to create temp file');
        }

        $body = $response->body();
        file_put_contents($tmpPath, $body);

        $size = filesize($tmpPath);
        $fh = fopen($tmpPath, 'rb');
        $sig = $fh ? fread($fh, 2) : false;
        if ($fh) {
            fclose($fh);
        }

        if ($sig !== 'PK') {
            throw new \RuntimeException('Downloaded file does not look like XLSX (zip signature PK missing). Size=' . ($size === false ? 'unknown' : $size));
        }

        $this->info('Downloaded file size: ' . ($size === false ? 'unknown' : $size) . ' bytes');
        return $tmpPath;
    }

    private function resolveSourceDate(string $option, string $url): string
    {
        if ($option !== '') {
            return $option;
        }

        if (preg_match('/(\\d{8})/', $url, $m)) {
            $yyyymmdd = $m[1];
            return substr($yyyymmdd, 0, 4) . '-' . substr($yyyymmdd, 4, 2) . '-' . substr($yyyymmdd, 6, 2);
        }

        return now()->toDateString();
    }

    private function normalizeHeader(string $h): string
    {
        $h = trim($h);
        $h = mb_strtoupper($h);
        $h = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $h) ?: $h;
        $h = preg_replace('/[^A-Z0-9]+/', '_', $h);
        return trim($h ?? '', '_');
    }

    private function buildColumnMap(array $headers): array
    {
        $map = [];
        foreach ($headers as $col => $h) {
            if ($h === 'DROGA') {
                $map['droga'] = $col;
            } elseif ($h === 'MARCA') {
                $map['marca'] = $col;
            } elseif ($h === 'PRESENTACION') {
                $map['presentacion'] = $col;
            } elseif ($h === 'LABORATORIO') {
                $map['laboratorio'] = $col;
            } elseif ($h === 'COBERTURA') {
                $map['cobertura'] = $col;
            } elseif ($h === 'COPAGO') {
                $map['copago'] = $col;
            }
        }
        return $map;
    }

    private function cell(array $row, ?string $col): ?string
    {
        if (!$col) {
            return null;
        }
        $v = $row[$col] ?? null;
        if ($v === null) {
            return null;
        }
        $s = trim((string) $v);
        return $s === '' ? null : $s;
    }

    private function parsePercent(?string $s): ?string
    {
        if ($s === null) {
            return null;
        }

        $s = trim($s);
        if ($s === '') {
            return null;
        }

        $s = str_replace('%', '', $s);
        $s = str_replace(',', '.', $s);
        $s = preg_replace('/[^0-9.\-]/', '', $s);

        if ($s === '' || $s === '-' || $s === '.') {
            return null;
        }

        return $s;
    }

    private function parseMoney(?string $s): ?string
    {
        if ($s === null) {
            return null;
        }

        $s = trim($s);
        if ($s === '') {
            return null;
        }

        // Remove currency symbols and spaces
        $s = str_replace(['$', 'AR$', 'USD', '€', ' '], '', $s);
        // Normalize thousands/decimal separators
        // If contains both '.' and ',', assume '.' are thousands and ',' is decimal
        if (str_contains($s, '.') && str_contains($s, ',')) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } else {
            $s = str_replace(',', '.', $s);
        }

        $s = preg_replace('/[^0-9.\-]/', '', $s);

        if ($s === '' || $s === '-' || $s === '.') {
            return null;
        }

        return $s;
    }

    private function normValue(?string $s): string
    {
        if ($s === null) {
            return '';
        }
        $s = trim(mb_strtolower($s));
        $s = preg_replace('/\s+/', ' ', $s);
        return $s;
    }
}
