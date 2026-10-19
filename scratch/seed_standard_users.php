<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Update or create standard user
$dev = User::updateOrCreate(
    ['email' => 'developer@cobit.local'],
    [
        'name' => 'Lead Developer',
        'password' => Hash::make('devpassword123'),
    ]
);

$admin = User::updateOrCreate(
    ['email' => 'admin@cobit.local'],
    [
        'name' => 'Administrator',
        'password' => Hash::make('password'),
    ]
);

echo "Standard users seeded:\n";
echo "- {$dev->email} (password: devpassword123)\n";
echo "- {$admin->email} (password: password)\n";
