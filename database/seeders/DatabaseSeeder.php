<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Run all seeders in order
        $this->call([
            UserSeeder::class,
            RegistrationTokenSeeder::class,
            StudentSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📝 Login Credentials:');
        $this->command->info('   Email: admin@darularqam.edu');
        $this->command->info('   Password: password');
        $this->command->newLine();
    }
}
