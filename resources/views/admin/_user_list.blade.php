@forelse($users as $user)
<div class="col-md-6 col-lg-4">
    <div class="card user-card shadow-sm mb-4">
        <div class="card-body text-center">
            <a href="{{ route('profile.show', $user) }}">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=100&d=mp' }}" alt="{{ $user->name }}" class="rounded-circle user-avatar mb-3">
            </a>
            <h5 class="card-title fw-bold">{{ $user->name }}</h5>
            <p class="card-text text-muted">{{ $user->email }}</p>
            <div class="roles-container mb-3">
                @foreach($user->roles as $role)
                    <span class="badge role-badge role-{{ strtolower($role->name) }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fa-solid fa-eye me-1"></i> View
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                        <i class="fa-solid fa-trash-alt me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="alert alert-info text-center">
        <i class="fa-solid fa-users-slash fa-3x mb-3"></i>
        <p class="h5">No users found.</p>
        <p>No users matched your search criteria.</p>
    </div>
</div>
@endforelse 