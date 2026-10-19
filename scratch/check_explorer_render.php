<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Livewire\Livewire;

$user = User::first();

// 1. Initial load (All)
$comp = Livewire::actingAs($user)->test('cobit-explorer');
$htmlInitial = $comp->html();

echo "=== EXPLORER RENDER VERIFICATION ===\n";
echo "Initial selectedDomain: " . ($comp->get('selectedDomain') ?: 'All (empty)') . "\n";
echo "Initial selectedObjectiveId: " . ($comp->get('selectedObjectiveId') ?: 'NULL (hidden)') . "\n";
echo "Right layer visible on All: " . (strpos($htmlInitial, 'Tujuan (Purpose Statement):') !== false ? 'YES (ERROR)' : 'NO (CORRECT)') . "\n";
echo "5 Domain Showcase visible: " . (strpos($htmlInitial, '5 Domain Standar COBIT 2019') !== false ? 'YES (CORRECT)' : 'NO (ERROR)') . "\n";

// 2. Select an objective (e.g. EDM01)
$comp->call('selectObjective', 'EDM01');
$htmlSelected = $comp->html();

echo "\nAfter selectObjective('EDM01'):\n";
echo "selectedObjectiveId: " . $comp->get('selectedObjectiveId') . "\n";
echo "Right layer visible: " . (strpos($htmlSelected, 'Tujuan (Purpose Statement):') !== false ? 'YES (CORRECT)' : 'NO (ERROR)') . "\n";

// 3. Close detail or click All
$comp->call('closeDetail');
$htmlClosed = $comp->html();

echo "\nAfter closeDetail():\n";
echo "selectedObjectiveId: " . ($comp->get('selectedObjectiveId') ?: 'NULL (hidden)') . "\n";
echo "Right layer visible: " . (strpos($htmlClosed, 'Tujuan (Purpose Statement):') !== false ? 'YES (ERROR)' : 'NO (CORRECT)') . "\n";
echo "=== VERIFICATION COMPLETED ===\n";
