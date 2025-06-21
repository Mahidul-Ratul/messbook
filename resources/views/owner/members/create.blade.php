@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Add Member</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Member by Email</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="emailSearch" class="form-label">Start typing a user's email to find them.</label>
                        <input type="text" id="emailSearch" class="form-control" placeholder="e.g., member@example.com">
                    </div>
                    <div id="searchResults" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('emailSearch');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    searchInput.addEventListener('input', function () {
        const query = this.value;

        clearTimeout(searchTimeout);
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('owner.members.search-ajax') }}?q=${query}`)
                .then(response => response.json())
                .then(users => {
                    let html = '<ul class="list-group">';
                    if (users.length === 0) {
                        html += '<li class="list-group-item text-muted">No users found.</li>';
                    } else {
                        users.forEach(user => {
                            html += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${user.name} &lt;${user.email}&gt;</span>
                                    <form method="POST" action="{{ route('owner.members.store') }}">
                                        @csrf
                                        <input type="hidden" name="user_id" value="${user.id}">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-plus me-1"></i> Add Member
                                        </button>
                                    </form>
                                </li>
                            `;
                        });
                    }
                    html += '</ul>';
                    searchResults.innerHTML = html;
                })
                .catch(error => {
                    searchResults.innerHTML = '<div class="alert alert-danger">An error occurred while searching.</div>';
                });
        }, 300);
    });
});
</script>
@endpush
