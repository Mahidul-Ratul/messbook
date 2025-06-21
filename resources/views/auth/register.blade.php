@extends('layouts.guest')

@section('content')
<style>
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --card-bg: #ffffff;
        --card-border: #dee2e6;
        --input-bg: #ffffff;
        --input-border: #ced4da;
        --input-focus-border: #4a90e2;
    }

    [data-theme="dark"] {
        --bg-primary: #1a1a1a;
        --bg-secondary: #2d2d2d;
        --text-primary: #ffffff;
        --text-secondary: #b0b0b0;
        --border-color: #404040;
        --card-bg: #2d2d2d;
        --card-border: #404040;
        --input-bg: #1a1a1a;
        --input-border: #404040;
        --input-focus-border: #4a90e2;
    }

    body {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .register-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        width: 100%;
        max-width: 450px;
        transition: all 0.3s ease;
    }

    .register-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .register-header .brand-icon {
        font-size: 3rem;
        color: #4a90e2;
        margin-bottom: 1rem;
    }

    .register-header h2 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .register-header p {
        color: var(--text-secondary);
        margin: 0;
    }

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-control {
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: var(--input-bg);
        border-color: var(--input-focus-border);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .form-floating label {
        color: var(--text-secondary);
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--text-secondary);
    }

    .btn-primary {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        border: none;
        padding: 12px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(74, 144, 226, 0.4);
    }

    .register-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .register-footer a {
        color: #4a90e2;
        text-decoration: none;
        font-weight: 500;
    }

    .register-footer a:hover {
        text-decoration: underline;
    }

    .theme-toggle {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .theme-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .alert {
        background-color: var(--card-bg);
        border-color: var(--card-border);
        color: var(--text-primary);
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>

<!-- Theme Toggle Button -->
<button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
    <i class="fa-solid fa-moon" id="themeIcon"></i>
</button>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <div class="brand-icon">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h2>Create Account</h2>
            <p>Join MessBook and start managing your mess</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-floating">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" required autofocus>
                <label for="name">
                    <i class="fa-solid fa-user me-2"></i>Full Name
                </label>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                <label for="email">
                    <i class="fa-solid fa-envelope me-2"></i>Email Address
                </label>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating">
                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Mess Member</option>
                    <option value="mess_owner" {{ old('role') == 'mess_owner' ? 'selected' : '' }}>Mess Owner</option>
                </select>
                <label for="role">
                    <i class="fa-solid fa-user-tag me-2"></i>Role
                </label>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                <label for="password">
                    <i class="fa-solid fa-lock me-2"></i>Password
                </label>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                <label for="password_confirmation">
                    <i class="fa-solid fa-lock me-2"></i>Confirm Password
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-user-plus me-2"></i>Create Account
            </button>
        </form>

        <div class="register-footer">
            <span style="color: var(--text-secondary);">Already have an account? </span>
            <a href="{{ route('login') }}">
                <i class="fa-solid fa-sign-in-alt me-1"></i>Sign in
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
