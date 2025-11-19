<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    /**
     * Show the timetable management page for a class
     */
    public function index(SchoolClass $class)
    {
        $class->load([
            'timetables' => function($query) {
                $query->orderBy('period_number')
                      ->orderBy('day_of_week');
            },
            'timetables.subject',
            'timetables.teacher',
            'subjects'
        ]);

        // Get available subjects for this class
        $subjects = $class->subjects;

        // Get all active teachers
        $teachers = Teacher::active()->orderBy('first_name')->get();

        // Days of the week
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        return view('classes.timetable.index', compact('class', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a new timetable entry
     */
    public function store(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'period_number' => 'required|integer|min:1',
            'type' => 'required|in:class,break,lunch,assembly',
            'room_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Check for overlapping periods
        $existing = $class->timetables()
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', $validated['start_time'])
            ->exists();

        if ($existing) {
            return redirect()
                ->route('classes.timetable.index', $class)
                ->with('error', 'A period already exists at this time on this day.');
        }

        $validated['school_class_id'] = $class->id;
        Timetable::create($validated);

        return redirect()
            ->route('classes.timetable.index', $class)
            ->with('success', 'Timetable entry created successfully!');
    }

    /**
     * Update a timetable entry
     */
    public function update(Request $request, SchoolClass $class, Timetable $timetable)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'period_number' => 'required|integer|min:1',
            'type' => 'required|in:class,break,lunch,assembly',
            'room_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Check for overlapping periods (excluding current entry)
        $existing = $class->timetables()
            ->where('id', '!=', $timetable->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', $validated['start_time'])
            ->exists();

        if ($existing) {
            return redirect()
                ->route('classes.timetable.index', $class)
                ->with('error', 'A period already exists at this time on this day.');
        }

        $timetable->update($validated);

        return redirect()
            ->route('classes.timetable.index', $class)
            ->with('success', 'Timetable entry updated successfully!');
    }

    /**
     * Delete a timetable entry
     */
    public function destroy(SchoolClass $class, Timetable $timetable)
    {
        $timetable->delete();

        return redirect()
            ->route('classes.timetable.index', $class)
            ->with('success', 'Timetable entry deleted successfully!');
    }

    /**
     * Bulk create timetable entries
     */
    public function bulkStore(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'entries' => 'required|array|min:1',
            'entries.*.subject_id' => 'nullable|exists:subjects,id',
            'entries.*.teacher_id' => 'nullable|exists:teachers,id',
            'entries.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'entries.*.start_time' => 'required|date_format:H:i',
            'entries.*.end_time' => 'required|date_format:H:i',
            'entries.*.period_number' => 'required|integer|min:1',
            'entries.*.type' => 'required|in:class,break,lunch,assembly',
        ]);

        $created = 0;
        foreach ($validated['entries'] as $entry) {
            // Check for duplicates
            $existing = $class->timetables()
                ->where('day_of_week', $entry['day_of_week'])
                ->where('start_time', $entry['start_time'])
                ->exists();

            if (!$existing) {
                $entry['school_class_id'] = $class->id;
                Timetable::create($entry);
                $created++;
            }
        }

        return redirect()
            ->route('classes.timetable.index', $class)
            ->with('success', "$created timetable entries created successfully!");
    }
}
