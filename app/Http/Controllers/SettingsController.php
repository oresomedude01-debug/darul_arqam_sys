<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        // Demo settings data
        $schoolSettings = [
            'school_name' => 'Darul Arqam Islamic School',
            'school_code' => 'DAIS-2025',
            'phone' => '+1 (555) 123-4567',
            'email' => 'info@darularqam.edu',
            'address' => '123 Education Street, Knowledge City, KC 12345',
            'website' => 'https://darularqam.edu',
            'academic_year' => '2024-2025',
            'timezone' => 'America/New_York',
            'language' => 'en',
            'currency' => 'USD'
        ];

        $systemSettings = [
            'maintenance_mode' => false,
            'allow_registration' => true,
            'require_email_verification' => true,
            'session_timeout' => 120,
            'max_login_attempts' => 5,
            'backup_frequency' => 'daily',
            'notification_email' => 'admin@darularqam.edu'
        ];

        $emailSettings = [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '587',
            'smtp_encryption' => 'tls',
            'smtp_username' => 'noreply@darularqam.edu',
            'from_name' => 'Darul Arqam School',
            'from_email' => 'noreply@darularqam.edu'
        ];

        $gradeSettings = [
            'passing_grade' => 50,
            'grade_scale' => 'percentage',
            'decimal_places' => 2,
            'show_ranking' => true,
            'allow_grade_comments' => true
        ];

        $attendanceSettings = [
            'track_time' => true,
            'mark_late_minutes' => 15,
            'require_absence_reason' => true,
            'notify_parents' => true,
            'attendance_percentage_alert' => 75
        ];

        return view('settings.index', compact(
            'schoolSettings',
            'systemSettings',
            'emailSettings',
            'gradeSettings',
            'attendanceSettings'
        ));
    }

    public function update(Request $request)
    {
        // In a real application, you would validate and save the settings
        // For demo purposes, we'll just redirect with a success message

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
