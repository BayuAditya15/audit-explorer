<?php

namespace App\Console\Commands;

use App\Models\CobitPractice;
use App\Models\CobitRole;
use App\Models\CobitRaciAssignment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class ImportCobitRaci extends Command
{
    protected $signature = 'cobit:import-raci
                            {path? : Path to the COBIT 2019 RACI workbook}';

    protected $description = 'Import COBIT 2019 RACI matrix (roles & assignments) from official ISACA Excel workbook';

    public function handle(): int
    {
        $path = $this->argument('path')
            ?? 'D:\\punya bayu\\tools audit\\Cobit-2019-toolkit\\COBIT-2019_RACI-by-role_April 2020_v2.xlsx';

        if (! is_file($path)) {
            $this->error("File Excel tidak ditemukan: {$path}");
            return self::FAILURE;
        }

        // 1. Verifikasi tabel database ada
        if (! DB::getSchemaBuilder()->hasTable('cobit_roles') || ! DB::getSchemaBuilder()->hasTable('cobit_raci_assignments')) {
            $this->error('Tabel cobit_roles atau cobit_raci_assignments belum ada di database. Jalankan migrasi terlebih dahulu.');
            return self::FAILURE;
        }

        // 2. Proteksi Idempotensi: Jangan menimpa atau mencampur data yang sudah ada
        $existingRoles = CobitRole::count();
        $existingAssignments = CobitRaciAssignment::count();

        if ($existingRoles > 0 || $existingAssignments > 0) {
            $this->error('Import dibatalkan karena tabel RACI sudah berisi data.');
            $this->table(
                ['Tabel', 'Jumlah data saat ini'],
                [
                    ['cobit_roles', $existingRoles],
                    ['cobit_raci_assignments', $existingAssignments],
                ]
            );
            $this->info('Data yang ada dipertahankan tanpa perubahan (tidak di-truncate).');
            return self::FAILURE;
        }

        $sheetName = 'COBIT 2019 RACI';
        $this->info("Membaca workbook: {$path}");
        $this->info("Sheet sumber: {$sheetName}");

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName($sheetName);

            if ($sheet === null) {
                $this->error("Sheet '{$sheetName}' tidak ditemukan di workbook.");
                return self::FAILURE;
            }

            // 3. Baca Header & Ekstraksi 33 Peran Organisasi
            $header = $sheet->rangeToArray('A1:AM1', null, true, true, false)[0];
            $roleHeaders = array_slice($header, 6); // Kolom G (indeks 6) hingga AM (indeks 38)
            $totalRoles = count($roleHeaders);

            if ($totalRoles !== 33) {
                $this->error("Jumlah kolom role tidak sesuai: ditemukan {$totalRoles}, diharapkan 33 peran.");
                return self::FAILURE;
            }

            // 4. Validasi 231 Practices terhadap Database
            $dbPractices = CobitPractice::pluck('id', 'practice_id')->all();
            $dbCount = count($dbPractices);

            if ($dbCount !== 231) {
                $this->error("Jumlah practices di database ({$dbCount}) tidak sesuai standar COBIT 2019 (231 practices).");
                return self::FAILURE;
            }

            $highestRow = $sheet->getHighestRow();
            $rowsData = [];
            $practiceIdsInFile = [];
            $totalACount = 0;
            $totalRCount = 0;
            $validationErrors = [];

            for ($r = 2; $r <= $highestRow; $r++) {
                $practiceId = trim((string) ($sheet->getCell([5, $r])->getValue() ?? ''));
                if ($practiceId === '') {
                    continue;
                }

                if (in_array($practiceId, $practiceIdsInFile, true)) {
                    $validationErrors[] = "Baris {$r}: Duplikasi Practice ID '{$practiceId}'.";
                }
                $practiceIdsInFile[] = $practiceId;

                if (! isset($dbPractices[$practiceId])) {
                    $validationErrors[] = "Baris {$r}: Practice ID '{$practiceId}' tidak ditemukan di tabel cobit_practices.";
                }

                // Cek penugasan per kolom role
                $aInRow = 0;
                $rowAssignments = [];

                foreach ($roleHeaders as $colIdx => $roleName) {
                    $colNum = 7 + $colIdx; // Kolom G = 7
                    $cellVal = trim((string) ($sheet->getCell([$colNum, $r])->getValue() ?? ''));

                    if ($cellVal === 'Accountable') {
                        $aInRow++;
                        $totalACount++;
                        $rowAssignments[$roleName] = 'A';
                    } elseif ($cellVal === 'Responsible') {
                        $totalRCount++;
                        $rowAssignments[$roleName] = 'R';
                    } elseif ($cellVal !== '') {
                        $validationErrors[] = "Baris {$r}, Role '{$roleName}': Nilai RACI tidak valid '{$cellVal}'. Hanya 'Accountable' atau 'Responsible' yang diizinkan.";
                    }
                }

                if ($aInRow !== 1) {
                    $validationErrors[] = "Baris {$r}, Practice '{$practiceId}': Memiliki {$aInRow} penugasan 'Accountable'. Setiap praktik wajib memiliki tepat 1 penanggung jawab akhir.";
                }

                $rowsData[] = [
                    'practice_id' => $practiceId,
                    'assignments' => $rowAssignments,
                ];
            }

            // Validasi kelengkapan 231 practices
            $totalFilePractices = count($practiceIdsInFile);
            if ($totalFilePractices !== 231) {
                $validationErrors[] = "Jumlah praktik dalam sheet ({$totalFilePractices}) tidak sesuai standar (diharapkan 231).";
            }

            // Jika ada kesalahan validasi, hentikan dan laporkan
            if (! empty($validationErrors)) {
                $this->error('Validasi data RACI gagal:');
                foreach (array_slice($validationErrors, 0, 10) as $err) {
                    $this->line(" - <fg=red>{$err}</>");
                }
                if (count($validationErrors) > 10) {
                    $this->line(" ... dan " . (count($validationErrors) - 10) . " kesalahan lainnya.");
                }
                return self::FAILURE;
            }

            $this->info("Validasi integritas berhasil:");
            $this->line(" - Total Roles: {$totalRoles}");
            $this->line(" - Total Practices diverifikasi: {$totalFilePractices} (100% cocok dengan DB)");
            $this->line(" - Total Accountable (A) aktual: {$totalACount}");
            $this->line(" - Total Responsible (R) aktual: {$totalRCount}");
            $this->line(" - Total Penugasan (A + R): " . ($totalACount + $totalRCount));

            // 5. Eksekusi Transaksional
            DB::transaction(function () use ($roleHeaders, $rowsData, $dbPractices) {
                $roleIdMap = [];
                $sequence = 1;

                foreach ($roleHeaders as $roleName) {
                    $shortName = $this->getRoleShortName($roleName);
                    $code = $this->getRoleCode($roleName);

                    $role = CobitRole::create([
                        'code' => $code,
                        'name' => $roleName,
                        'short_name' => $shortName,
                        'sequence' => $sequence++,
                    ]);

                    $roleIdMap[$roleName] = $role->id;
                }

                $assignmentsBatch = [];
                $now = now();

                foreach ($rowsData as $row) {
                    $practiceDbId = $dbPractices[$row['practice_id']];

                    foreach ($row['assignments'] as $roleName => $level) {
                        $assignmentsBatch[] = [
                            'cobit_practice_id' => $practiceDbId,
                            'cobit_role_id' => $roleIdMap[$roleName],
                            'raci_level' => $level,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                // Batch insert per 500 rows untuk efisiensi
                foreach (array_chunk($assignmentsBatch, 500) as $chunk) {
                    CobitRaciAssignment::insert($chunk);
                }
            });

            $this->info('Import data RACI berhasil diselesaikan.');
            $this->table(
                ['Kategori', 'Jumlah Tersimpan'],
                [
                    ['Peran Organisasi (cobit_roles)', CobitRole::count()],
                    ['Penugasan Accountable (A)', CobitRaciAssignment::where('raci_level', 'A')->count()],
                    ['Penugasan Responsible (R)', CobitRaciAssignment::where('raci_level', 'R')->count()],
                    ['Total Penugasan RACI (cobit_raci_assignments)', CobitRaciAssignment::count()],
                ]
            );

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Terjadi kesalahan saat memproses berkas Excel: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function getRoleShortName(string $name): string
    {
        $map = [
            'Chief Executive Officer' => 'CEO',
            'Chief Financial Officer' => 'CFO',
            'Chief Operating Officer' => 'COO',
            'Chief Risk Officer' => 'CRO',
            'Chief Information Officer' => 'CIO',
            'Chief Technology Officer' => 'CTO',
            'Chief Digital Officer' => 'CDO',
            'Chief Information Security Officer' => 'CISO',
            'Business Process Owners' => 'Biz Owners',
            'I&T Governance Board' => 'IT Gov Board',
            'Architecture Board' => 'Arch Board',
            'Enterprise Risk Committee' => 'Risk Comm',
            'Steering Programs/Projects Committee' => 'Steering Comm',
            'Project Management Office' => 'PMO',
            'Data Mgmt Function' => 'Data Mgmt',
            'Executive Committee' => 'Exec Comm',
            'Head Human Resources' => 'Head HR',
            'Head Architect' => 'Head Arch',
            'Head Development' => 'Head Dev',
            'Head IT operations' => 'Head Ops',
            'Head IT administration' => 'Head Admin',
            'Service Manager' => 'Service Mgr',
            'Information Security Manager' => 'Infosec Mgr',
            'Business Continuity Manager' => 'BCM',
            'Privacy Officer' => 'Privacy',
            'Legal Counsel' => 'Legal',
            'Portfolio Manager' => 'Portfolio Mgr',
            'Program Manager' => 'Program Mgr',
            'Project Manager' => 'Project Mgr',
            'Relationship Manager' => 'Rel Mgr',
        ];

        return $map[$name] ?? $name;
    }

    private function getRoleCode(string $name): string
    {
        $code = strtoupper(preg_replace('/[^a-zA-Z0-9]+/', '_', $name));
        return trim($code, '_');
    }
}
