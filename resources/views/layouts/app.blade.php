<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MessBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')

    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #f4f7f6;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #e0e0e0;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e0e0e0;
            --sidebar-link: #4a5568;
            --sidebar-link-hover: #4a90e2;
            --navbar-bg: #ffffff;
            --navbar-border: #e0e0e0;
            --card-bg: #ffffff;
            --card-border: #e0e0e0;
        }

        [data-theme="dark"] {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --bg-tertiary: #1f1f1f;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border-color: #404040;
            --sidebar-bg: #2d2d2d;
            --sidebar-border: #404040;
            --sidebar-link: #b0b0b0;
            --sidebar-link-hover: #4a90e2;
            --navbar-bg: #2d2d2d;
            --navbar-border: #404040;
            --card-bg: #2d2d2d;
            --card-border: #404040;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-tertiary);
            overflow-x: hidden;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .sidebar, .main-content {
            transition: all 0.3s ease-in-out;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding-top: 20px;
        }

        .sidebar .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--text-primary) !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }

        .sidebar .navbar-brand i {
            margin-right: 10px;
        }

        .sidebar .nav-link {
            color: var(--sidebar-link);
            font-weight: 500;
            margin: 5px 15px;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: var(--sidebar-link-hover);
            color: white;
        }

        .sidebar .nav-link .fa-fw {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 260px;
            padding: 0;
            width: calc(100% - 260px);
        }

        .top-navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            height: 70px;
            padding: 0 30px;
            transition: all 0.3s ease;
        }

        .content {
            padding: 30px;
        }
        
        /* Collapsed Sidebar Styles */
        .sidebar-collapsed .sidebar {
            width: 80px;
        }
        .sidebar-collapsed .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
        }
        .sidebar-collapsed .sidebar .navbar-brand {
            padding-left: 0;
            padding-right: 0;
            justify-content: center;
        }
        .sidebar-collapsed .sidebar .brand-text {
            display: none;
        }
        .sidebar-collapsed .sidebar .navbar-brand i {
            margin-right: 0;
        }
        .sidebar-collapsed .sidebar .nav-link-text {
            display: none;
        }
        .sidebar-collapsed .sidebar .nav-link {
            justify-content: center;
        }
        .sidebar-collapsed .sidebar .nav-link .fa-fw {
            margin-right: 0;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .theme-toggle:hover {
            background: var(--bg-secondary);
            transform: scale(1.1);
        }

        /* Card and component dark mode support */
        .card {
            background-color: var(--card-bg);
            border-color: var(--card-border);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .bg-light {
            background-color: var(--bg-secondary) !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background-color: var(--bg-secondary);
        }

        .btn-light {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-light:hover {
            background-color: var(--border-color);
        }
    </style>
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">
            <a class="navbar-brand" href="#">
                <i class="fa-solid fa-utensils text-primary"></i> <span class="brand-text">MessBook</span>
            </a>
            <ul class="nav flex-column flex-grow-1">
                 @include('layouts.sidebar')
            </ul>
            </div>
            
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar top-navbar justify-content-between">
                <button class="btn btn-light" id="sidebar-toggle">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="d-flex align-items-center">
                    <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                        <i class="fa-solid fa-moon" id="themeIcon"></i>
                    </button>

                    <div class="dropdown ms-3">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?s=40&d=mp' }}" alt="" width="32" height="32" class="rounded-circle me-2">
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('profile.show', Auth::user()) }}"><i class="fa-solid fa-user-circle me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                                    <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                    </form>
                            </li>
                        </ul>
            </div>
        </div>
    </nav>

            <!-- Content Area -->
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const wrapper = document.getElementById('wrapper');
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        // Sidebar toggle functionality
        sidebarToggle.addEventListener('click', function() {
            wrapper.classList.toggle('sidebar-collapsed');
        });

        // Theme toggle functionality
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
</body>
</html>
