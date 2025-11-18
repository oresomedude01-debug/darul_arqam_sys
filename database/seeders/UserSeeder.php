<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@darularqam.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Principal',
            'email' => 'principal@darularqam.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Registrar',
            'email' => 'registrar@darularqam.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Admissions Officer',
            'email' => 'admissions@darularqam.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Teacher - Ahmad',
            'email' => 'ahmad@darularqam.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Teacher - Fatima',
            'email' => 'fatima@darularqam.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Created 6 users with email/password authentication');
        $this->command->info('Default password for all users: password');
    }
}
