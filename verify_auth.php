<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a test user
$name = 'TestAdmin';
$password = 'secret';
$email = 'testadmin@example.com';

$user = User::where('name', $name)->first();
if (!$user) {
    echo "Creating user...\n";
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
    ]);
} else {
    echo "User exists, updating password...\n";
    $user->password = Hash::make($password);
    $user->save();
}

// Attempt login
echo "Attempting login with name: $name and password: $password\n";
if (Auth::attempt(['name' => $name, 'password' => $password])) {
    echo "LOGIN SUCCESS!\n";
} else {
    echo "LOGIN FAILED!\n";
}
