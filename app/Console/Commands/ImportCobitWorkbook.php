<?php

namespace App\Console\Commands;
use App\Models\CobitActivity;
use App\Models\CobitObjective;
use App\Models\CobitPractice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class ImportCobitWorkbook extends Command
{
    protected $signature = 'cobit:import-workbook
                            {path? : Path to the COBIT 2019 objectives workbook}';

    protected $description = 'Import COBIT 2019 objectives, practices, and activities from Excel';

    public function handle(): int
    {
        $path = $this->argument('path')
            ?? 'D:\\punya bayu\\tools audit\\Cobit-2019-toolkit\\COBIT 2019_Governance-Management-Objectives-Practices-Activities_Nov2018.xlsx';

        if (! is_file($path)) {
            $this->error("File Excel tidak ditemukan: {$path}");

            return self::FAILURE;
        }

        // Jangan menimpa atau mencampur data yang sudah ada.
        $existing = [
            'objectives' => CobitObjective::count(),
            'practices' => CobitPractice::count(),
            'activities' => CobitActivity::count(),
        ];

        if (array_sum($existing) > 0) {
            $this->error('Import dibatalkan karena tabel COBIT sudah berisi data.');
            $this->table(
                ['Tabel', 'Jumlah data saat ini'],
                [
                    ['Objectives', $existing['objectives']],
                    ['Practices', $existing['practices']],
                    ['Activities', $existing['activities']],
                ]
            );

            return self::FAILURE;
        }

        try {
            $spreadsheet = IOFactory::load($path);

            foreach (['Objectives', 'Objectives-Practices', 'Activities'] as $sheetName) {
                if ($spreadsheet->getSheetByName($sheetName) === null) {
                    $this->error("Sheet '{$sheetName}' tidak ditemukan.");

                    return self::FAILURE;
                }
            }

            $objectiveRows = $spreadsheet->getSheetByName('Objectives')
                ->toArray(null, true, true, true);

            $practiceRows = $spreadsheet->getSheetByName('Objectives-Practices')
                ->toArray(null, true, true, true);

            $activityRows = $spreadsheet->getSheetByName('Activities')
                ->toArray(null, true, true, true);

            $counts = DB::transaction(function () use (
                $objectiveRows,
                $practiceRows,
                $activityRows
            ): array {
                $objectives = $this->importObjectives($objectiveRows);
                $practices = $this->importPractices($practiceRows);
                $activities = $this->importActivities($activityRows);

                return compact('objectives', 'practices', 'activities');
            });

            $this->info('Import COBIT berhasil.');
            $this->table(
                ['Jenis data', 'Jumlah berhasil diimpor'],
                [
                    ['Objectives', $counts['objectives']],
                    ['Practices', $counts['practices']],
                    ['Activities', $counts['activities']],
                ]
            );

            return self::SUCCESS;
        } catch (Throwable $e) {
            report($e);
            $this->error('Import gagal. Transaksi database dibatalkan jika sudah dimulai.');
            $this->line($e->getMessage());

            return self::FAILURE;
        }
    }

    private function cell(array $row, string $column): string
    {
        return trim((string) ($row[$column] ?? ''));
    }

    private function truthy(string $value): bool
    {
        return in_array(strtolower(trim($value)), ['yes', 'true', '1', 'x'], true);
    }

    private function importObjectives(array $rows): int
    {
        $count = 0;

        // Sheet Objectives: header baris 7, data mulai baris 8.
        for ($i = 8, $max = count($rows); $i <= $max; $i++) {
            $row = $rows[$i] ?? [];
            $objectiveId = $this->cell($row, 'D');

            if ($objectiveId === '') {
                continue;
            }

            CobitObjective::create([
                'area' => $this->cell($row, 'B'),
                'domain' => $this->cell($row, 'C'),
                'objective_id' => $objectiveId,
                'name' => $this->cell($row, 'E'),
                'description' => $this->cell($row, 'F') ?: null,
                'purpose_statement' => $this->cell($row, 'G') ?: null,
                'new_in_cobit_2019' => $this->truthy($this->cell($row, 'H')),
                'new_in_cobit_5' => $this->truthy($this->cell($row, 'I')),
            ]);

            $count++;
        }

        if ($count === 0) {
            throw new \RuntimeException('Tidak ada objective yang ditemukan pada sheet Objectives.');
        }

        return $count;
    }

    
private function importPractices(array $rows): int
{
    $count = 0;
    $currentObjectiveId = '';

    // Sheet Objectives-Practices: header baris 8, data mulai baris 9.
    for ($i = 9, $max = count($rows); $i <= $max; $i++) {
        $row = $rows[$i] ?? [];

        // Excel mengosongkan Objective ID pada baris practice berikutnya
        // selama masih berada di bawah objective yang sama.
        $objectiveId = $this->cell($row, 'D');

        if ($objectiveId !== '') {
            $currentObjectiveId = $objectiveId;
        }

        $practiceId = $this->cell($row, 'H');

        if ($practiceId === '') {
            continue;
        }

        if ($currentObjectiveId === '') {
            throw new \RuntimeException(
                "Objective ID kosong untuk practice '{$practiceId}' pada baris Excel {$i}."
            );
        }

        $objective = CobitObjective::where(
            'objective_id',
            $currentObjectiveId
        )->first();

        if (! $objective) {
            throw new \RuntimeException(
                "Objective '{$currentObjectiveId}' tidak ditemukan untuk practice '{$practiceId}'."
            );
        }

        CobitPractice::create([
            'cobit_objective_id' => $objective->id,
            'practice_id' => $practiceId,
            'name' => $this->cell($row, 'I'),
            'description' => null,
        ]);

        $count++;
    }

    if ($count === 0) {
        throw new \RuntimeException('Tidak ada practice yang ditemukan.');
    }

    return $count;
}


    private function importActivities(array $rows): int
    {
        $count = 0;

        // Sheet Activities: header baris 7, data mulai baris 8.
        for ($i = 8, $max = count($rows); $i <= $max; $i++) {
            $row = $rows[$i] ?? [];
            $practiceId = $this->cell($row, 'F');
            $activityText = $this->cell($row, 'H');

            if ($practiceId === '' || $activityText === '') {
                continue;
            }

            $practice = CobitPractice::where('practice_id', $practiceId)->first();

            if (! $practice) {
                throw new \RuntimeException(
                    "Practice '{$practiceId}' tidak ditemukan untuk aktivitas pada baris Excel {$i}."
                );
            }

            $sequence = null;

            if (preg_match('/^\s*(\d+)\s*[.)]\s*(.*)$/u', $activityText, $matches)) {
                $sequence = (int) $matches[1];
                $activityText = trim($matches[2]);
            }

            CobitActivity::create([
                'cobit_practice_id' => $practice->id,
                'sequence' => $sequence,
                'description' => $activityText,
            ]);

            $count++;
        }

        if ($count === 0) {
            throw new \RuntimeException('Tidak ada activity yang ditemukan.');
        }

        return $count;
    }
}
