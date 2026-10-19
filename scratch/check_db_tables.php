<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select("SHOW TABLES");
$dbName = \Illuminate\Support\Facades\DB::getDatabaseName();
echo "Database: " . $dbName . "\n";
foreach ($tables as $t) {
    $prop = "Tables_in_" . $dbName;
    echo "- " . ($t->$prop ?? json_encode($t)) . "\n";
}
