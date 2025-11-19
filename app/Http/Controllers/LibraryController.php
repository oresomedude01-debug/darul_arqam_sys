<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        // Demo library data
        $books = [
            [
                'id' => 1,
                'title' => 'Introduction to Islamic Studies',
                'author' => 'Dr. Ahmad Hassan',
                'isbn' => '978-1-234567-89-0',
                'category' => 'Islamic Studies',
                'copies' => 15,
                'available' => 12,
                'borrowed' => 3,
                'status' => 'available'
            ],
            [
                'id' => 2,
                'title' => 'Advanced Mathematics',
                'author' => 'Prof. Sarah Johnson',
                'isbn' => '978-1-234567-90-6',
                'category' => 'Mathematics',
                'copies' => 20,
                'available' => 15,
                'borrowed' => 5,
                'status' => 'available'
            ],
            [
                'id' => 3,
                'title' => 'English Literature Anthology',
                'author' => 'Multiple Authors',
                'isbn' => '978-1-234567-91-3',
                'category' => 'English',
                'copies' => 10,
                'available' => 8,
                'borrowed' => 2,
                'status' => 'available'
            ],
            [
                'id' => 4,
                'title' => 'Science Experiments for Students',
                'author' => 'Dr. Michael Chen',
                'isbn' => '978-1-234567-92-0',
                'category' => 'Science',
                'copies' => 12,
                'available' => 0,
                'borrowed' => 12,
                'status' => 'unavailable'
            ],
            [
                'id' => 5,
                'title' => 'World History: Modern Era',
                'author' => 'Prof. David Martinez',
                'isbn' => '978-1-234567-93-7',
                'category' => 'History',
                'copies' => 18,
                'available' => 14,
                'borrowed' => 4,
                'status' => 'available'
            ],
            [
                'id' => 6,
                'title' => 'Arabic Grammar Fundamentals',
                'author' => 'Dr. Fatima Al-Zahrani',
                'isbn' => '978-1-234567-94-4',
                'category' => 'Arabic',
                'copies' => 25,
                'available' => 20,
                'borrowed' => 5,
                'status' => 'available'
            ],
            [
                'id' => 7,
                'title' => 'Quran Tafsir for Beginners',
                'author' => 'Sheikh Abdullah Rahman',
                'isbn' => '978-1-234567-95-1',
                'category' => 'Islamic Studies',
                'copies' => 30,
                'available' => 22,
                'borrowed' => 8,
                'status' => 'available'
            ],
            [
                'id' => 8,
                'title' => 'Computer Programming Basics',
                'author' => 'Dr. Lisa Wang',
                'isbn' => '978-1-234567-96-8',
                'category' => 'Computer Science',
                'copies' => 8,
                'available' => 3,
                'borrowed' => 5,
                'status' => 'available'
            ],
        ];

        $stats = [
            'total_books' => collect($books)->sum('copies'),
            'available_books' => collect($books)->sum('available'),
            'borrowed_books' => collect($books)->sum('borrowed'),
            'total_titles' => count($books),
        ];

        $categories = [
            'Islamic Studies' => 2,
            'Mathematics' => 1,
            'English' => 1,
            'Science' => 1,
            'History' => 1,
            'Arabic' => 1,
            'Computer Science' => 1,
        ];

        return view('library.index', compact('books', 'stats', 'categories'));
    }

    public function lending()
    {
        // Demo lending data
        $borrowedBooks = [
            [
                'id' => 1,
                'student_name' => 'Ahmed Ali',
                'student_id' => 'STD-2024-001',
                'book_title' => 'Introduction to Islamic Studies',
                'borrow_date' => '2025-01-15',
                'due_date' => '2025-02-15',
                'status' => 'active',
                'days_remaining' => 10
            ],
            [
                'id' => 2,
                'student_name' => 'Fatima Hassan',
                'student_id' => 'STD-2024-002',
                'book_title' => 'Advanced Mathematics',
                'borrow_date' => '2025-01-10',
                'due_date' => '2025-02-10',
                'status' => 'active',
                'days_remaining' => 5
            ],
            [
                'id' => 3,
                'student_name' => 'Omar Mohammed',
                'student_id' => 'STD-2024-003',
                'book_title' => 'Science Experiments for Students',
                'borrow_date' => '2025-01-05',
                'due_date' => '2025-02-05',
                'status' => 'overdue',
                'days_remaining' => -10
            ],
            [
                'id' => 4,
                'student_name' => 'Aisha Abdullah',
                'student_id' => 'STD-2024-004',
                'book_title' => 'World History: Modern Era',
                'borrow_date' => '2025-01-18',
                'due_date' => '2025-02-18',
                'status' => 'active',
                'days_remaining' => 13
            ],
            [
                'id' => 5,
                'student_name' => 'Yusuf Ibrahim',
                'student_id' => 'STD-2024-005',
                'book_title' => 'Arabic Grammar Fundamentals',
                'borrow_date' => '2024-12-20',
                'due_date' => '2025-01-20',
                'status' => 'returned',
                'days_remaining' => null
            ],
        ];

        $lendingStats = [
            'active_loans' => 3,
            'overdue_loans' => 1,
            'returned_today' => 2,
            'total_loans_this_month' => 28
        ];

        return view('library.lending', compact('borrowedBooks', 'lendingStats'));
    }
}
