<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = \App\Models\User::all();
echo "User count: " . $users->count() . "\n";
foreach ($users as $u) {
    echo "- ID: {$u->id}, Name: {$u->name}, Email: {$u->email}\n";
}
