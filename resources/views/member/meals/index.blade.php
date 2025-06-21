@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">My Meals</h4>
        <a href="{{ route('member.meals.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add Meal Entry
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Meal Entries</h5>
        </div>
        <div class="card-body">
            @if($meals->isEmpty())
                <div class="text-center py-4">
                    <i class="fa-solid fa-bowl-food fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No meal entries found.</p>
                    <a href="{{ route('member.meals.create') }}" class="btn btn-primary">Add Your First Meal Entry</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Mess</th>
                                <th>Meal Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meals as $meal)
                                <tr>
                                    <td>{{ $meal->date->format('d M Y') }}</td>
                                    <td>{{ $meal->mess->name }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $meal->meal_count }} meals</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('member.meals.edit', $meal->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form action="{{ route('member.meals.destroy', $meal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this meal entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $meals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
</style>
@endpush 