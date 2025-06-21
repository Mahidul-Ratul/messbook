@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">{{ $mess->name }}</h4>
        <a href="{{ route('owner.mess.edit') }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Mess Details
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="mess-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-content" type="button" role="tab">Mess Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-content" type="button" role="tab">Members ({{ $mess->memberships->count() }})</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="mess-tab-content">
                <div class="tab-pane fade show active" id="details-content" role="tabpanel">
                    <h5 class="card-title">Details & Rules</h5>
                    <p><strong><i class="fa-solid fa-location-dot me-2 text-primary"></i>Location:</strong> {{ $mess->location ?? 'Not specified' }}</p>
                    <p><strong><i class="fa-solid fa-circle-info me-2 text-primary"></i>Description:</strong></p>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $mess->description ?? 'No description provided.' }}</p>
                </div>
                <div class="tab-pane fade" id="members-content" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Current Members</h5>
                        <a href="{{ route('owner.members.index') }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-users me-1"></i> Manage Members
                        </a>
                    </div>
                    <div class="list-group">
                        @forelse($mess->memberships as $member)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($member->email))) . '?s=40&d=mp' }}" alt="" width="32" height="32" class="rounded-circle me-2">
                                    <span>{{ $member->name }}</span>
                                </div>
                                <span class="badge bg-secondary">{{ $member->pivot->created_at ? $member->pivot->created_at->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        @empty
                            <p class="text-muted">No members yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
.card-header-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
}
.card-header-tabs .nav-link.active {
    border-bottom-color: var(--bs-primary);
    color: var(--bs-primary);
    background: none;
}
</style>
@endpush 