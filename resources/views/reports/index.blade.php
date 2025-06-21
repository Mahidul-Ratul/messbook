@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Select a Mess</h4>
    </div>

    @if($messes->isEmpty())
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="fa-solid fa-house-circle-xmark fa-3x text-muted mb-3"></i>
                <h5 class="card-title">No Messes Found</h5>
                <p class="card-text">You are not a member of any mess. Please join a mess to view reports.</p>
                <a href="{{ route('member.messes.join') }}" class="btn btn-primary">Join a Mess</a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($messes as $mess)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm mess-card-selectable">
                        <a href="{{ route('reports.show', $mess) }}" class="text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-house-chimney-window fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">{{ $mess->name }}</h5>
                                <p class="card-text text-muted">{{ $mess->location ?? 'No address provided' }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.page-title {
    font-weight: 600;
}
.mess-card-selectable {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.mess-card-selectable:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
}
</style>
@endpush 