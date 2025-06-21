@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page-Title & Search -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="page-title">All Messes</h4>
            <p class="text-muted mb-0">Browse, search, and manage all messes in the system.</p>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <div class="input-group w-75">
                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Search messes..." value="{{ request('search') }}">
                    <span class="input-group-text bg-primary text-white"><i class="fa-solid fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Test Dropdown -->
    <div class="mb-3">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Test Dropdown
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Test Item 1</a></li>
                <li><a class="dropdown-item" href="#">Test Item 2</a></li>
            </ul>
        </div>
    </div>

    <!-- Mess List Container -->
    <div id="mess-list-container">
        @include('admin._mess_list')
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Test if Bootstrap is available
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap Dropdown available:', typeof bootstrap.Dropdown !== 'undefined');
    }
    
    let searchTimeout;

    function fetchMesses(page = 1) {
        const query = $('#search-input').val();
        
        $.ajax({
            url: "{{ route('admin.messes') }}?page=" + page,
            type: 'GET',
            data: { 'search': query },
            success: function(data) {
                $('#mess-list-container').html(data);
                console.log('AJAX content loaded, reinitializing dropdowns...');
                // Reinitialize Bootstrap dropdowns after AJAX content load
                var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
                console.log('Found dropdown elements:', dropdownElementList.length);
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    try {
                        return new bootstrap.Dropdown(dropdownToggleEl);
                    } catch (error) {
                        console.error('Error initializing dropdown:', error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + status + " " + error);
            }
        });
    }

    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            fetchMesses(1); // Reset to page 1 for new search
        }, 300);
    });

    $(document).on('click', '#mess-list-container .pagination a', function(event) {
        event.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        fetchMesses(page);
    });

    // Initialize dropdowns on page load
    console.log('Page loaded, initializing dropdowns...');
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    console.log('Found dropdown elements on page load:', dropdownElementList.length);
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        try {
            console.log('Initializing dropdown for:', dropdownToggleEl);
            return new bootstrap.Dropdown(dropdownToggleEl);
        } catch (error) {
            console.error('Error initializing dropdown:', error);
        }
    });

    // Add click event listener for debugging
    $(document).on('click', '[data-bs-toggle="dropdown"]', function(e) {
        console.log('Dropdown button clicked:', e.target);
    });
});
</script>
@endpush

<style>
    .page-title {
        font-weight: 600;
    }
    .card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 1px solid #e0e0e0;
        border-radius: 0.5rem;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    .btn-link {
        text-decoration: none;
    }
    
    /* Ensure dropdowns work properly */
    .dropdown-toggle::after {
        display: none;
    }
    
    .dropdown-menu {
        z-index: 1050;
    }
    
    .dropdown-item {
        cursor: pointer;
    }
    
    .dropdown-item:hover {
        background-color: var(--bg-secondary);
    }
    
    /* Fix for dark mode dropdowns */
    [data-theme="dark"] .dropdown-menu {
        background-color: var(--card-bg);
        border-color: var(--card-border);
    }
    
    [data-theme="dark"] .dropdown-item {
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .dropdown-item:hover {
        background-color: var(--bg-secondary);
    }
</style>
@endsection
