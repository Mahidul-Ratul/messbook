@extends('layouts.guest')

@section('content')

<style>
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --navbar-bg: rgba(255, 255, 255, 0.1);
        --navbar-text: rgba(255, 255, 255, 0.9);
        --navbar-border: rgba(255, 255, 255, 0.2);
    }

    [data-theme="dark"] {
        --bg-primary: #1a1a1a;
        --bg-secondary: #2d2d2d;
        --text-primary: #ffffff;
        --text-secondary: #b0b0b0;
        --border-color: #404040;
        --navbar-bg: rgba(26, 26, 26, 0.9);
        --navbar-text: rgba(255, 255, 255, 0.9);
        --navbar-border: rgba(255, 255, 255, 0.1);
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .hero-section {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
    }
    
    .background-carousel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    
    .carousel-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        opacity: 0;
        transition: opacity 1.5s ease-in-out;
    }
    
    .carousel-item.active {
        opacity: 1;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.4) 100%);
        z-index: 1;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
    }
    
    .navbar {
        background: transparent !important;
        border-bottom: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        transition: all 0.3s ease;
    }
    
    .navbar-brand {
        font-size: 1.5rem;
        font-weight: 700;
        color: white !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .nav-link {
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        color: white !important;
        transform: translateY(-1px);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .navbar-toggler {
        border: 1px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }
    
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .theme-toggle {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        margin-left: 10px;
        backdrop-filter: blur(10px);
    }

    .theme-toggle:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .card {
        background-color: var(--bg-primary);
        border-color: var(--border-color);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .bg-light {
        background-color: var(--bg-secondary) !important;
    }

    .text-muted {
        color: var(--text-secondary) !important;
    }
</style>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/"><i class="fa-solid fa-utensils me-2"></i>MessBook</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
                @endauth
            </ul>
            <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Hero Section with Background Carousel -->
<div class="hero-section d-flex align-items-center">
    <!-- Background Carousel -->
    <div class="background-carousel">
        <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1920&q=80');"></div>
    </div>
    
    <!-- Overlay -->
    <div class="hero-overlay"></div>
    
    <!-- Content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="hero-content p-5" data-aos="fade-up">
                    <h1 class="display-2 fw-bold mb-4">Welcome to MessBook</h1>
                    <p class="lead mb-4">The most modern Mess Management Software to automate your meals, expenses, and bills. Simple. Secure. Smart.</p>
                    
                    @guest
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 py-3">Register</a>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold" data-aos="fade-up">Key Features</h2>
    <div class="row text-center g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card p-4 shadow-sm h-100">
                <i class="fa-solid fa-utensils fa-3x text-primary mb-3"></i>
                <h5>Daily Meal Tracking</h5>
                <p class="small">Easily track members' daily meal counts with full accuracy.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card p-4 shadow-sm h-100">
                <i class="fa-solid fa-file-invoice-dollar fa-3x text-success mb-3"></i>
                <h5>Auto Billing & Reporting</h5>
                <p class="small">No more manual calculations. Get real-time bills & reports automatically.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card p-4 shadow-sm h-100">
                <i class="fa-solid fa-lock fa-3x text-danger mb-3"></i>
                <h5>Secure Member Access</h5>
                <p class="small">Only authorized members can join messes with full owner control.</p>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" data-aos="fade-up">Why Choose MessBook?</h2>
        <div class="row text-center g-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="p-3">
                    <i class="fa-solid fa-clock fa-3x text-warning mb-3"></i>
                    <h6>Save Time</h6>
                    <p class="small">Automate calculations & spend more time enjoying meals, not managing accounts.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="p-3">
                    <i class="fa-solid fa-chart-line fa-3x text-info mb-3"></i>
                    <h6>Track Everything</h6>
                    <p class="small">View real-time data of expenses, meals, balances & payments anytime.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="p-3">
                    <i class="fa-solid fa-user-shield fa-3x text-primary mb-3"></i>
                    <h6>Owner Controlled</h6>
                    <p class="small">Owners have full control over members, permissions, and join requests.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="p-3">
                    <i class="fa-solid fa-mobile-screen-button fa-3x text-success mb-3"></i>
                    <h6>Mobile Friendly</h6>
                    <p class="small">Manage your mess from desktop, tablet, or phone — anytime, anywhere.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How it Works -->
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold" data-aos="fade-up">How It Works</h2>
    <div class="row text-center g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="p-4">
                <i class="fa-solid fa-user-plus fa-3x text-primary mb-3"></i>
                <h5>Create Your Mess</h5>
                <p class="small">Register your mess, invite members, and configure meals easily.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="p-4">
                <i class="fa-solid fa-calendar-days fa-3x text-success mb-3"></i>
                <h5>Track Meals Daily</h5>
                <p class="small">Enter daily meal counts. Members track their personal meals too.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="p-4">
                <i class="fa-solid fa-receipt fa-3x text-danger mb-3"></i>
                <h5>Generate Bills</h5>
                <p class="small">Automatic bills & reports calculated based on actual meals & expenses.</p>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials -->
<div class="py-5 bg-light text-center" data-aos="fade-up">
    <h3 class="mb-4 fw-bold">What Our Users Say</h3>
    <blockquote class="blockquote">
        <p>"MessBook saved hours of my time every month! Full control over my mess."</p>
        <footer class="blockquote-footer mt-2">- Mess Owner, Dhaka</footer>
    </blockquote>
</div>

<!-- Call to Action -->
<div class="py-5 text-white text-center" style="background-color: #4F46E5;" data-aos="zoom-in">
    <h2 class="fw-bold">Ready to Simplify Your Mess Management?</h2>
    <p class="lead mb-4">Join hundreds of messes already using MessBook daily.</p>
    <a href="{{ route('register') }}" class="btn btn-lg btn-light px-5">Get Started Today</a>
</div>

<!-- Footer -->
<footer class="py-4 bg-dark text-white">
    <div class="container text-center">
        <p class="mb-1">MessBook © {{ date('Y') }} - All Rights Reserved</p>
        <small>Secure Mess Management SaaS System | <a href="#" class="text-white">Contact Us</a></small>
    </div>
</footer>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carousel functionality
    const carouselItems = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;
    
    // Ensure all items are initially hidden except the first one
    carouselItems.forEach((item, index) => {
        if (index === 0) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
    
    function nextSlide() {
        // Remove active class from current item
        carouselItems[currentIndex].classList.remove('active');
        
        // Move to next item
        currentIndex = (currentIndex + 1) % carouselItems.length;
        
        // Add active class to new item
        carouselItems[currentIndex].classList.add('active');
        
        console.log('Current slide:', currentIndex + 1); // Debug log
    }
    
    // Auto-slide every 3 seconds
    setInterval(nextSlide, 3000);
    
    // Manual slide function for testing
    window.testSlide = function() {
        nextSlide();
    };

    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);
    
    themeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    
    function updateThemeIcon(theme) {
        if (theme === 'dark') {
            themeIcon.className = 'fa-solid fa-sun';
        } else {
            themeIcon.className = 'fa-solid fa-moon';
        }
    }
});
</script>
@endpush
