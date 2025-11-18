<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistrationToken;
use Carbon\Carbon;

class RegistrationTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create active tokens for different sessions and classes
        $sessions = ['2024/2025', '2025/2026'];
        $classes = ['Nursery 1', 'Nursery 2', 'Primary 1', 'Primary 2', 'Primary 3', 'JSS 1', 'JSS 2', 'SSS 1'];

        foreach ($sessions as $session) {
            foreach ($classes as $class) {
                // Create 5 active tokens per class per session
                for ($i = 1; $i <= 5; $i++) {
                    RegistrationToken::create([
                        'token_code' => RegistrationToken::generateTokenCode(),
                        'status' => 'active',
                        'session_year' => $session,
                        'class_level' => $class,
                        'expires_at' => Carbon::now()->addMonths(6),
                        'created_by' => 1, // Admin user
                    ]);
                }
            }
        }

        // Create some expired tokens
        for ($i = 1; $i <= 10; $i++) {
            RegistrationToken::create([
                'token_code' => RegistrationToken::generateTokenCode(),
                'status' => 'expired',
                'session_year' => '2023/2024',
                'class_level' => $classes[array_rand($classes)],
                'expires_at' => Carbon::now()->subMonths(2),
                'created_by' => 1,
            ]);
        }

        // Create some disabled tokens
        for ($i = 1; $i <= 5; $i++) {
            RegistrationToken::create([
                'token_code' => RegistrationToken::generateTokenCode(),
                'status' => 'disabled',
                'session_year' => '2024/2025',
                'class_level' => $classes[array_rand($classes)],
                'expires_at' => Carbon::now()->addMonths(3),
                'created_by' => 1,
            ]);
        }

        $totalActive = count($sessions) * count($classes) * 5;
        $this->command->info("Created $totalActive active tokens");
        $this->command->info('Created 10 expired tokens');
        $this->command->info('Created 5 disabled tokens');
        $this->command->info('Total: ' . ($totalActive + 15) . ' tokens');
    }
}
