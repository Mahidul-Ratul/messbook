@extends('layouts.app')

@push('styles')
<style>
    .profile-card .card-header {
        background-color: transparent;
    }
    .profile-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: -60px;
        object-fit: cover;
    }
    .stat {
        text-align: center;
    }
    .stat .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
    }
    .stat .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6c757d;
    }
    .nav-pills .nav-link {
        color: #333;
    }
    .nav-pills .nav-link.active {
        background-color: #4a90e2;
        color: white;
    }
    .info-item i {
        width: 20px;
        text-align: center;
        margin-right: 1rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card profile-card shadow-sm h-100">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-center">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=150&d=mp' }}" alt="{{ $user->name }}" class="profile-avatar">
                    </div>
                </div>
                <div class="card-body text-center pt-2">
                    <h4 class="card-title fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted">
                        @foreach($user->roles as $role)
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        @endforeach
                    </p>
                    @if(Auth::id() == $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm mb-3">
                        <i class="fa-solid fa-pencil me-1"></i>Edit Profile
                    </a>
                    @endif
                    <hr>
                    <div class="row">
                        <div class="col-6 stat">
                            <div class="stat-value">{{ $user->messes->count() }}</div>
                            <div class="stat-label">Owned Messes</div>
                        </div>
                        <div class="col-6 stat">
                            <div class="stat-value">{{ $user->memberships->count() }}</div>
                            <div class="stat-label">Member Of</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card profile-card shadow-sm h-100">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-messes-tab" data-bs-toggle="pill" data-bs-target="#pills-messes" type="button" role="tab">Messes</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content" id="pills-tabContent">
                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="pills-details" role="tabpanel">
                        <h5 class="card-title mb-4">Contact Information</h5>
                        <p class="info-item"><i class="fa-solid fa-envelope"></i> {{ $user->email }}</p>
                        <p class="info-item"><i class="fa-solid fa-phone"></i> {{ $user->phone ?? 'Not provided' }}</p>
                        <p class="info-item"><i class="fa-solid fa-location-dot"></i> {{ $user->address ?? 'Not provided' }}</p>
                        <hr>
                        <h5 class="card-title my-4">Other Information</h5>
                        <p class="info-item"><i class="fa-solid fa-calendar-check"></i> Joined on {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <!-- Messes Tab -->
                    <div class="tab-pane fade" id="pills-messes" role="tabpanel">
                        <h5 class="card-title mb-4">Owned Messes</h5>
                        @if($user->messes->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($user->messes as $mess)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $mess->name }}
                                        <a href="{{ route('owner.mess.show') }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">This user does not own any messes.</p>
                        @endif
                        <hr>
                        <h5 class="card-title my-4">Mess Memberships</h5>
                        @if($user->memberships->count() > 0)
                             <ul class="list-group list-group-flush">
                                @foreach($user->memberships as $mess)
                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $mess->name }}
                                        <a href="{{ route('member.messes.show', $mess) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">This user is not a member of any mess.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 