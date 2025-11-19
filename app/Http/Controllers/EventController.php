<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AcademicTerm;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display calendar dashboard
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // Get events for the current month view
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $events = Event::inDateRange($startDate, $endDate)
            ->orderBy('start_date')
            ->get();
        
        // Get upcoming events
        $upcomingEvents = Event::upcoming()->limit(5)->get();
        
        // Get current term
        $currentTerm = AcademicTerm::active()->first();
        
        // Get all terms for filter
        $academicTerms = AcademicTerm::orderBy('start_date', 'desc')->get();
        
        // Get classes for filter
        $classes = SchoolClass::active()->orderBy('name')->get();
        
        return view('calendar.index', compact(
            'events',
            'upcomingEvents',
            'currentTerm',
            'academicTerms',
            'classes',
            'year',
            'month'
        ));
    }

    /**
     * Get events for calendar (AJAX)
     */
    public function getEvents(Request $request)
    {
        $startDate = $request->get('start');
        $endDate = $request->get('end');
        
        $events = Event::inDateRange($startDate, $endDate)->get();
        
        return response()->json($events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d') . ($event->start_time ? 'T' . $event->start_time : ''),
                'end' => ($event->end_date ?? $event->start_date)->format('Y-m-d') . ($event->end_time ? 'T' . $event->end_time : ''),
                'color' => $event->type_color,
                'type' => $event->type,
                'description' => $event->description,
            ];
        }));
    }

    /**
     * Store new event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:term_start,term_end,holiday,exam,meeting,special',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'affected_classes' => 'nullable|array',
            'academic_term_id' => 'nullable|exists:academic_terms,id',
        ]);

        $event = Event::create($validated);

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:term_start,term_end,holiday,exam,meeting,special',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'affected_classes' => 'nullable|array',
            'academic_term_id' => 'nullable|exists:academic_terms,id',
        ]);

        $event->update($validated);

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Events list view with filters
     */
    public function eventsList(Request $request)
    {
        $query = Event::with('academicTerm');

        // Filters
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        if ($request->filled('academic_term_id')) {
            $query->where('academic_term_id', $request->academic_term_id);
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(20)->withQueryString();

        $academicTerms = AcademicTerm::orderBy('start_date', 'desc')->get();

        return view('calendar.events-list', compact('events', 'academicTerms'));
    }

    /**
     * Academic terms management
     */
    public function terms()
    {
        $terms = AcademicTerm::orderBy('start_date', 'desc')->get();
        return view('calendar.terms.index', compact('terms'));
    }

    /**
     * Store academic term
     */
    public function storeTerm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'session' => 'required|string|max:255',
            'term' => 'required|in:First Term,Second Term,Third Term',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        AcademicTerm::create($validated);

        return redirect()
            ->route('calendar.terms')
            ->with('success', 'Academic term created successfully!');
    }

    /**
     * Update academic term
     */
    public function updateTerm(Request $request, AcademicTerm $term)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'session' => 'required|string|max:255',
            'term' => 'required|in:First Term,Second Term,Third Term',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $term->update($validated);

        return redirect()
            ->route('calendar.terms')
            ->with('success', 'Academic term updated successfully!');
    }

    /**
     * Delete academic term
     */
    public function destroyTerm(AcademicTerm $term)
    {
        $term->delete();

        return redirect()
            ->route('calendar.terms')
            ->with('success', 'Academic term deleted successfully!');
    }
}
