@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Daily Meals</h4>
        <a href="{{ route('owner.daily_meals.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Meal Entry
        </a>
    </div>

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($meals->isEmpty())
        <div class="card text-center shadow-sm">
            <div class="card-body py-5">
                <i class="fa-solid fa-cloud-moon fa-3x text-muted mb-3"></i>
                <h5 class="card-title">No Meals Recorded</h5>
                <p class="card-text">You haven't added any daily meal records yet.</p>
                <a href="{{ route('owner.daily_meals.create') }}" class="btn btn-primary mt-2">Add First Meal Entry</a>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Meal Records</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Member</th>
                                <th class="text-center">Meals</th>
                                <th>Notes</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meals as $meal)
                            <tr>
                                <td>{{ $meal->date }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $meal->user->avatar ? asset('storage/' . $meal->user->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($meal->user->email))) . '?s=40&d=mp' }}" alt="" width="32" height="32" class="rounded-circle me-2">
                                        <span>{{ $meal->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">{{ $meal->total_meal }}</span>
                                </td>
                                <td>{{ $meal->notes ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('owner.daily_meals.edit', $meal->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('owner.daily_meals.destroy', $meal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this meal entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
</style>
@endpush
