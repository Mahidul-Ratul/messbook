@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">My Messes</h4>
        <a href="{{ route('owner.messes.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Create New Mess
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($messes as $mess)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm mess-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-house-chimney-window fa-2x text-primary me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">{{ $mess->name }}</h5>
                                <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $mess->location ?? 'N/A' }}</small>
                            </div>
                        </div>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($mess->description, 120) }}</p>
                        <div class="mt-auto d-flex justify-content-end gap-2">
                            <a href="{{ route('owner.members.index', ['mess' => $mess->id]) }}" class="btn btn-sm btn-outline-success">
                                <i class="fa-solid fa-users me-1"></i>Manage Members
                            </a>
                            <a href="{{ route('owner.messes.show', $mess->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-eye me-1"></i>View
                            </a>
                            <a href="{{ route('owner.messes.edit', $mess->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('owner.messes.destroy', $mess->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mess? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash-alt me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-5">
                        <i class="fa-solid fa-house-circle-xmark fa-3x text-muted mb-3"></i>
                        <h5 class="card-title">No Messes Created</h5>
                        <p class="card-text">You haven't created any messes yet.</p>
                        <a href="{{ route('owner.messes.create') }}" class="btn btn-primary mt-2">Create Your First Mess</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
.mess-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: .5rem; }
.mess-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important; }
</style>
@endpush
