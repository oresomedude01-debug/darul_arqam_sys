<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title id="page-title">@yield('title', 'Dashboard') - Darul Arqam</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap CSS for calendar views -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }

        /* Loading spinner */
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #0284c7;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Page transition */
        .page-transition {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Active nav link indicator */
        .nav-link-active {
            background: rgba(255, 255, 255, 0.1) !important;
            border-left: 4px solid #fbbf24;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased"
      x-data="spaApp()"
      x-init="initSPA()"
      @popstate.window="handlePopState($event)">

    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen"
         @click="mobileMenuOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"
         x-cloak>
    </div>

    <!-- Sidebar -->
    <aside x-show="sidebarOpen || mobileMenuOpen"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           :class="sidebarCollapsed && !mobileMenuOpen ? 'lg:w-20' : 'lg:w-64'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900 text-white shadow-2xl transition-all duration-300 lg:translate-x-0"
           x-cloak>

        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-primary-700/50">
            <div class="flex items-center space-x-3 overflow-hidden">
                <div class="flex-shrink-0 w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity duration-200">
                    <h1 class="text-base font-bold leading-tight">Darul Arqam</h1>
                    <p class="text-xs text-primary-200">School System</p>
                </div>
            </div>

            <!-- Mobile Close Button -->
            <button @click="mobileMenuOpen = false"
                    class="lg:hidden text-white/80 hover:text-white p-2 rounded-lg hover:bg-primary-700/30 transition">
                <i class="fas fa-times text-lg"></i>
            </button>

            <!-- Desktop Collapse Button -->
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:block text-white/80 hover:text-white p-2 rounded-lg hover:bg-primary-700/30 transition">
                <i :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'" class="fas text-sm"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="/dashboard"
               @click.prevent="navigate('/dashboard')"
               :class="currentPath === '/dashboard' ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-home text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Dashboard</span>
            </a>

            <!-- Students -->
            <div x-data="{ open: currentPath.includes('/students') }">
                <button @click="open = !open"
                        :class="currentPath.includes('/students') ? 'bg-primary-700/50 text-white' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                    <i class="fas fa-user-graduate text-base w-5"></i>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen" class="flex-1 text-left transition-opacity">Students</span>
                    <i x-show="!sidebarCollapsed || mobileMenuOpen" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-xs transition-transform"></i>
                </button>
                <div x-show="open && (!sidebarCollapsed || mobileMenuOpen)"
                     x-transition
                     class="ml-8 mt-1 space-y-1"
                     x-cloak>
                    <a href="/students"
                       @click.prevent="navigate('/students')"
                       :class="currentPath === '/students' ? 'text-white bg-primary-700/30' : 'text-primary-200 hover:text-white hover:bg-primary-700/20'"
                       class="block px-3 py-2 text-sm rounded-lg">
                        All Students
                    </a>
                    <a href="/students/create"
                       @click.prevent="navigate('/students/create')"
                       :class="currentPath === '/students/create' ? 'text-white bg-primary-700/30' : 'text-primary-200 hover:text-white hover:bg-primary-700/20'"
                       class="block px-3 py-2 text-sm rounded-lg">
                        Add Student
                    </a>
                </div>
            </div>

            <!-- Teachers -->
            <a href="/teachers"
               @click.prevent="navigate('/teachers')"
               :class="currentPath.includes('/teachers') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-chalkboard-teacher text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Teachers</span>
            </a>

            <!-- Classes -->
            <a href="/classes"
               @click.prevent="navigate('/classes')"
               :class="currentPath.includes('/classes') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-door-open text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Classes</span>
            </a>

            <!-- Subjects -->
            <a href="/subjects"
               @click.prevent="navigate('/subjects')"
               :class="currentPath.includes('/subjects') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-book text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Subjects</span>
            </a>

            <!-- Attendance -->
            <a href="/attendance"
               @click.prevent="navigate('/attendance')"
               :class="currentPath.includes('/attendance') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-calendar-check text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Attendance</span>
            </a>

            <!-- Grades -->
            <a href="/grades"
               @click.prevent="navigate('/grades')"
               :class="currentPath.includes('/grades') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-chart-line text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Grades & Results</span>
            </a>

            <!-- Calendar & Events -->
            <a href="/calendar"
               @click.prevent="navigate('/calendar')"
               :class="currentPath.includes('/calendar') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-calendar-alt text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Calendar & Events</span>
            </a>

            <!-- Tokens -->
            <a href="/tokens"
               @click.prevent="navigate('/tokens')"
               :class="currentPath.includes('/tokens') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-ticket-alt text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Registration Tokens</span>
            </a>

            <!-- Divider -->
            <div class="my-4 border-t border-primary-700/50"></div>

            <!-- Settings -->
            <a href="/settings"
               @click.prevent="navigate('/settings')"
               :class="currentPath.includes('/settings') ? 'bg-primary-700/50 text-white shadow-lg' : 'text-primary-100 hover:bg-primary-700/30 hover:text-white'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fas fa-cog text-base w-5"></i>
                <span x-show="!sidebarCollapsed || mobileMenuOpen" class="transition-opacity">Settings</span>
            </a>
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-t border-primary-700/50">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-primary-100 hover:bg-primary-700/30 transition-all">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div x-show="!sidebarCollapsed || mobileMenuOpen" class="flex-1 text-left transition-opacity">
                        <p class="text-sm font-medium text-white leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-primary-200 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <i x-show="!sidebarCollapsed || mobileMenuOpen" class="fas fa-chevron-up text-xs"></i>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
                     x-cloak>
                    <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-user-circle mr-2 text-gray-400"></i>
                        My Profile
                    </a>
                    <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-cog mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div :class="sidebarOpen && !mobileMenuOpen && !sidebarCollapsed ? 'lg:ml-64' : (sidebarOpen && !mobileMenuOpen && sidebarCollapsed ? 'lg:ml-20' : '')"
         class="flex-1 flex flex-col min-h-screen transition-all duration-300">

        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Menu Button + Breadcrumb -->
                    <div class="flex items-center gap-4">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                                class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <div class="hidden sm:block">
                            <nav class="flex items-center space-x-2 text-sm" id="breadcrumb">
                                <!-- Breadcrumb will be dynamically updated -->
                            </nav>
                        </div>
                    </div>

                    <!-- Right: Search, Language, Notifications -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Search -->
                        <div class="hidden md:block relative">
                            <input type="text"
                                   placeholder="Search..."
                                   class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Language Switcher -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                <i class="fas fa-language text-lg"></i>
                                <span class="hidden sm:inline text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</span>
                            </button>
                        </div>

                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content (SPA Container) -->
        <main class="flex-1 overflow-y-auto">
            <!-- Loading Indicator -->
            <div x-show="loading"
                 x-transition
                 class="flex items-center justify-center py-20"
                 x-cloak>
                <div class="text-center">
                    <div class="spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">Loading...</p>
                </div>
            </div>

            <!-- Content Area -->
            <div x-show="!loading"
                 id="spa-content"
                 class="px-4 sm:px-6 lg:px-8 py-6 page-transition">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} Darul Arqam School Management System. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-primary-600 transition">Privacy</a>
                    <a href="#" class="hover:text-primary-600 transition">Terms</a>
                    <a href="#" class="hover:text-primary-600 transition">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SPA JavaScript -->
    <script>
        function spaApp() {
            return {
                sidebarOpen: window.innerWidth >= 1024,
                sidebarCollapsed: false,
                mobileMenuOpen: false,
                loading: false,
                currentPath: window.location.pathname,

                initSPA() {
                    // Handle window resize
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.sidebarOpen = true;
                            this.mobileMenuOpen = false;
                        } else {
                            this.sidebarOpen = false;
                        }
                    });

                    // Set initial path
                    this.currentPath = window.location.pathname;
                },

                async navigate(url) {
                    // Close mobile menu
                    this.mobileMenuOpen = false;

                    // Show loading
                    this.loading = true;

                    try {
                        // Fetch the page content
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Page not found');
                        }

                        const html = await response.text();

                        // Parse the response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Extract content
                        const content = doc.querySelector('#spa-content') || doc.querySelector('main');
                        const title = doc.querySelector('title');

                        // Update content
                        if (content) {
                            document.getElementById('spa-content').innerHTML = content.innerHTML;
                        }

                        // Update title
                        if (title) {
                            document.title = title.textContent;
                        }

                        // Update URL
                        history.pushState({ url: url }, '', url);

                        // Update current path
                        this.currentPath = url;

                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'smooth' });

                        // Reinitialize scripts in the new content
                        this.reinitializeScripts();

                    } catch (error) {
                        console.error('Navigation error:', error);
                        // Fallback to full page load
                        window.location.href = url;
                    } finally {
                        this.loading = false;
                    }
                },

                handlePopState(event) {
                    if (event.state && event.state.url) {
                        this.navigate(event.state.url);
                    }
                },

                reinitializeScripts() {
                    // Reinitialize any Alpine components in the new content
                    if (window.Alpine) {
                        window.Alpine.initTree(document.getElementById('spa-content'));
                    }

                    // Reinitialize Bootstrap components
                    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

                    const modals = document.querySelectorAll('.modal');
                    modals.forEach(modal => new bootstrap.Modal(modal));
                }
            }
        }

        // Handle form submissions via AJAX
        document.addEventListener('submit', async function(e) {
            const form = e.target;

            // Only intercept forms with data-ajax attribute
            if (!form.hasAttribute('data-ajax')) {
                return;
            }

            e.preventDefault();

            const formData = new FormData(form);
            const method = form.method || 'POST';
            const action = form.action;

            try {
                const response = await fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.redirect) {
                    window.Alpine.evaluate(document.body, '$dispatch("navigate", { url: "' + data.redirect + '" })');
                } else if (data.success) {
                    // Show success message
                    alert(data.message || 'Success!');
                } else {
                    // Show error message
                    alert(data.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Form submission error:', error);
                alert('An error occurred while submitting the form');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
