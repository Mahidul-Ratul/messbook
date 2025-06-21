@forelse ($availableMesses as $mess)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm mess-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $mess->owner->avatar ? asset('storage/' . $mess->owner->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($mess->owner->email))) . '?s=40&d=mp' }}" class="rounded-circle me-3" alt="{{ $mess->owner->name }}">
                    <div>
                        <h5 class="card-title mb-0">{{ $mess->name }}</h5>
                        <small class="text-muted">Owned by {{ $mess->owner->name }}</small>
                    </div>
                </div>
                <p class="card-text text-muted"><i class="fa-solid fa-location-dot me-2"></i>{{ $mess->address ?? 'No address provided' }}</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-end pb-3">
                <form action="{{ route('member.messes.send-request', $mess) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" {{ $userMessCount >= 2 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-paper-plane me-1"></i> Send Request
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-light text-center">
            <p class="h5">No messes available to join.</p>
            <p class="text-muted mb-0">Either you have already joined, sent a request, or no messes match your search.</p>
        </div>
    </div>
@endforelse 