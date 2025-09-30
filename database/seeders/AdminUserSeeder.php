<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder; // Import the User model
use Illuminate\Support\Facades\Hash;

// No need to import DB facade if using the model

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminEmail = env('ADMIN_EMAIL', 'default-admin@app.com');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        if (User::where('email', $adminEmail)->exists()) {
            return;
        }

        User::create([
            'name' => 'Presida',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully.');
    }
}
