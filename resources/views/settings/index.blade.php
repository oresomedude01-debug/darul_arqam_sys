@extends('layouts.spa')

@section('title', 'Settings')

@section('breadcrumb')
    <span class="font-semibold text-gray-900">Settings</span>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'school' }">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600 mt-1">Manage your school's configuration and preferences</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center space-x-3">
        <i class="fas fa-check-circle text-green-600"></i>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Settings Tabs -->
    <div class="card">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'school'"
                        :class="activeTab === 'school' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-school mr-2"></i>
                    School Information
                </button>
                <button @click="activeTab = 'system'"
                        :class="activeTab === 'system' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-cog mr-2"></i>
                    System Settings
                </button>
                <button @click="activeTab = 'email'"
                        :class="activeTab === 'email' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    Email Settings
                </button>
                <button @click="activeTab = 'grading'"
                        :class="activeTab === 'grading' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Grading System
                </button>
                <button @click="activeTab = 'attendance'"
                        :class="activeTab === 'attendance' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Attendance
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <form method="POST" action="{{ route('settings.update') }}" class="p-6">
            @csrf

            <!-- School Information Tab -->
            <div x-show="activeTab === 'school'" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">School Information</h3>
                        <p class="text-sm text-gray-600 mb-6">Basic information about your school</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">School Name</label>
                            <input type="text" name="school_name" value="{{ $schoolSettings['school_name'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">School Code</label>
                            <input type="text" name="school_code" value="{{ $schoolSettings['school_code'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ $schoolSettings['phone'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ $schoolSettings['email'] }}"
                                   class="form-input w-full">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3" class="form-input w-full">{{ $schoolSettings['address'] }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" value="{{ $schoolSettings['website'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                            <input type="text" name="academic_year" value="{{ $schoolSettings['academic_year'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" class="form-select w-full">
                                <option value="America/New_York" {{ $schoolSettings['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                                <option value="America/Chicago" {{ $schoolSettings['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time (CT)</option>
                                <option value="America/Denver" {{ $schoolSettings['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time (MT)</option>
                                <option value="America/Los_Angeles" {{ $schoolSettings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                            <select name="language" class="form-select w-full">
                                <option value="en" {{ $schoolSettings['language'] == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ $schoolSettings['language'] == 'ar' ? 'selected' : '' }}>Arabic</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- System Settings Tab -->
            <div x-show="activeTab === 'system'" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Settings</h3>
                        <p class="text-sm text-gray-600 mb-6">Configure system-wide preferences</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Maintenance Mode</p>
                                <p class="text-sm text-gray-600">Disable public access to the system</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" value="1"
                                       {{ $systemSettings['maintenance_mode'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Allow Public Registration</p>
                                <p class="text-sm text-gray-600">Enable public student enrollment</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="allow_registration" value="1"
                                       {{ $systemSettings['allow_registration'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Require Email Verification</p>
                                <p class="text-sm text-gray-600">Users must verify email before access</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="require_email_verification" value="1"
                                       {{ $systemSettings['require_email_verification'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                                <input type="number" name="session_timeout" value="{{ $systemSettings['session_timeout'] }}"
                                       class="form-input w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                                <input type="number" name="max_login_attempts" value="{{ $systemSettings['max_login_attempts'] }}"
                                       class="form-input w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Frequency</label>
                                <select name="backup_frequency" class="form-select w-full">
                                    <option value="daily" {{ $systemSettings['backup_frequency'] == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $systemSettings['backup_frequency'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $systemSettings['backup_frequency'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notification Email</label>
                                <input type="email" name="notification_email" value="{{ $systemSettings['notification_email'] }}"
                                       class="form-input w-full">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Email Settings Tab -->
            <div x-show="activeTab === 'email'" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Settings</h3>
                        <p class="text-sm text-gray-600 mb-6">Configure email delivery settings</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" name="smtp_host" value="{{ $emailSettings['smtp_host'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="text" name="smtp_port" value="{{ $emailSettings['smtp_port'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                            <select name="smtp_encryption" class="form-select w-full">
                                <option value="tls" {{ $emailSettings['smtp_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $emailSettings['smtp_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ $emailSettings['smtp_encryption'] == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                            <input type="text" name="smtp_username" value="{{ $emailSettings['smtp_username'] }}"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                            <input type="password" name="smtp_password" placeholder="••••••••"
                                   class="form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                            <input type="text" name="from_name" value="{{ $emailSettings['from_name'] }}"
                                   class="form-input w-full">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                            <input type="email" name="from_email" value="{{ $emailSettings['from_email'] }}"
                                   class="form-input w-full">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Test Email Configuration</p>
                                <p class="text-sm text-blue-700 mt-1">After saving, send a test email to verify your settings are correct.</p>
                                <button type="button" class="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    <i class="fas fa-paper-plane mr-1"></i>
                                    Send Test Email
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grading System Tab -->
            <div x-show="activeTab === 'grading'" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Grading System Settings</h3>
                        <p class="text-sm text-gray-600 mb-6">Configure how grades are calculated and displayed</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passing Grade</label>
                            <input type="number" name="passing_grade" value="{{ $gradeSettings['passing_grade'] }}"
                                   min="0" max="100" class="form-input w-full">
                            <p class="text-xs text-gray-500 mt-1">Minimum grade required to pass</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Grade Scale</label>
                            <select name="grade_scale" class="form-select w-full">
                                <option value="percentage" {{ $gradeSettings['grade_scale'] == 'percentage' ? 'selected' : '' }}>Percentage (0-100)</option>
                                <option value="gpa" {{ $gradeSettings['grade_scale'] == 'gpa' ? 'selected' : '' }}>GPA (0.0-4.0)</option>
                                <option value="letter" {{ $gradeSettings['grade_scale'] == 'letter' ? 'selected' : '' }}>Letter Grade (A-F)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Decimal Places</label>
                            <select name="decimal_places" class="form-select w-full">
                                <option value="0" {{ $gradeSettings['decimal_places'] == 0 ? 'selected' : '' }}>0 (85)</option>
                                <option value="1" {{ $gradeSettings['decimal_places'] == 1 ? 'selected' : '' }}>1 (85.5)</option>
                                <option value="2" {{ $gradeSettings['decimal_places'] == 2 ? 'selected' : '' }}>2 (85.50)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Show Student Ranking</p>
                                <p class="text-sm text-gray-600">Display class rank on report cards</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_ranking" value="1"
                                       {{ $gradeSettings['show_ranking'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Allow Grade Comments</p>
                                <p class="text-sm text-gray-600">Teachers can add comments to grades</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="allow_grade_comments" value="1"
                                       {{ $gradeSettings['allow_grade_comments'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Attendance Tab -->
            <div x-show="activeTab === 'attendance'" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Settings</h3>
                        <p class="text-sm text-gray-600 mb-6">Configure attendance tracking preferences</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Track Attendance Time</p>
                                <p class="text-sm text-gray-600">Record exact time of attendance marking</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="track_time" value="1"
                                       {{ $attendanceSettings['track_time'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Require Absence Reason</p>
                                <p class="text-sm text-gray-600">Mandatory reason for absent students</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="require_absence_reason" value="1"
                                       {{ $attendanceSettings['require_absence_reason'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Notify Parents</p>
                                <p class="text-sm text-gray-600">Send email notifications for absences</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_parents" value="1"
                                       {{ $attendanceSettings['notify_parents'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mark as Late After (minutes)</label>
                            <input type="number" name="mark_late_minutes" value="{{ $attendanceSettings['mark_late_minutes'] }}"
                                   min="1" max="60" class="form-input w-full">
                            <p class="text-xs text-gray-500 mt-1">Minutes after start time to mark as late</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Low Attendance Alert (%)</label>
                            <input type="number" name="attendance_percentage_alert" value="{{ $attendanceSettings['attendance_percentage_alert'] }}"
                                   min="0" max="100" class="form-input w-full">
                            <p class="text-xs text-gray-500 mt-1">Alert when attendance falls below this percentage</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
