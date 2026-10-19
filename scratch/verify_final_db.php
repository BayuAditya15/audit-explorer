<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CobitObjective;
use App\Models\CobitPractice;
use App\Models\CobitActivity;
use App\Models\CobitRole;
use App\Models\CobitRaciAssignment;
use App\Models\User;

echo "=== COBIT EXPLORER DATABASE VERIFICATION ===\n";
echo "Objectives: " . CobitObjective::count() . " (Expected: 40)\n";
echo "Practices: " . CobitPractice::count() . " (Expected: 231)\n";
echo "Activities: " . CobitActivity::count() . " (Expected: 1202)\n";
echo "Roles: " . CobitRole::count() . " (Expected: 33)\n";
echo "RACI Assignments: " . CobitRaciAssignment::count() . " (Expected: 1614)\n";
echo "Users: " . User::count() . "\n";
foreach (User::all() as $u) {
    echo "  - {$u->name} ({$u->email})\n";
}
echo "=== END VERIFICATION ===\n";
