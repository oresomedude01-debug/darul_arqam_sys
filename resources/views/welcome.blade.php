<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Darul Arqam') }} - Islamic Education Excellence</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Darul Arqam School Management System - Excellence in Islamic Education">
    <meta name="keywords" content="Islamic school, education, Darul Arqam, enrollment, school management">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] { display: none !important; }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Hero gradient background */
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Animated gradient */
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animated-gradient {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Fade in animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        /* Stagger animation delays */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        .delay-600 { animation-delay: 600ms; }

        /* Initial hidden state for animated elements */
        .fade-in-up, .fade-in-left, .fade-in-right {
            opacity: 0;
        }

        /* WhatsApp Button Pulse */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.9);
                opacity: 1;
            }
            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .whatsapp-pulse::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #25D366;
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        /* Card hover effect */
        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Button shine effect */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }

        .btn-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-shine:hover::before {
            left: 100%;
        }
    </style>

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
</head>

<body class="bg-gray-50 antialiased" x-data="{ mobileMenuOpen: false, contactModalOpen: false }">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Darul Arqam</h1>
                        <p class="text-xs text-gray-500">Islamic School</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-purple-600 transition font-medium">Home</a>
                    <a href="#about" class="text-gray-700 hover:text-purple-600 transition font-medium">About</a>
                    <a href="#features" class="text-gray-700 hover:text-purple-600 transition font-medium">Features</a>
                    <a href="#contact" class="text-gray-700 hover:text-purple-600 transition font-medium">Contact</a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-lg hover:shadow-lg transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 transition font-medium">
                                Login
                            </a>
                            <a href="{{ route('enrollment.token') }}" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-lg hover:shadow-lg transition btn-shine">
                                Enroll Now
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen"
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="md:hidden bg-white border-t"
             x-cloak>
            <div class="px-4 py-4 space-y-3">
                <a href="#home" class="block text-gray-700 hover:text-purple-600 transition font-medium py-2">Home</a>
                <a href="#about" class="block text-gray-700 hover:text-purple-600 transition font-medium py-2">About</a>
                <a href="#features" class="block text-gray-700 hover:text-purple-600 transition font-medium py-2">Features</a>
                <a href="#contact" class="block text-gray-700 hover:text-purple-600 transition font-medium py-2">Contact</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-lg text-center">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block text-gray-700 hover:text-purple-600 transition font-medium py-2">
                            Login
                        </a>
                        <a href="{{ route('enrollment.token') }}" class="block px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-lg text-center">
                            Enroll Now
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-24 pb-20 md:pt-32 md:pb-32 animated-gradient relative overflow-hidden">
        <!-- Animated Background Shapes -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/4 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-1/2 -left-1/4 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight fade-in-left">
                        Excellence in <br>
                        <span class="text-yellow-300">Islamic Education</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-100 mb-8 fade-in-left delay-200">
                        Empowering young minds with knowledge, character, and Islamic values for a brighter future.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 fade-in-left delay-300">
                        <a href="{{ route('enrollment.token') }}"
                           class="px-8 py-4 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition shadow-xl btn-shine text-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Enroll Your Child
                        </a>
                        <a href="#about"
                           class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition text-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Learn More
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12 fade-in-left delay-400">
                        <div>
                            <h3 class="text-3xl font-bold text-yellow-300">1000+</h3>
                            <p class="text-sm text-gray-200">Students</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-yellow-300">50+</h3>
                            <p class="text-sm text-gray-200">Teachers</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-yellow-300">15+</h3>
                            <p class="text-sm text-gray-200">Years</p>
                        </div>
                    </div>
                </div>

                <!-- Right Illustration -->
                <div class="hidden lg:block fade-in-right delay-200">
                    <div class="relative float-animation">
                        <!-- Modern SVG Illustration -->
                        <svg viewBox="0 0 500 500" class="w-full h-auto drop-shadow-2xl">
                            <!-- Education Illustration - Students with Books -->
                            <circle cx="250" cy="250" r="200" fill="#fff" opacity="0.1"/>
                            <circle cx="250" cy="250" r="150" fill="#fff" opacity="0.2"/>

                            <!-- Book Icon -->
                            <rect x="150" y="200" width="200" height="150" rx="10" fill="#fff" opacity="0.9"/>
                            <rect x="170" y="220" width="60" height="3" rx="1.5" fill="#667eea"/>
                            <rect x="170" y="235" width="100" height="3" rx="1.5" fill="#764ba2"/>
                            <rect x="170" y="250" width="80" height="3" rx="1.5" fill="#f093fb"/>
                            <rect x="170" y="265" width="90" height="3" rx="1.5" fill="#667eea"/>
                            <rect x="170" y="280" width="70" height="3" rx="1.5" fill="#764ba2"/>

                            <!-- Graduation Cap -->
                            <polygon points="250,150 200,170 200,180 250,200 300,180 300,170" fill="#fbbf24" opacity="0.9"/>
                            <rect x="245" y="140" width="10" height="30" fill="#fbbf24"/>
                            <circle cx="250" cy="135" r="8" fill="#fbbf24"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">About Darul Arqam</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-blue-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    A leading Islamic educational institution dedicated to nurturing minds and souls through comprehensive
                    Islamic and modern education.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-quran text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Islamic Foundation</h3>
                    <p class="text-gray-600">
                        Comprehensive Quranic studies, Arabic language, and Islamic teachings form the core of our curriculum.
                    </p>
                </div>

                <div class="text-center p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Academic Excellence</h3>
                    <p class="text-gray-600">
                        Modern curriculum aligned with national standards, preparing students for academic success.
                    </p>
                </div>

                <div class="text-center p-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Character Building</h3>
                    <p class="text-gray-600">
                        Emphasis on moral values, ethics, and character development to create responsible citizens.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Features</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-blue-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    State-of-the-art facilities and comprehensive programs designed for holistic student development.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature Card 1 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-laptop text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Online Learning</h3>
                    <p class="text-gray-600 text-sm">
                        Modern e-learning platform for flexible and accessible education.
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Expert Faculty</h3>
                    <p class="text-gray-600 text-sm">
                        Highly qualified and experienced teachers dedicated to excellence.
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-mosque text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Prayer Facilities</h3>
                    <p class="text-gray-600 text-sm">
                        Dedicated prayer spaces and regular Islamic activities.
                    </p>
                </div>

                <!-- Feature Card 4 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-futbol text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Sports & Activities</h3>
                    <p class="text-gray-600 text-sm">
                        Comprehensive sports programs and extracurricular activities.
                    </p>
                </div>

                <!-- Feature Card 5 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-book-reader text-pink-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Library Resources</h3>
                    <p class="text-gray-600 text-sm">
                        Extensive collection of books and digital resources.
                    </p>
                </div>

                <!-- Feature Card 6 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bus text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Safe Transport</h3>
                    <p class="text-gray-600 text-sm">
                        Secure and reliable transportation services.
                    </p>
                </div>

                <!-- Feature Card 7 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-utensils text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cafeteria</h3>
                    <p class="text-gray-600 text-sm">
                        Healthy and nutritious meals prepared with care.
                    </p>
                </div>

                <!-- Feature Card 8 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-14 h-14 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Security</h3>
                    <p class="text-gray-600 text-sm">
                        24/7 security and safe learning environment.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-blue-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <form x-data="contactForm()" @submit.prevent="submitForm" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                            <input type="text"
                                   x-model="formData.name"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition"
                                   placeholder="John Doe">
                            <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1" x-cloak></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email"
                                   x-model="formData.email"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition"
                                   placeholder="john@example.com">
                            <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1" x-cloak></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <input type="text"
                                   x-model="formData.subject"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition"
                                   placeholder="Enrollment Inquiry">
                            <p x-show="errors.subject" x-text="errors.subject" class="text-red-500 text-sm mt-1" x-cloak></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea x-model="formData.message"
                                      required
                                      rows="5"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition resize-none"
                                      placeholder="Tell us about your inquiry..."></textarea>
                            <p x-show="errors.message" x-text="errors.message" class="text-red-500 text-sm mt-1" x-cloak></p>
                        </div>

                        <button type="submit"
                                :disabled="loading"
                                :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-lg font-semibold hover:shadow-lg transition btn-shine">
                            <span x-show="!loading">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </span>
                            <span x-show="loading" x-cloak>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Sending...
                            </span>
                        </button>

                        <!-- Success Message -->
                        <div x-show="success"
                             x-transition
                             class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"
                             x-cloak>
                            <i class="fas fa-check-circle mr-2"></i>
                            Thank you! Your message has been sent successfully.
                        </div>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-8 rounded-2xl">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h3>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Address</h4>
                                    <p class="text-gray-600">123 Education Street<br>Lagos, Nigeria</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Phone</h4>
                                    <p class="text-gray-600">+234 XXX XXX XXXX</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Email</h4>
                                    <p class="text-gray-600">info@darularqam.edu</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Office Hours</h4>
                                    <p class="text-gray-600">Mon - Fri: 8:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-12 h-12 bg-white rounded-lg flex items-center justify-center hover:bg-purple-600 hover:text-white transition shadow-md">
                                <i class="fab fa-facebook-f text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-white rounded-lg flex items-center justify-center hover:bg-blue-500 hover:text-white transition shadow-md">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-white rounded-lg flex items-center justify-center hover:bg-pink-600 hover:text-white transition shadow-md">
                                <i class="fab fa-instagram text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-white rounded-lg flex items-center justify-center hover:bg-blue-700 hover:text-white transition shadow-md">
                                <i class="fab fa-linkedin-in text-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">Darul Arqam</h3>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Excellence in Islamic Education. Nurturing minds, building character, inspiring futures.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#home" class="hover:text-white transition">Home</a></li>
                        <li><a href="#about" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Admissions</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('enrollment.token') }}" class="hover:text-white transition">Online Enrollment</a></li>
                        <li><a href="#" class="hover:text-white transition">Admission Requirements</a></li>
                        <li><a href="#" class="hover:text-white transition">Fee Structure</a></li>
                        <li><a href="#" class="hover:text-white transition">Scholarships</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">Subscribe to get updates</p>
                    <form class="flex">
                        <input type="email"
                               placeholder="Your email"
                               class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg focus:outline-none focus:border-purple-600">
                        <button type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-500 rounded-r-lg hover:shadow-lg transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Darul Arqam School Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/234XXXXXXXXXX?text=Hello,%20I%20would%20like%20to%20inquire%20about%20Darul%20Arqam%20School"
       target="_blank"
       class="fixed bottom-6 right-6 z-50 w-16 h-16 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center shadow-2xl transition transform hover:scale-110 whatsapp-pulse group"
       title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp text-white text-3xl relative z-10"></i>
        <span class="absolute right-full mr-3 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none">
            Chat with us!
        </span>
    </a>

    <!-- Contact Form Script -->
    <script>
        function contactForm() {
            return {
                formData: {
                    name: '',
                    email: '',
                    subject: '',
                    message: ''
                },
                errors: {},
                loading: false,
                success: false,

                async submitForm() {
                    this.errors = {};
                    this.loading = true;
                    this.success = false;

                    // Validate
                    if (!this.formData.name) {
                        this.errors.name = 'Name is required';
                    }
                    if (!this.formData.email) {
                        this.errors.email = 'Email is required';
                    } else if (!this.validateEmail(this.formData.email)) {
                        this.errors.email = 'Please enter a valid email';
                    }
                    if (!this.formData.subject) {
                        this.errors.subject = 'Subject is required';
                    }
                    if (!this.formData.message) {
                        this.errors.message = 'Message is required';
                    }

                    if (Object.keys(this.errors).length > 0) {
                        this.loading = false;
                        return;
                    }

                    // Simulate form submission (replace with actual API call)
                    setTimeout(() => {
                        console.log('Form submitted:', this.formData);
                        this.loading = false;
                        this.success = true;

                        // Reset form
                        this.formData = {
                            name: '',
                            email: '',
                            subject: '',
                            message: ''
                        };

                        // Hide success message after 5 seconds
                        setTimeout(() => {
                            this.success = false;
                        }, 5000);
                    }, 2000);
                },

                validateEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                }
            };
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offset = 80; // Account for fixed nav height
                    const targetPosition = target.offsetTop - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
