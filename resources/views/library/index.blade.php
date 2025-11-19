@extends('layouts.spa')

@section('title', 'Library')

@section('breadcrumb')
    <span class="font-semibold text-gray-900">Library</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Library Management</h1>
            <p class="text-gray-600 mt-1">Browse and manage the school library catalog</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('library.lending') }}" class="btn btn-secondary">
                <i class="fas fa-hand-holding mr-2"></i>
                Manage Lending
            </a>
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Add Book
            </button>
        </div>
    </div>

    <!-- Library Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Books</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['total_books']) }}</h3>
                </div>
                <i class="fas fa-books text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Available</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['available_books']) }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-orange-500 to-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Borrowed</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['borrowed_books']) }}</h3>
                </div>
                <i class="fas fa-hand-holding text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Titles</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['total_titles']) }}</h3>
                </div>
                <i class="fas fa-book text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Book Categories</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @foreach($categories as $category => $count)
                <button class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all">
                    <i class="fas fa-book text-2xl text-primary-600 mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">{{ $category }}</span>
                    <span class="text-xs text-gray-600 mt-1">{{ $count }} {{ Str::plural('title', $count) }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" placeholder="Search by title, author, or ISBN..."
                               class="form-input w-full pl-10">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <select class="form-select">
                        <option>All Categories</option>
                        @foreach($categories as $category => $count)
                        <option>{{ $category }}</option>
                        @endforeach
                    </select>
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Available</option>
                        <option>Borrowed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Catalog -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Book Catalog</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Category</th>
                            <th>Total Copies</th>
                            <th>Available</th>
                            <th>Borrowed</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td class="font-medium">{{ $book['title'] }}</td>
                            <td>{{ $book['author'] }}</td>
                            <td class="font-mono text-sm">{{ $book['isbn'] }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $book['category'] }}</span>
                            </td>
                            <td class="text-center">{{ $book['copies'] }}</td>
                            <td class="text-center">
                                <span class="font-bold text-green-600">{{ $book['available'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-bold text-orange-600">{{ $book['borrowed'] }}</span>
                            </td>
                            <td>
                                @if($book['status'] === 'available')
                                    <span class="badge badge-success">Available</span>
                                @else
                                    <span class="badge badge-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <button class="text-primary-600 hover:text-primary-700" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-blue-600 hover:text-blue-700" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-700" title="Lend Book">
                                        <i class="fas fa-hand-holding"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
