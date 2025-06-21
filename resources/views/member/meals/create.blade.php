@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Add Meal Entry</h4>
        <a href="{{ route('member.meals.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to My Meals
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">New Meal Entry</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('member.meals.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="mess_id" class="form-label">Mess</label>
                            <select class="form-control @error('mess_id') is-invalid @enderror" id="mess_id" name="mess_id" required>
                                <option value="">Select a Mess</option>
                                @foreach($messes as $mess)
                                    <option value="{{ $mess->id }}" {{ old('mess_id') == $mess->id ? 'selected' : '' }}>
                                        {{ $mess->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mess_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meal_count" class="form-label">Number of Meals</label>
                            <input type="number" class="form-control @error('meal_count') is-invalid @enderror" id="meal_count" name="meal_count" value="{{ old('meal_count', 1) }}" min="0" max="10" required>
                            <div class="form-text">Enter the number of meals you had on this date (0-10).</div>
                            @error('meal_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-2"></i>Add Meal Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
</style>
@endpush 