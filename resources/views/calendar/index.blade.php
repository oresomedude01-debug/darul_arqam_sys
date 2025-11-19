@extends('layouts.spa')

@section('title', 'Academic Calendar')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Academic Calendar</h2>
            <p class="text-muted mb-0">{{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="goToToday()">
                <i class="fas fa-calendar-day"></i> Today
            </button>
            <button type="button" class="btn btn-primary" onclick="openAddEventModal()">
                <i class="fas fa-plus"></i> Add Event
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Calendar Main Section -->
        <div class="col-lg-9 mb-4">
            <!-- Calendar Navigation -->
            <div class="card shadow-sm mb-3">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="{{ route('calendar.index', ['year' => $prevYear, 'month' => $prevMonth]) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                            <a href="{{ route('calendar.index', ['year' => $nextYear, 'month' => $nextMonth]) }}" class="btn btn-sm btn-outline-secondary">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>

                        <!-- Event Type Legend -->
                        <div class="d-none d-md-flex gap-2 flex-wrap">
                            <span class="badge" style="background-color: #10b981;">Term Start</span>
                            <span class="badge" style="background-color: #ef4444;">Term End</span>
                            <span class="badge" style="background-color: #f59e0b;">Holiday</span>
                            <span class="badge" style="background-color: #8b5cf6;">Exam</span>
                            <span class="badge" style="background-color: #3b82f6;">Meeting</span>
                            <span class="badge" style="background-color: #06b6d4;">Special</span>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="calendar-grid">
                        <!-- Calendar Header (Days of Week) -->
                        <div class="calendar-header">
                            <div class="calendar-day-name">Sunday</div>
                            <div class="calendar-day-name">Monday</div>
                            <div class="calendar-day-name">Tuesday</div>
                            <div class="calendar-day-name">Wednesday</div>
                            <div class="calendar-day-name">Thursday</div>
                            <div class="calendar-day-name">Friday</div>
                            <div class="calendar-day-name">Saturday</div>
                        </div>

                        <!-- Calendar Body -->
                        <div class="calendar-body">
                            @php
                                $startDate = \Carbon\Carbon::create($year, $month, 1);
                                $endDate = $startDate->copy()->endOfMonth();
                                $firstDayOfWeek = $startDate->dayOfWeek; // 0 = Sunday
                                $daysInMonth = $startDate->daysInMonth;
                                $today = \Carbon\Carbon::today();

                                // Group events by date
                                $eventsByDate = [];
                                foreach ($events as $event) {
                                    $eventStart = $event->start_date;
                                    $eventEnd = $event->end_date ?? $event->start_date;

                                    $current = $eventStart->copy();
                                    while ($current->lte($eventEnd) && $current->lte($endDate)) {
                                        if ($current->gte($startDate)) {
                                            $dateKey = $current->format('Y-m-d');
                                            if (!isset($eventsByDate[$dateKey])) {
                                                $eventsByDate[$dateKey] = [];
                                            }
                                            $eventsByDate[$dateKey][] = $event;
                                        }
                                        $current->addDay();
                                    }
                                }
                            @endphp

                            @for ($week = 0; $week < 6; $week++)
                                @php
                                    $weekHasDays = false;
                                @endphp

                                @for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                                    @php
                                        $dayNumber = ($week * 7) + $dayOfWeek - $firstDayOfWeek + 1;
                                        $isValidDay = $dayNumber >= 1 && $dayNumber <= $daysInMonth;

                                        if ($isValidDay) {
                                            $weekHasDays = true;
                                            $currentDate = \Carbon\Carbon::create($year, $month, $dayNumber);
                                            $dateKey = $currentDate->format('Y-m-d');
                                            $dayEvents = $eventsByDate[$dateKey] ?? [];
                                            $isToday = $currentDate->isSameDay($today);
                                        }
                                    @endphp
                                @endfor

                                @if ($weekHasDays)
                                    <div class="calendar-week">
                                        @for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                                            @php
                                                $dayNumber = ($week * 7) + $dayOfWeek - $firstDayOfWeek + 1;
                                                $isValidDay = $dayNumber >= 1 && $dayNumber <= $daysInMonth;

                                                if ($isValidDay) {
                                                    $currentDate = \Carbon\Carbon::create($year, $month, $dayNumber);
                                                    $dateKey = $currentDate->format('Y-m-d');
                                                    $dayEvents = $eventsByDate[$dateKey] ?? [];
                                                    $isToday = $currentDate->isSameDay($today);
                                                }
                                            @endphp

                                            <div class="calendar-day {{ $isValidDay ? '' : 'empty' }} {{ $isValidDay && $isToday ? 'today' : '' }}">
                                                @if ($isValidDay)
                                                    <div class="day-number">{{ $dayNumber }}</div>
                                                    <div class="day-events">
                                                        @foreach (array_slice($dayEvents, 0, 3) as $event)
                                                            <div class="event-badge"
                                                                 style="background-color: {{ $event->type_color }}; cursor: pointer;"
                                                                 onclick='showEventDetails(@json($event))'
                                                                 title="{{ $event->title }}">
                                                                <span class="event-title">{{ Str::limit($event->title, 15) }}</span>
                                                            </div>
                                                        @endforeach
                                                        @if (count($dayEvents) > 3)
                                                            <div class="event-more">+{{ count($dayEvents) - 3 }} more</div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Current Term Card -->
            @if ($currentTerm)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-calendar-check text-primary"></i> Current Term
                    </h6>
                    <h5 class="mb-2">{{ $currentTerm->name }}</h5>
                    <span class="badge badge-{{ $currentTerm->status_badge }}">{{ ucfirst($currentTerm->status) }}</span>
                    <hr>
                    <div class="small text-muted">
                        <div class="mb-1">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $currentTerm->start_date->format('M d, Y') }} - {{ $currentTerm->end_date->format('M d, Y') }}
                        </div>
                        <div>
                            <i class="fas fa-hourglass-half"></i>
                            {{ $currentTerm->duration_days }} days
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Upcoming Events Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-clock text-warning"></i> Upcoming Events
                    </h6>
                    @if ($upcomingEvents->count() > 0)
                        <div class="upcoming-events-list">
                            @foreach ($upcomingEvents as $event)
                                <div class="upcoming-event-item mb-3" onclick='showEventDetails(@json($event))' style="cursor: pointer;">
                                    <div class="d-flex align-items-start">
                                        <div class="event-date-badge text-center me-2">
                                            <div class="event-date-day">{{ $event->start_date->format('d') }}</div>
                                            <div class="event-date-month">{{ $event->start_date->format('M') }}</div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $event->title }}</h6>
                                            <span class="badge badge-sm" style="background-color: {{ $event->type_color }};">
                                                {{ $event->type_name }}
                                            </span>
                                            @if ($event->start_time)
                                                <div class="small text-muted mt-1">
                                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr class="my-2">
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('calendar.events.list') }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                            View All Events
                        </a>
                    @else
                        <p class="text-muted mb-0 small">No upcoming events</p>
                    @endif
                </div>
            </div>

            <!-- Quick Links Card -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-link"></i> Quick Links
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('calendar.events.list') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-list"></i> All Events
                        </a>
                        <a href="{{ route('calendar.terms') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-graduation-cap"></i> Manage Terms
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<div class="mobile-bottom-nav d-lg-none">
    <a href="{{ route('calendar.index') }}" class="nav-item active">
        <i class="fas fa-calendar"></i>
        <span>Calendar</span>
    </a>
    <a href="{{ route('calendar.events.list') }}" class="nav-item">
        <i class="fas fa-list"></i>
        <span>Events</span>
    </a>
    <a href="{{ route('calendar.terms') }}" class="nav-item">
        <i class="fas fa-graduation-cap"></i>
        <span>Terms</span>
    </a>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsTitle">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="eventDetailsBody">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editEventBtn" onclick="editEventFromDetails()">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button type="button" class="btn btn-danger" id="deleteEventBtn" onclick="deleteEvent()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div class="modal fade" id="eventFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="eventForm" onsubmit="submitEventForm(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventFormTitle">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="eventId" name="id">

                    <div class="mb-3">
                        <label for="eventTitle" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eventTitle" name="title" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="eventType" class="form-label">Event Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="eventType" name="type" required>
                                <option value="special">Special Event</option>
                                <option value="term_start">Term Start</option>
                                <option value="term_end">Term End</option>
                                <option value="holiday">Holiday</option>
                                <option value="exam">Exam</option>
                                <option value="meeting">Meeting</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="eventColor" class="form-label">Custom Color (Optional)</label>
                            <input type="color" class="form-control form-control-color" id="eventColor" name="color">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="eventStartDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eventStartDate" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="eventEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="eventEndDate" name="end_date">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="eventStartTime" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="eventStartTime" name="start_time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="eventEndTime" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="eventEndTime" name="end_time">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="eventAffectedClasses" class="form-label">Affected Classes</label>
                        <select class="form-select" id="eventAffectedClasses" name="affected_classes[]" multiple>
                            <option value="all">All Classes</option>
                            @foreach ($classes ?? [] as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple classes</small>
                    </div>

                    <div class="mb-3">
                        <label for="eventAcademicTerm" class="form-label">Academic Term</label>
                        <select class="form-select" id="eventAcademicTerm" name="academic_term_id">
                            <option value="">-- Select Term --</option>
                            @foreach ($terms ?? [] as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitEventBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="submitSpinner"></span>
                        Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .calendar-grid {
        overflow-x: auto;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .calendar-day-name {
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #495057;
        border-right: 1px solid #dee2e6;
    }

    .calendar-day-name:last-child {
        border-right: none;
    }

    .calendar-body {
        min-height: 500px;
    }

    .calendar-week {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        border-bottom: 1px solid #dee2e6;
    }

    .calendar-day {
        min-height: 100px;
        padding: 8px;
        border-right: 1px solid #dee2e6;
        background-color: #fff;
        transition: background-color 0.2s;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.empty {
        background-color: #f8f9fa;
    }

    .calendar-day.today {
        background-color: #e7f3ff;
    }

    .calendar-day:last-child {
        border-right: none;
    }

    .day-number {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 4px;
        color: #495057;
    }

    .calendar-day.today .day-number {
        background-color: #007bff;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .day-events {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .event-badge {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.7rem;
        color: white;
        transition: opacity 0.2s, transform 0.2s;
    }

    .event-badge:hover {
        opacity: 0.8;
        transform: translateX(2px);
    }

    .event-title {
        font-weight: 500;
    }

    .event-more {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .event-date-badge {
        width: 50px;
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 8px;
        border: 1px solid #dee2e6;
    }

    .event-date-day {
        font-size: 1.5rem;
        font-weight: 700;
        color: #007bff;
        line-height: 1;
    }

    .event-date-month {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
    }

    .upcoming-event-item {
        transition: background-color 0.2s;
        padding: 8px;
        border-radius: 6px;
    }

    .upcoming-event-item:hover {
        background-color: #f8f9fa;
    }

    .mobile-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: white;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-around;
        padding: 8px 0;
        z-index: 1000;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }

    .mobile-bottom-nav .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #6c757d;
        font-size: 0.75rem;
        padding: 4px 12px;
        transition: color 0.2s;
    }

    .mobile-bottom-nav .nav-item i {
        font-size: 1.25rem;
        margin-bottom: 2px;
    }

    .mobile-bottom-nav .nav-item.active {
        color: #007bff;
    }

    @media print {
        .btn, .mobile-bottom-nav, .modal, .card-title i {
            display: none !important;
        }

        .calendar-day {
            page-break-inside: avoid;
        }
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 4px;
        }

        .day-number {
            font-size: 0.75rem;
        }

        .event-badge {
            font-size: 0.65rem;
            padding: 1px 4px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentEventId = null;

    function goToToday() {
        const today = new Date();
        window.location.href = '{{ route("calendar.index") }}?year=' + today.getFullYear() + '&month=' + (today.getMonth() + 1);
    }

    function showEventDetails(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        currentEventId = event.id;

        const startDate = new Date(event.start_date);
        const endDate = event.end_date ? new Date(event.end_date) : null;

        let dateDisplay = startDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        if (endDate && endDate.getTime() !== startDate.getTime()) {
            dateDisplay += ' - ' + endDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }

        let timeDisplay = '';
        if (event.start_time) {
            timeDisplay = '<div class="mb-2"><strong>Time:</strong> ' + event.start_time;
            if (event.end_time) {
                timeDisplay += ' - ' + event.end_time;
            }
            timeDisplay += '</div>';
        }

        const html = `
            <div class="mb-3">
                <span class="badge" style="background-color: ${event.type_color};">${event.type_name}</span>
            </div>
            <div class="mb-2">
                <strong>Date:</strong> ${dateDisplay}
            </div>
            ${timeDisplay}
            ${event.description ? '<div class="mb-2"><strong>Description:</strong><br>' + event.description + '</div>' : ''}
            ${event.academic_term ? '<div class="mb-2"><strong>Term:</strong> ' + event.academic_term.name + '</div>' : ''}
        `;

        document.getElementById('eventDetailsTitle').textContent = event.title;
        document.getElementById('eventDetailsBody').innerHTML = html;
        modal.show();
    }

    function openAddEventModal() {
        currentEventId = null;
        document.getElementById('eventForm').reset();
        document.getElementById('eventFormTitle').textContent = 'Add Event';
        document.getElementById('eventId').value = '';

        const modal = new bootstrap.Modal(document.getElementById('eventFormModal'));
        modal.show();
    }

    function editEventFromDetails() {
        // Close details modal
        const detailsModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal'));
        detailsModal.hide();

        // Fetch event data and open edit modal
        if (currentEventId) {
            fetch(`/calendar/events/${currentEventId}`)
                .then(response => response.json())
                .then(event => {
                    editEvent(event);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load event data');
                });
        }
    }

    function editEvent(event) {
        currentEventId = event.id;
        document.getElementById('eventFormTitle').textContent = 'Edit Event';
        document.getElementById('eventId').value = event.id;
        document.getElementById('eventTitle').value = event.title;
        document.getElementById('eventType').value = event.type;
        document.getElementById('eventStartDate').value = event.start_date;
        document.getElementById('eventEndDate').value = event.end_date || '';
        document.getElementById('eventStartTime').value = event.start_time || '';
        document.getElementById('eventEndTime').value = event.end_time || '';
        document.getElementById('eventDescription').value = event.description || '';
        document.getElementById('eventColor').value = event.color || '#6b7280';
        document.getElementById('eventAcademicTerm').value = event.academic_term_id || '';

        // Handle affected classes (if multi-select is populated)
        if (event.affected_classes && Array.isArray(event.affected_classes)) {
            const select = document.getElementById('eventAffectedClasses');
            Array.from(select.options).forEach(option => {
                option.selected = event.affected_classes.includes(option.value) || event.affected_classes.includes(parseInt(option.value));
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('eventFormModal'));
        modal.show();
    }

    function submitEventForm(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitEventBtn');
        const spinner = document.getElementById('submitSpinner');
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(e.target);
        const eventId = document.getElementById('eventId').value;
        const url = eventId ? `/calendar/events/${eventId}` : '/calendar/events';
        const method = eventId ? 'PUT' : 'POST';

        // Convert FormData to JSON
        const data = {};
        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                const actualKey = key.slice(0, -2);
                if (!data[actualKey]) data[actualKey] = [];
                data[actualKey].push(value);
            } else {
                data[key] = value;
            }
        });

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the event');
        })
        .finally(() => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    }

    function deleteEvent() {
        if (!currentEventId) return;

        if (!confirm('Are you sure you want to delete this event?')) {
            return;
        }

        fetch(`/calendar/events/${currentEventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the event');
        });
    }
</script>
@endpush
