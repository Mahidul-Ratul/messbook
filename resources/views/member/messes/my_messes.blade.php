@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">My Messes</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($myMesses as $mess)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm mess-card">
                    <div class="card-header bg-light border-bottom">
                         <div class="d-flex align-items-center">
                            <i class="fa-solid fa-house-chimney-window fa-2x text-primary me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">{{ $mess->name }}</h5>
                                <small class="text-muted">Est. {{ $mess->created_at->format('M Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted mb-2"><i class="fa-solid fa-location-dot me-2 text-secondary"></i>{{ $mess->location ?? 'No address provided' }}</p>
                        <p class="card-text text-muted"><i class="fa-solid fa-user-shield me-2 text-secondary"></i>Owned by {{ $mess->owner->name }}</p>
                        <hr>
                        <p class="card-text">{!! Str::limit(nl2br(e($mess->rules)), 100) !!}</p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2 pb-3">
                        <a href="{{ route('member.messes.show', $mess) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa-solid fa-circle-info me-1"></i> View Details
                        </a>
                        <form action="{{ route('member.messes.leave', $mess) }}" method="POST" class="d-inline" onsubmit="return confirmLeave(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Leave Mess
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa-solid fa-house-circle-xmark fa-3x text-muted mb-3"></i>
                        <h5 class="card-title">No Messes Joined</h5>
                        <p class="card-text">You haven't joined any messes yet.</p>
                        <a href="{{ route('member.messes.join') }}" class="btn btn-primary">Join a Mess Now</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($myMesses->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $myMesses->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.mess-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: .5rem;
    border: 1px solid #e0e0e0;
}
.mess-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
}
.page-title {
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
function confirmLeave(event) {
    event.preventDefault();
    const form = event.target;
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be removed from this mess. This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, leave it!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}
</script>
@endpush
