@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Manage Members</h4>
        <a href="{{ route('owner.members.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-1"></i> Add Member
        </a>
    </div>

    <div id="alert-container"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($members->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-users-slash fa-3x text-muted mb-3"></i>
                    <h5 class="card-title">No Members Found</h5>
                    <p class="card-text">You haven't added any members to your mess yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Member</th>
                                <th>Joined At</th>
                                <th>Meal Manager</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($member->email))) . '?s=40&d=mp' }}" alt="" width="40" height="40" class="rounded-circle me-3">
                                        <div>
                                            <h6 class="mb-0">{{ $member->name }}</h6>
                                            <small class="text-muted">{{ $member->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $member->pivot->created_at ? \Carbon\Carbon::parse($member->pivot->created_at)->format('d M, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input meal-manager-toggle" type="checkbox" role="switch" 
                                               data-user-id="{{ $member->id }}"
                                               id="mealManagerSwitch{{$member->id}}" 
                                               {{ $member->hasRole('meal_manager') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="mealManagerSwitch{{$member->id}}">
                                            {{ $member->hasRole('meal_manager') ? 'Enabled' : 'Disabled' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('owner.members.meals', $member->id) }}" class="btn btn-sm btn-outline-primary" title="View Meals"><i class="fa-solid fa-utensils"></i></a>
                                    <a href="{{ route('owner.members.expenses', $member->id) }}" class="btn btn-sm btn-outline-info" title="View Expenses"><i class="fa-solid fa-wallet"></i></a>
                                    <form method="POST" action="{{ route('owner.members.destroy', $member->id) }}" onsubmit="return confirm('Are you sure you want to remove this member?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Member"><i class="fa-solid fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
.table th { white-space: nowrap; }
.form-check-input { cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.meal-manager-toggle');
    const alertContainer = document.getElementById('alert-container');

    toggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const userId = this.dataset.userId;
            const isChecked = this.checked;
            const label = this.nextElementSibling;
            
            label.textContent = isChecked ? 'Enabling...' : 'Disabling...';

            fetch(`/owner/ajax/members/${userId}/toggle-meal-role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_manager: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    label.textContent = isChecked ? 'Enabled' : 'Disabled';
                    showAlert('success', data.success);
                } else {
                    // Revert the toggle on failure
                    toggle.checked = !isChecked;
                    label.textContent = !isChecked ? 'Enabled' : 'Disabled';
                    showAlert('danger', data.error || 'An unexpected error occurred.');
                }
            })
            .catch(error => {
                // Revert the toggle on network error
                toggle.checked = !isChecked;
                label.textContent = !isChecked ? 'Enabled' : 'Disabled';
                showAlert('danger', 'A network error occurred. Please try again.');
            });
        });
    });

    function showAlert(type, message) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }
});
</script>
@endpush
