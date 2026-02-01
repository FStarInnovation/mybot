<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Options;
use OpenSpout\Reader\XLSX\Reader;

class ImportFarmaciasFromExcel extends Command
{
    protected $signature = 'farmacias:import 
        {path? : Ruta al archivo XLSX (por defecto farmacias_2024.xlsx en la raíz del proyecto)}
        {--chunk=1000 : Cantidad de filas por inserción}';

    protected $description = 'Importa farmacias desde un XLSX al esquema Neon';

    public function handle(): int
    {
        ini_set('memory_limit', config('farmacias.import_memory_limit', '512M'));

        $path = $this->argument('path') ?? base_path('farmacias_2024.xlsx');
        $chunkSize = (int) $this->option('chunk');

        if (!file_exists($path)) {
            $this->error("Archivo no encontrado: {$path}");
            return self::FAILURE;
        }

        $this->info("Leyendo {$path}");

        $tempFolder = storage_path('app/openspout-temp');
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0o775, true);
        }

        $options = new Options(
            SHOULD_FORMAT_DATES: false,
            SHOULD_PRESERVE_EMPTY_ROWS: false,
            SHOULD_USE_1904_DATES: false,
            SHOULD_LOAD_MERGE_CELLS: false,
            tempFolder: $tempFolder,
        );

        $reader = new Reader($options);
        $reader->open($path);

        $headerMap = [];
        $total = 0;
        $buffer = [];
        $now = Carbon::now();

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $cells = $row->toArray();

                if (empty($headerMap)) {
                    $headerMap = $this->buildHeaderMap($cells);
                    if (empty($headerMap)) {
                        $this->warn('No se detectaron encabezados en la primera fila.');
                        $reader->close();
                        return self::FAILURE;
                    }
                    $this->info('Columnas detectadas: ' . implode(', ', array_values($headerMap)));
                    continue;
                }

                $record = $this->mapRow($cells, $headerMap, $now);
                if (!$record) {
                    continue;
                }

                $buffer[] = $record;
                $total++;

                if (count($buffer) >= $chunkSize) {
                    $this->flushBuffer($buffer);
                    $buffer = [];
                    $this->info("Importadas {$total} filas...");
                }
            }

            // Solo procesamos la primera hoja
            break;
        }

        $reader->close();

        if ($buffer) {
            $this->flushBuffer($buffer);
        }

        $this->info("Importación completada. Total filas procesadas: {$total}");
        return $total > 0 ? self::SUCCESS : self::SUCCESS;
    }

    /**
     * @param array<int,mixed> $row
     * @param array<int,string> $headers
     */
    protected function mapRow(array $row, array $headers, Carbon $timestamp): ?array
    {
        $values = [];
        foreach ($headers as $index => $label) {
            $values[$label] = $row[$index] ?? null;
        }

        if (empty($values['establecimiento_id'])) {
            return null;
        }

        return [
            'establecimiento_id' => (string) $values['establecimiento_id'],
            'establecimiento_nombre' => $values['establecimiento_nombre'] ?? null,
            'localidad_id' => $values['localidad_id'] ?? null,
            'localidad_nombre' => $values['localidad_nombre'] ?? null,
            'provincia_id' => $values['provincia_id'] ?? null,
            'provincia_nombre' => $values['provincia_nombre'] ?? null,
            'departamento_id' => $values['departamento_id'] ?? null,
            'departamento_nombre' => $values['departamento_nombre'] ?? null,
            'codloc' => $values['codloc'] ?? null,
            'codent' => $values['codent'] ?? null,
            'origen_financiamiento' => $values['origen_financiamiento'] ?? null,
            'tipologia_id' => $values['tipologia_id'] ?? null,
            'tipologia_sigla' => $values['tipologia_sigla'] ?? null,
            'tipologia_nombre' => $values['tipologia_nombre'] ?? null,
            'cp' => $values['cp'] ?? null,
            'domicilio' => $values['domicilio'] ?? null,
            'sitio_web' => $values['sitio_web'] ?? null,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
    }

    /**
     * @param array<int,array<string,mixed>> $buffer
     */
    protected function flushBuffer(array $buffer): void
    {
        DB::table('farmacias')->upsert(
            $buffer,
            ['establecimiento_id'],
            [
                'establecimiento_nombre',
                'localidad_id',
                'localidad_nombre',
                'provincia_id',
                'provincia_nombre',
                'departamento_id',
                'departamento_nombre',
                'codloc',
                'codent',
                'origen_financiamiento',
                'tipologia_id',
                'tipologia_sigla',
                'tipologia_nombre',
                'cp',
                'domicilio',
                'sitio_web',
                'updated_at',
            ]
        );
    }

    protected function buildHeaderMap(array $cells): array
    {
        $map = [];
        foreach (array_values($cells) as $index => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $map[$index] = strtolower(trim((string) $value));
        }

        return $map;
    }

}
