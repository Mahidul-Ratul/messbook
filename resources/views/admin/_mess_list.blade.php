<div class="row">
    @forelse($messes as $mess)
    <div class="col-md-4 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title fw-bold text-dark">{{ $mess->name }}</h5>
                        <p class="card-text text-muted small">
                            <i class="fa-solid fa-location-dot me-2"></i>{{ $mess->location }}
                        </p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; background: none; box-shadow: none;">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('admin.messes.show', $mess->id) }}"><i class="fa-solid fa-eye me-2"></i>View Details</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.messes.destroy', $mess->id) }}" onsubmit="return confirm('Are you sure you want to delete this mess?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-trash me-2"></i>Delete Mess</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-4">
                   <img src="https://ui-avatars.com/api/?name={{ urlencode($mess->owner->name) }}&background=random" alt="{{ $mess->owner->name }}" class="rounded-circle me-3" width="40">
                    <div>
                        <p class="mb-0 small fw-bold">{{ $mess->owner->name }}</p>
                        <p class="mb-0 text-muted small">Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center py-5 border-0 shadow-sm">
            <div class="card-body">
                <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="card-title">No Messes Found</h5>
                @if(request('search'))
                <p>Your search for "{{ request('search') }}" did not return any results.</p>
                @else
                <p class="text-muted">There are no messes in the system yet.</p>
                @endif
                <a href="{{ route('admin.messes') }}" class="btn btn-primary mt-2">Clear Search</a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $messes->appends(request()->except('page'))->links() }}
</div> 