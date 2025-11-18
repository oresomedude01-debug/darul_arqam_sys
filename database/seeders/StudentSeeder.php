<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\RegistrationToken;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstNames = [
            'male' => ['Ibrahim', 'Ahmad', 'Muhammad', 'Abdullah', 'Yusuf', 'Hassan', 'Usman', 'Ali', 'Bilal', 'Omar'],
            'female' => ['Fatima', 'Aisha', 'Maryam', 'Zainab', 'Khadija', 'Hafsa', 'Amina', 'Safiya', 'Halima', 'Rabia']
        ];

        $lastNames = ['Abdullah', 'Hassan', 'Musa', 'Ibrahim', 'Ahmad', 'Usman', 'Ali', 'Bello', 'Suleiman', 'Ismail'];

        $classes = [
            'Nursery 1', 'Nursery 2',
            'Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6',
            'JSS 1', 'JSS 2', 'JSS 3',
            'SSS 1', 'SSS 2', 'SSS 3'
        ];

        $sessions = ['2024/2025', '2023/2024'];
        $statuses = ['active' => 80, 'pending' => 15, 'inactive' => 5]; // Percentage distribution

        $allergies = [
            ['Peanuts'], ['Dust', 'Pollen'], ['Eggs'], ['Milk'], ['Shellfish'],
            ['Penicillin'], [], []  // Some students with no allergies
        ];

        $religions = ['Islam', 'Christianity'];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $sections = ['A', 'B', 'C', null];

        // Get some consumed tokens to link students to
        $consumedTokens = RegistrationToken::where('status', 'consumed')->pluck('id')->toArray();

        // Create 100 students
        for ($i = 1; $i <= 100; $i++) {
            $gender = $i % 2 === 0 ? 'male' : 'female';
            $firstName = $firstNames[$gender][array_rand($firstNames[$gender])];
            $lastName = $lastNames[array_rand($lastNames)];
            $middleName = $i % 3 === 0 ? $firstNames[$gender][array_rand($firstNames[$gender])] : null;

            // Random date of birth (age 3-18)
            $age = rand(3, 18);
            $dob = Carbon::now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 30));

            // Determine class level based on age
            if ($age <= 4) {
                $classLevel = 'Nursery 1';
            } elseif ($age === 5) {
                $classLevel = 'Nursery 2';
            } elseif ($age === 6) {
                $classLevel = 'Primary 1';
            } elseif ($age === 7) {
                $classLevel = 'Primary 2';
            } elseif ($age === 8) {
                $classLevel = 'Primary 3';
            } elseif ($age === 9) {
                $classLevel = 'Primary 4';
            } elseif ($age === 10) {
                $classLevel = 'Primary 5';
            } elseif ($age === 11) {
                $classLevel = 'Primary 6';
            } elseif ($age === 12) {
                $classLevel = 'JSS 1';
            } elseif ($age === 13) {
                $classLevel = 'JSS 2';
            } elseif ($age === 14) {
                $classLevel = 'JSS 3';
            } elseif ($age === 15) {
                $classLevel = 'SSS 1';
            } elseif ($age === 16) {
                $classLevel = 'SSS 2';
            } else {
                $classLevel = 'SSS 3';
            }

            // Determine status based on distribution
            $rand = rand(1, 100);
            if ($rand <= 80) {
                $status = 'active';
            } elseif ($rand <= 95) {
                $status = 'pending';
            } else {
                $status = 'inactive';
            }

            $selectedAllergies = $allergies[array_rand($allergies)];

            Student::create([
                'admission_number' => Student::generateAdmissionNumber(),
                'admission_date' => Carbon::now()->subMonths(rand(1, 12)),
                'status' => $status,
                'registration_token_id' => $i % 4 === 0 && !empty($consumedTokens) ? $consumedTokens[array_rand($consumedTokens)] : null,

                // Personal Information
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'date_of_birth' => $dob,
                'gender' => $gender,
                'nationality' => 'Nigerian',
                'religion' => $religions[array_rand($religions)],
                'place_of_birth' => ['Lagos', 'Abuja', 'Kano', 'Ibadan', 'Port Harcourt'][array_rand(['Lagos', 'Abuja', 'Kano', 'Ibadan', 'Port Harcourt'])],
                'address' => rand(1, 999) . ' ' . ['Ahmadu Bello Way', 'Independence Avenue', 'Victoria Island', 'Ikoyi Road', 'Surulere Street'][array_rand(['Ahmadu Bello Way', 'Independence Avenue', 'Victoria Island', 'Ikoyi Road', 'Surulere Street'])],
                'blood_group' => $bloodGroups[array_rand($bloodGroups)],

                // Academic Information
                'class_level' => $classLevel,
                'section' => $sections[array_rand($sections)],
                'session_year' => $sessions[array_rand($sessions)],
                'roll_number' => str_pad($i, 3, '0', STR_PAD_LEFT),

                // Contact Information
                'email' => $i % 5 === 0 ? strtolower($firstName . $lastName . '@example.com') : null,
                'phone' => $i % 3 === 0 ? '+234' . rand(7000000000, 9099999999) : null,

                // Parent/Guardian Information - Primary
                'parent1_name' => $lastNames[array_rand($lastNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'parent1_relationship' => ['Father', 'Mother'][array_rand(['Father', 'Mother'])],
                'parent1_phone' => '+234' . rand(7000000000, 9099999999),
                'parent1_email' => strtolower('parent' . $i . '@example.com'),
                'parent1_occupation' => ['Engineer', 'Doctor', 'Teacher', 'Businessman', 'Civil Servant', 'Accountant'][array_rand(['Engineer', 'Doctor', 'Teacher', 'Businessman', 'Civil Servant', 'Accountant'])],

                // Parent/Guardian Information - Secondary (optional)
                'parent2_name' => $i % 2 === 0 ? $lastNames[array_rand($lastNames)] . ' ' . $lastNames[array_rand($lastNames)] : null,
                'parent2_relationship' => $i % 2 === 0 ? ['Father', 'Mother'][array_rand(['Father', 'Mother'])] : null,
                'parent2_phone' => $i % 2 === 0 ? '+234' . rand(7000000000, 9099999999) : null,
                'parent2_email' => $i % 2 === 0 ? strtolower('parent2_' . $i . '@example.com') : null,
                'parent2_occupation' => $i % 2 === 0 ? ['Nurse', 'Lawyer', 'Pharmacist', 'Trader'][array_rand(['Nurse', 'Lawyer', 'Pharmacist', 'Trader'])] : null,

                // Previous School (for some students)
                'previous_school_name' => $i % 3 === 0 ? ['Green Valley School', 'Bright Future Academy', 'Al-Iman School', 'Royal Kids School'][array_rand(['Green Valley School', 'Bright Future Academy', 'Al-Iman School', 'Royal Kids School'])] : null,
                'previous_school_address' => $i % 3 === 0 ? 'Lagos, Nigeria' : null,
                'previous_school_grade' => $i % 3 === 0 ? 'Grade ' . rand(1, 6) : null,
                'previous_school_year' => $i % 3 === 0 ? rand(2020, 2024) : null,
                'previous_school_reason' => $i % 3 === 0 ? ['Relocation', 'Better opportunities', 'Distance'][array_rand(['Relocation', 'Better opportunities', 'Distance'])] : null,

                // Health & Medical Information
                'allergies' => !empty($selectedAllergies) ? json_encode($selectedAllergies) : null,
                'medical_conditions' => $i % 10 === 0 ? ['Asthma', 'None', 'Mild allergy'][array_rand(['Asthma', 'None', 'Mild allergy'])] : null,
                'medications' => $i % 15 === 0 ? 'Inhaler (as needed)' : null,
                'emergency_medical_consent' => $i % 4 !== 0, // 75% have consent
                'special_needs' => $i % 20 === 0 ? 'Requires extra time for exams' : null,

                // Additional Information
                'notes' => $i % 10 === 0 ? 'Excellent student with strong academic performance.' : null,

                // System Fields
                'created_by' => 1,
            ]);
        }

        $activeCount = Student::where('status', 'active')->count();
        $pendingCount = Student::where('status', 'pending')->count();
        $inactiveCount = Student::where('status', 'inactive')->count();

        $this->command->info('Created 100 students');
        $this->command->info("- Active: $activeCount");
        $this->command->info("- Pending: $pendingCount");
        $this->command->info("- Inactive: $inactiveCount");
        $this->command->info('- Distributed across all class levels');
        $this->command->info('- Realistic data with parent/guardian information');
    }
}
