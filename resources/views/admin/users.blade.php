@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">User Management</h4>
            <p class="text-muted mb-md-0">Manage all registered users in the system.</p>
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="input-group me-2">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search"></i></span>
                    <input type="text" id="user-search-input" class="form-control border-start-0" placeholder="Search by name or email...">
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-start border-bottom mb-4">
        <div class="nav nav-pills" id="user-role-filter">
            <button class="nav-link active" data-role="">All Users</button>
            <button class="nav-link" data-role="mess_owner">Mess Owners</button>
            <button class="nav-link" data-role="member">Members</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="row" id="user-list-container">
        @include('admin._user_list', ['users' => $users])
    </div>
    <div id="pagination-container" class="d-flex justify-content-center mt-4">
        {{ $users->links()->toHtml() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-title { font-weight: 600; }
    .user-card {
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: .75rem;
    }
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .user-avatar {
        width: 100px;
        height: 100px;
        border: 4px solid #e9ecef;
        object-fit: cover;
    }
    .roles-container {
        min-height: 25px;
    }
    .role-badge {
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
        letter-spacing: 0.5px;
    }
    .role-admin { background-color: #f44336; color: white; }
    .role-mess_owner { background-color: #4a90e2; color: white; }
    .role-member { background-color: #7ed321; color: white; }

    #user-role-filter .nav-link {
        color: #6c757d;
        margin-bottom: -1px;
        border-radius: .5rem .5rem 0 0;
    }
    #user-role-filter .nav-link.active {
        color: #495057;
        background-color: transparent;
        border-bottom: 2px solid #4a90e2;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let currentRole = '';
    let currentSearch = '';

    function fetchUsers(page = 1) {
        let url = '{{ route("admin.users") }}';
        
        // Show a loading indicator if you have one
        $('#user-list-container').html('<div class="col-12 text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');

        $.ajax({
            url: url,
            data: {
                page: page,
                role: currentRole,
                search: currentSearch
            },
            success: function(data) {
                $('#user-list-container').html(data.users_html);
                $('#pagination-container').html(data.pagination_html);
            },
            error: function() {
                alert('An error occurred while fetching users.');
                // You might want to restore the old content or show an error message
            }
        });
    }

    // Role filter handler
    $('#user-role-filter .nav-link').on('click', function(e) {
        e.preventDefault();
        $('#user-role-filter .nav-link').removeClass('active');
        $(this).addClass('active');
        currentRole = $(this).data('role');
        fetchUsers(1);
    });

    // Search input handler
    $('#user-search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        currentSearch = $(this).val();
        searchTimeout = setTimeout(function() {
            fetchUsers(1);
        }, 300);
    });

    // Pagination handler
    $(document).on('click', '#pagination-container .pagination a', function(e) {
        e.preventDefault();
        const url = new URL($(this).attr('href'));
        const page = url.searchParams.get('page');
        fetchUsers(page);
    });
});
</script>
@endpush
